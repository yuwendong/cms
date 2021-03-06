<?php
class Amazon {
	private $token;
	private $encPass;
	private $encSalt;
	private $server = 'http://uk-amazon.openbaypro.com/';
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;

		$this->token   = $registry->get('config')->get('openbay_amazon_token');
		$this->encPass = $registry->get('config')->get('openbay_amazon_enc_string1');
		$this->encSalt = $registry->get('config')->get('openbay_amazon_enc_string2');

	}

	public function __get($name) {
		return $this->registry->get($name);
	}

	public function orderNew($orderId) {
		if ($this->config->get('amazon_status') != 1) {
			return;
		}

		/* Is called from front-end? */
		if (!defined('HTTPS_CATALOG')) {
			$this->load->model('openbay/amazon_order');
			$amazonOrderId = $this->model_openbay_amazon_order->getAmazonOrderId($orderId);

			$this->load->library('log');
			$logger = new Log('amazon_stocks.log');
			$logger->write('orderNew() called with order id: ' . $orderId);

			//Stock levels update
			if ($this->openbay->addonLoad('openstock')) {
				$logger->write('openStock found installed.');

				$osCases = $this->osCases($orderId);
				$logger->write(print_r($osCases, true));
				$quantityData = array();
				foreach ($osCases as $osCase) {
					$amazonSkuRows = $this->getLinkedSkus($osCase['pid'], $osCase['var']);
					foreach($amazonSkuRows as $amazonSkuRow) {
						$quantityData[$amazonSkuRow['amazon_sku']] = $osCase['qty_left'];
					}
				}
				if(!empty($quantityData)) {
					$logger->write('Updating quantities with data: ' . print_r($quantityData, true));
					$this->updateQuantities($quantityData);
				} else {
					$logger->write('No quantity data need to be posted.');
				}
			} else {
				$orderedCases = $this->getOrderdCases($orderId);
				$orderedCaseIds = array();
				foreach($orderedCases as $orderedCase) {
					$orderedCaseIds[] = $orderedCase['case_id'];
				}
				$this->putStockUpdateBulk($orderedCaseIds);
			}
			$logger->write('orderNew() exiting');
		}
	}

	public function caseUpdateListen($caseId, $data) {
		$logger = new Log('amazon_stocks.log');
		$logger->write('caseUpdateListen called for case id: ' . $caseId);

		if ($this->openbay->addonLoad('openstock') && (isset($data['has_option']) && $data['has_option'] == 1)) {
			$logger->write('openStock found installed and case has options.');
			$quantityData = array();
			foreach($data['case_option_stock'] as $optStock) {
				$amazonSkuRows = $this->getLinkedSkus($caseId, $optStock['var']);
				foreach($amazonSkuRows as $amazonSkuRow) {
					$quantityData[$amazonSkuRow['amazon_sku']] = $optStock['stock'];
				}
			}
			if(!empty($quantityData)) {
				$logger->write('Updating quantities with data: ' . print_r($quantityData, true));
				$this->updateQuantities($quantityData);
			} else {
				$logger->write('No quantity data need to be posted.');
			}

		} else {
			$this->putStockUpdateBulk(array($caseId));
		}
		$logger->write('caseUpdateListen() exiting');
	}

	public function updateOrder($orderId, $orderStatusString, $courier_id = '', $courierFromList = true, $tracking_no = '') {

		if ($this->config->get('amazon_status') != 1) {
			return;
		}

		/* Is called from admin? */
		if (!defined('HTTPS_CATALOG')) {
			return;
		}

		$amazonOrder = $this->getOrder($orderId);

		if(!$amazonOrder) {
			return;
		}

		$amazonOrderId = $amazonOrder['amazon_order_id'];


		$log = new Log('amazon.log');
		$log->write("Order's $amazonOrderId status changed to $orderStatusString");


		$this->load->model('openbay/amazon');
		$amazonOrderCases = $this->model_openbay_amazon->getAmazonOrderedCases($orderId);


		$requestNode = new SimpleXMLElement('<Request/>');

		$requestNode->addChild('AmazonOrderId', $amazonOrderId);
		$requestNode->addChild('Status', $orderStatusString);

		if(!empty($courier_id)) {
			if($courierFromList) {
				$requestNode->addChild('CourierId', $courier_id);
			} else {
				$requestNode->addChild('CourierOther', $courier_id);
			}
			$requestNode->addChild('TrackingNo', $tracking_no);
		}

		$orderItemsNode = $requestNode->addChild('OrderItems');

		foreach ($amazonOrderCases as $case) {
			$newOrderItem = $orderItemsNode->addChild('OrderItem');
			$newOrderItem->addChild('ItemId', htmlspecialchars($case['amazon_order_item_id']));
			$newOrderItem->addChild('Quantity', (int)$case['quantity']);
		}

		$doc = new DOMDocument('1.0');
		$doc->preserveWhiteSpace = false;
		$doc->loadXML($requestNode->asXML());
		$doc->formatOutput = true;

		$this->model_openbay_amazon->updateAmazonOrderTracking($orderId, $courier_id, $courierFromList, !empty($courier_id) ? $tracking_no : '');
		$log->write('Request: ' . $doc->saveXML());
		$response = $this->callWithResponse('order/update2', $doc->saveXML(), false);
		$log->write("Response for Order's status update: $response");
	}

	public function getCategoryTemplates() {
		$result = $this->callWithResponse("casev2/RequestTemplateList");
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function registerInsertion($data) {
		$result = $this->callWithResponse("casev2/RegisterInsertionRequest", $data);
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function insertCase($data) {
		$result = $this->callWithResponse("casev2/InsertCaseRequest", $data);
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function updateQuantities($data) {
		$result = $this->callWithResponse("case/UpdateQuantityRequest", $data);
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function getStockUpdatesStatus($data) {
		$result = $this->callWithResponse("status/StockUpdates", $data);
		if(isset($result)) {
			return $result;
		} else {
			return false;
		}
	}

	public function callNoResponse($method, $data = array(), $isJson = true) {
		if  ($isJson) {
			$argString = json_encode($data);
		} else {
			$argString = $data;
		}

		$token = $this->pbkdf2($this->encPass, $this->encSalt, 1000, 32);
		$crypt = $this->encrypt($argString, $token, true);

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $this->server . $method,
			CURLOPT_USERAGENT => 'OpenBay Pro for Amazon/Opencart',
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 2,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => 'token=' . $this->token . '&data=' . rawurlencode($crypt),
		);
		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		curl_exec($ch);

		curl_close($ch);
	}

	public function callWithResponse($method, $data = array(), $isJson = true) {
		if  ($isJson) {
			$argString = json_encode($data);
		} else {
			$argString = $data;
		}

		$token = $this->pbkdf2($this->encPass, $this->encSalt, 1000, 32);
		$crypt = $this->encrypt($argString, $token, true);

		$defaults = array(
			CURLOPT_POST            => 1,
			CURLOPT_HEADER          => 0,
			CURLOPT_URL             => $this->server . $method,
			CURLOPT_USERAGENT       => 'OpenBay Pro for Amazon/Opencart',
			CURLOPT_FRESH_CONNECT   => 1,
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_FORBID_REUSE    => 1,
			CURLOPT_TIMEOUT         => 30,
			CURLOPT_SSL_VERIFYPEER  => 0,
			CURLOPT_SSL_VERIFYHOST  => 0,
			CURLOPT_POSTFIELDS      => 'token=' . $this->token . '&data=' . rawurlencode($crypt),
		);
		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		$response = curl_exec($ch);

		curl_close($ch);

		return $response;
	}

	public function decryptArgs($crypt, $isBase64 = true) {
		if ($isBase64) {
			$crypt = base64_decode($crypt, true);
			if (!$crypt) {
				return false;
			}
		}

		$token = $this->pbkdf2($this->encPass, $this->encSalt, 1000, 32);
		$data = $this->decrypt($crypt, $token);

		return $data;
	}

	private function encrypt($msg, $k, $base64 = false) {
		if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', ''))
			return false;

		$iv = mcrypt_create_iv(32, MCRYPT_RAND);

		if (mcrypt_generic_init($td, $k, $iv) !== 0)
			return false;

		$msg = mcrypt_generic($td, $msg);
		$msg = $iv . $msg;
		$mac = $this->pbkdf2($msg, $k, 1000, 32);
		$msg .= $mac;

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		if ($base64) {
			$msg = base64_encode($msg);
		}

		return $msg;
	}

	private function decrypt($msg, $k, $base64 = false) {
		if ($base64) {
			$msg = base64_decode($msg);
		}

		if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', '')) {
			return false;
		}

		$iv = substr($msg, 0, 32);
		$mo = strlen($msg) - 32;
		$em = substr($msg, $mo);
		$msg = substr($msg, 32, strlen($msg) - 64);
		$mac = $this->pbkdf2($iv . $msg, $k, 1000, 32);

		if ($em !== $mac) {
			return false;
		}

		if (mcrypt_generic_init($td, $k, $iv) !== 0) {
			return false;
		}

		$msg = mdecrypt_generic($td, $msg);
		$msg = unserialize($msg);

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $msg;
	}

	private function pbkdf2($p, $s, $c, $kl, $a = 'sha256') {
		$hl = strlen(hash($a, null, true));
		$kb = ceil($kl / $hl);
		$dk = '';

		for ($block = 1; $block <= $kb; $block++) {

			$ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

			for ($i = 1; $i < $c; $i++)
				$ib ^= ($b = hash_hmac($a, $b, $p, true));

			$dk .= $ib;
		}

		return substr($dk, 0, $kl);
	}

	public function getServer() {
		return $this->server;
	}

	public function putStockUpdateBulk($caseIdArray, $endInactive = false){
		$this->load->library('log');
		$logger = new Log('amazon_stocks.log');
		$logger->write('Updating stock using putStockUpdateBulk()');
		$quantityData = array();
		foreach($caseIdArray as $caseId) {
			$amazonRows = $this->getLinkedSkus($caseId);
			foreach($amazonRows as $amazonRow) {
				$caseRow = $this->db->query("SELECT quantity, status FROM `" . DB_PREFIX . "case`
					WHERE `case_id` = '" . (int)$caseId . "'")->row;

				if(!empty($caseRow)) {
					if($endInactive && $caseRow['status'] == '0') {
						$quantityData[$amazonRow['amazon_sku']] = 0;
					} else {
						$quantityData[$amazonRow['amazon_sku']] = $caseRow['quantity'];
					}
				}
			}
		}
		if(!empty($quantityData)) {
			$logger->write('Quantity data to be sent:' . print_r($quantityData, true));
			$response = $this->updateQuantities($quantityData);
			$logger->write('Submit to API. Response: ' . print_r($response, true));
		} else {
			$logger->write('No quantity data need to be posted.');
		}
	}

	public function getLinkedSkus($caseId, $var='') {
		return $this->db->query("SELECT `amazon_sku` FROM `" . DB_PREFIX . "amazon_case_link` WHERE `case_id` = '" . (int)$caseId . "' AND `var` = '" . $this->db->escape($var) . "'")->rows;
	}

	public function getOrderdCases($orderId) {
		return $this->db->query("SELECT `op`.`case_id`, `p`.`quantity` as `quantity_left`
			FROM `" . DB_PREFIX . "order_case` as `op`
			LEFT JOIN `" . DB_PREFIX . "case` as `p`
			ON `p`.`case_id` = `op`.`case_id`
			WHERE `op`.`order_id` = '" . (int)$orderId . "'
			")->rows;
	}

	public function osCases($order_id){
		$order_case_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_case` WHERE `order_id` = '" . (int)$order_id . "'");

		$passArray = array();
		foreach ($order_case_query->rows as $order_case) {
			$case_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "case` WHERE `case_id` = '".(int)$order_case['case_id']."' LIMIT 1");

			if (!empty($case_query->row)) {
				if (isset($case_query->row['has_option']) && ($case_query->row['has_option'] == 1)) {
					$pOption_query = $this->db->query("
						SELECT `oo`.`case_option_value_id`
						FROM `" . DB_PREFIX . "order_option` `oo`
							LEFT JOIN `" . DB_PREFIX . "case_option_value` `pov` ON (`pov`.`case_option_value_id` = `oo`.`case_option_value_id`)
							LEFT JOIN `" . DB_PREFIX . "option` `o` ON (`o`.`option_id` = `pov`.`option_id`)
						WHERE `oo`.`order_case_id` = '" . (int)$order_case['order_case_id'] . "'
						AND `oo`.`order_id` = '" . (int)$order_id . "'
						AND ((`o`.`type` = 'radio') OR (`o`.`type` = 'select') OR (`o`.`type` = 'image'))
						ORDER BY `oo`.`order_option_id`
						ASC");

					if ($pOption_query->num_rows != 0) {
						$pOptions = array();
						foreach ($pOption_query->rows as $pOptionRow) {
							$pOptions[] = $pOptionRow['case_option_value_id'];
						}

						$var = implode(':', $pOptions);
						$qtyLeftRow = $this->db->query("SELECT `stock` FROM `" . DB_PREFIX . "case_option_relation` WHERE `case_id` = '" . (int)$order_case['case_id'] . "' AND `var` = '" . $this->db->escape($var) . "'")->row;

						if(empty($qtyLeftRow)) {
							$qtyLeftRow['stock'] = 0;
						}

						$passArray[] = array('pid' => $order_case['case_id'], 'qty_left' => $qtyLeftRow['stock'], 'var' => $var);
					}
				} else {
					$passArray[] = array('pid' => $order_case['case_id'], 'qty_left' => $case_query->row['quantity'], 'var' => '');
				}
			}
		}

		return $passArray;
	}

	public function validate(){
		if($this->config->get('amazon_status') != 0 &&
			$this->config->get('openbay_amazon_token') != '' &&
			$this->config->get('openbay_amazon_enc_string1') != '' &&
			$this->config->get('openbay_amazon_enc_string2') != ''){
			return true;
		}else{
			return false;
		}
	}

	public function deleteCase($case_id){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "amazon_case_link` WHERE `case_id` = '" . $this->db->escape($case_id) . "'");
	}

	public function deleteOrder($order_id){
		/**
		 * @todo
		 */
	}

	public function getOrder($orderId) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazon_order` WHERE `order_id` = '".(int)$orderId."' LIMIT 1");

		if($qry->num_rows > 0){
			return $qry->row;
		}else{
			return false;
		}
	}

	public function getCarriers() {
		return array(
			"Blue Package",
			"Canada Post",
			"City Link",
			"DHL",
			"DHL Global Mail",
			"Fastway",
			"FedEx",
			"FedEx SmartPost",
			"GLS",
			"GO!",
			"Hermes Logistik Gruppe",
			"Newgistics",
			"NipponExpress",
			"OSM",
			"OnTrac",
			"Parcelforce",
			"Royal Mail",
			"SagawaExpress",
			"Streamlite",
			"TNT",
			"Target",
			"UPS",
			"UPS Mail Innovations",
			"USPS",
			"YamatoTransport",
		);
	}

	public function parseCategoryTemplate($xml) {
		$simplexml = null;

		libxml_use_internal_errors(true);
		if(($simplexml = simplexml_load_string($xml)) == false) {
			return false;
		}

		$category = (string)$simplexml->filename;

		$tabs = array();
		foreach($simplexml->tabs->tab as $tab) {
			$attributes = $tab->attributes();
			$tabs[] = array(
				'id' => (string)$attributes['id'],
				'name' => (string)$tab->name,
			);
		}

		$fields = array();
		$fieldTypes = array('required', 'desired', 'optional');
		foreach ($fieldTypes as $type) {
			foreach ($simplexml->fields->$type->field as $field) {
				$attributes = $field->attributes();
				$fields[] = array(
					'name' => (string)$attributes['name'],
					'title' => (string)$field->title,
					'definition' => (string)$field->definition,
					'accepted' => (array)$field->accepted,
					'type' => (string)$type,
					'child' => false,
					'order' => isset($attributes['order']) ? (string)$attributes['order'] : '',
					'tab' => (string)$attributes['tab'],
				);
			}
			foreach ($simplexml->fields->$type->childfield as $field) {
				$attributes = $field->attributes();
				$fields[] = array(
					'name' => (string)$attributes['name'],
					'title' => (string)$field->title,
					'definition' => (string)$field->definition,
					'accepted' => (array)$field->accepted,
					'type' => (string)$type,
					'child' => true,
					'parent' => (array)$field->parent,
					'order' => isset($attributes['order']) ? (string)$attributes['order'] : '',
					'tab' => (string)$attributes['tab'],
				);
			}
		}

		foreach($fields as $index => $field) {
			$fields[$index]['unordered_index'] = $index;
		}

		usort($fields, array('Amazon','compareFields'));

		return array(
			'category' => $category,
			'fields' => $fields,
			'tabs' => $tabs,
		);
	}

	private static function compareFields($field1, $field2) {
		if($field1['order'] == $field2['order']) {
			return ($field1['unordered_index'] < $field2['unordered_index']) ? -1 : 1;
		} else if(!empty($field1['order']) && empty($field2['order'])) {
			return -1;
		} else if(!empty($field2['order']) && empty($field1['order'])) {
			return 1;
		} else {
			return ($field1['order'] < $field2['order']) ? -1 : 1;
		}
	}
}
?>