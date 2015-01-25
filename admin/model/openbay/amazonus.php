<?php
class ModelOpenbayAmazonus extends Model {
	public function scheduleOrders($data) {
		$log = new Log('amazonus.log');

		$requestXml = '<Request>
  <ResponseURL>' . HTTPS_CATALOG . 'index.php?route=amazonus/order' . '</ResponseURL>
  <MarketplaceIDs>';

		foreach ($data['openbay_amazonus_orders_marketplace_ids'] as $marketplaceId) {
			$requestXml .= '    <MarketplaceID>' . $marketplaceId . '</MarketplaceID>';
		}

		$requestXml .= '
  </MarketplaceIDs>
</Request>';

		$response = $this->openbay->amazonus->callWithResponse('order/scheduleOrders', $requestXml, false);

		libxml_use_internal_errors(true);
		$responseXml = simplexml_load_string($response);
		libxml_use_internal_errors(false);

		if ($responseXml && $responseXml->Status == '0') {
			$log->write('Scheduling orders call was successful');
			return true;
		}

		$log->write('Failed to schedule orders. Response: ' . $response);

		return false;
	}

	public function saveCase($case_id, $dataArray) {
		if(isset($dataArray['fields']['item-price'])) {
			$price = $dataArray['fields']['item-price'];
		} else if(isset($dataArray['fields']['price'])) {
			$price = $dataArray['fields']['price'];
		} else if(isset($dataArray['fields']['StandardPrice'])) {
			$price = $dataArray['fields']['StandardPrice'];
		}   else {
			$price = 0;
		}

		$category = (isset($dataArray['category'])) ? $dataArray['category'] : "";
		$sku = (isset($dataArray['fields']['sku'])) ? $dataArray['fields']['sku'] : "";
		if(isset($dataArray['fields']['sku'])) {
			$sku = $dataArray['fields']['sku'];
		} else if(isset($dataArray['fields']['SKU'])) {
			$sku = $dataArray['fields']['SKU'];
		}

		$var = isset($dataArray['optionVar']) ? $dataArray['optionVar'] : '';

		$marketplaces = isset($dataArray['marketplace_ids']) ? serialize($dataArray['marketplace_ids']) : serialize(array());

		$dataEncoded = json_encode(array('fields' => $dataArray['fields']));

		$this->db->query("
			REPLACE INTO `" . DB_PREFIX . "amazonus_case`
			SET `case_id` = '" . (int)$case_id . "',
				`sku` = '" . $this->db->escape($sku) . "',
				`category` = '" . $this->db->escape($category) . "',
				`data` = '" . $this->db->escape($dataEncoded) . "',
				`status` = 'saved',
				`insertion_id` = '',
				`price` = '" . $price . "',
				`var` = '" . $this->db->escape($var) . "',
				`marketplaces` = '" . $this->db->escape($marketplaces) . "'");
	}

	public function deleteSaved($case_id, $var = '') {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND `var` = '" . $this->db->escape($var) . "'");
	}

	public function getSavedCases() {
		return $this->db->query("
			SELECT `ap`.`status`, `ap`.`case_id`, `ap`.`sku` as `amazonus_sku`, `pd`.`name` as `case_name`, `p`.`model` as `case_model`, `p`.`sku` as `case_sku`, `ap`.`var` as `var`
			FROM `" . DB_PREFIX . "amazonus_case` as `ap`
			LEFT JOIN `" . DB_PREFIX . "case_description` as `pd`
			ON `ap`.`case_id` = `pd`.`case_id`
			LEFT JOIN `" . DB_PREFIX . "case` as `p`
			ON `ap`.`case_id` = `p`.`case_id`
			WHERE `ap`.`status` = 'saved'
			AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'")->rows;
	}

	public function getSavedCasesData() {
		return $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `status` = 'saved' AND `version` = 2")->rows;
	}

	public function getCase($case_id, $var = '') {
		return $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND `var` = '" . $this->db->escape($var) . "' AND `version` = 2")->row;
	}

	public function getCaseCategory($case_id, $var = '') {
		$row = $this->db->query("
			SELECT `category` FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND `var` = '" . $this->db->escape($var) . "' AND `version` = 2")->row;
		if(isset($row['category'])) {
			return $row['category'];
		} else {
			return "";
		}
	}

	public function setCaseUploaded($case_id, $insertion_id, $var = '') {
		$this->db->query(
			"UPDATE `" . DB_PREFIX . "amazonus_case`
			SET `status` = 'uploaded', `insertion_id` = '" . $this->db->escape($insertion_id) . "'
			WHERE `case_id` = '" . (int)$case_id . "' AND `var` = '" . $this->db->escape($var) . "' AND `version` = 2");
	}

	public function resetUploaded($insertion_id) {
		$this->db->query(
			"UPDATE `" . DB_PREFIX . "amazonus_case`
			SET `status` = 'saved', `insertion_id` = ''
			WHERE `insertion_id` = '" . $this->db->escape($insertion_id) . "' AND `version` = 2");
	}

	public function getCaseStatus($case_id) {

		$rowsUploaded = $this->db->query("
			SELECT COUNT(*) count
			FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND status = 'uploaded'")->row;
		$rowsUploaded = $rowsUploaded['count'];

		$rowsOk = $this->db->query("
			SELECT COUNT(*) count
			FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND status = 'ok'")->row;
		$rowsOk = $rowsOk['count'];

		$rowsError = $this->db->query("
			SELECT COUNT(*) count
			FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND status = 'error'")->row;
		$rowsError = $rowsError['count'];

		$rowsSaved = $this->db->query("
			SELECT COUNT(*) count
			FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND status = 'saved'")->row;
		$rowsSaved = $rowsSaved['count'];

		$rowsTotal = $rowsUploaded + $rowsOk + $rowsError + $rowsSaved;

		$links = $this->db->query("
			SELECT COUNT(*) as count
			FROM `" . DB_PREFIX . "amazonus_case_link`
			WHERE `case_id` = '" . (int)$case_id . "'")->row;
		$links = $links['count'];


		if($rowsTotal === 0 && $links > 0) {
			return 'linked';
		} else if($rowsTotal == 0) {
			return false;
		}

		if($rowsUploaded > 0) {
			return 'processing';
		}

		if($rowsUploaded == 0 && $rowsOk > 0 && $rowsError == 0) {
			return 'ok';
		}

		if($rowsSaved > 0) {
			return 'saved';
		}

		if($rowsUploaded == 0 && $rowsError > 0 && $rowsOk == 0) {
			$quick = $this->db->query("
				SELECT *
				FROM `" . DB_PREFIX . "amazonus_case`
				WHERE `case_id` = " . (int)$case_id . " AND `version` = 3")->row;

			if($quick) {
				return 'error_quick';
			} else {
				return 'error_advanced';
			}
		} else {
			return 'error_few';
		}

		return false;
	}

	public function getCaseErrors($case_id, $version = 2) {
		if($version == 3) {
			$messageRow = $this->db->query("
			SELECT `messages` FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND `version` = 3")->row;

			return json_decode($messageRow['messages']);
		}


		$result = array();

		$insertionRows = $this->db->query("
			SELECT `sku`, `insertion_id` FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND `version` = 2")->rows;

		if(!empty($insertionRows)) {
			foreach($insertionRows as $insertionRow) {
				$errorRows = $this->db->query("
					SELECT * FROM `" . DB_PREFIX . "amazonus_case_error`
					WHERE `sku` = '" . $this->db->escape($insertionRow['sku']) . "' AND `insertion_id` = '" . $this->db->escape($insertionRow['insertion_id']) . "'")->rows;
				foreach($errorRows as $errorRow) {
					$result[] = $errorRow;
				}
			}
		}
		return $result;
	}

	public function getCasesWithErrors() {
		return $this->db->query("
			SELECT `case_id`, `sku` FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `status` = 'error' AND `version` = 2")->rows;
	}

	public function deleteCase($case_id) {
		$this->db->query(
			"DELETE FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "'");
	}

	public function linkCase($amazonus_sku, $case_id, $var = '') {
		$count = $this->db->query("SELECT COUNT(*) as 'count' FROM `" . DB_PREFIX . "amazonus_case_link` WHERE `case_id` = '" . (int)$case_id . "' AND `amazonus_sku` = '" . $this->db->escape($amazonus_sku) . "' AND `var` = '" . $this->db->escape($var) . "' LIMIT 1")->row;
		if($count['count'] == 0) {
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "amazonus_case_link`
				SET `case_id` = '" . (int)$case_id . "', `amazonus_sku` = '" . $this->db->escape($amazonus_sku) . "', `var` = '" . $this->db->escape($var) . "'");
		}
	}

	public function removeCaseLink($amazonus_sku) {
		$this->db->query(
			"DELETE FROM `" . DB_PREFIX . "amazonus_case_link`
			WHERE `amazonus_sku` = '" . $this->db->escape($amazonus_sku) . "'");
	}

	public function removeAdvancedErrors($case_id) {
		$case_rows = $this->db->query("
			SELECT `insertion_id` FROM `" . DB_PREFIX . "amazonus_case`
			WHERE `case_id` = '" . (int)$case_id . "' AND `version` = 2")->rows;

		foreach ($case_rows as $case) {
			$this->db->query(
				"DELETE FROM `" . DB_PREFIX . "amazonus_case_error`
				WHERE `insertion_id` = '" . $this->db->escape($case['insertion_id']) . "'");
		}

		$this->db->query(
			"UPDATE `" . DB_PREFIX . "amazonus_case`
			SET `status` = 'saved', `insertion_id` = ''
			WHERE `case_id` = '" . (int)$case_id . "' AND `status` = 'error' AND `version` = 2");
	}

	public function getCaseLinks($case_id = 'all') {
		$query = "SELECT `apl`.`amazonus_sku`, `apl`.`case_id`, `pd`.`name` as `case_name`, `p`.`model`, `p`.`sku`, `apl`.`var`, '' as `combi`
			FROM `" . DB_PREFIX . "amazonus_case_link` as `apl`
			LEFT JOIN `" . DB_PREFIX . "case_description` as `pd`
			ON `apl`.`case_id` = `pd`.`case_id`
			LEFT JOIN `" . DB_PREFIX . "case` as `p`
			ON `apl`.`case_id` = `p`.`case_id`";
		if($case_id != 'all') {
			$query .= " WHERE `apl`.`case_id` = '" . (int)$case_id . "' AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";
		}else{
			$query .= "WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";
		}

		$rows = $this->db->query($query)->rows;

		$this->load->library('amazonus');
		if ($this->openbay->addonLoad('openstock')) {
			$this->load->model('openstock/openstock');
			$this->load->model('tool/image');
			$rowsWithVar = array();
			foreach($rows as $row) {
				$stockOpts = $this->model_openstock_openstock->getCaseOptionStocks($row['case_id']);
				foreach($stockOpts as $opt) {
					if($opt['var'] == $row['var']) {
						$row['combi'] = $opt['combi'];
						$row['sku'] = $opt['sku'];
						break;
					}
				}
				$rowsWithVar[] = $row;
			}
			return $rowsWithVar;
		} else {
			return $rows;
		}
	}

	public function getUnlinkedCases() {
		$this->load->library('amazonus');
		if ($this->openbay->addonLoad('openstock')) {

			$rows = $this->db->query("
				SELECT `p`.`case_id`, `p`.`model`, `p`.`sku`, `pd`.`name` as `case_name`, '' as `var`, '' as `combi`, `p`.`has_option`
				FROM `" . DB_PREFIX . "case` as `p`
				LEFT JOIN `" . DB_PREFIX . "case_description` as `pd`
				ON `p`.`case_id` = `pd`.`case_id`
				AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'")->rows;

			$result = array();
			$this->load->model('openstock/openstock');
			$this->load->model('tool/image');
			foreach($rows as $row) {
				if ($row['has_option'] == 1) {
					$stockOpts = $this->model_openstock_openstock->getCaseOptionStocks($row['case_id']);
					foreach($stockOpts as $opt) {
						if($this->caseLinkExists($row['case_id'], $opt['var'])) {
							continue;
						}
						$row['var'] = $opt['var'];
						$row['combi'] = $opt['combi'];
						$row['sku'] = $opt['sku'];
						$result[] = $row;
					}
				} else {
					if(!$this->caseLinkExists($row['case_id'], $row['var'])) {
						$result[] = $row;
					}
				}
			}
		} else {
			$result = $this->db->query("
				SELECT `p`.`case_id`, `p`.`model`, `p`.`sku`, `pd`.`name` as `case_name`, '' as `var`, '' as `combi`
				FROM `" . DB_PREFIX . "case` as `p`
				LEFT JOIN `" . DB_PREFIX . "case_description` as `pd`
				ON `p`.`case_id` = `pd`.`case_id`
				LEFT JOIN `" . DB_PREFIX . "amazonus_case_link` as `apl`
				ON `apl`.`case_id` = `p`.`case_id`
				WHERE `apl`.`amazonus_sku` IS NULL
				AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'")->rows;
		}

		return $result;
	}

	private function caseLinkExists($case_id, $var) {
		$link = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazonus_case_link` WHERE `case_id` = " . (int)$case_id . " AND var = '" . $this->db->escape($var) . "'")->row;

		if(empty($link)) {
			return false;
		} else {
			return true;
		}
	}

	public function getOrderStatusString($orderId) {
		$row = $this->db->query("
			SELECT `s`.`key`
			FROM `" . DB_PREFIX . "order` `o`
			JOIN `" . DB_PREFIX . "setting` `s` ON `o`.`order_id` = " . (int)$orderId . " AND `s`.`value` = `o`.`order_status_id`
			WHERE `s`.`key` = 'openbay_amazonus_order_status_shipped' OR `s`.`key` = 'openbay_amazonus_order_status_canceled'
			LIMIT 1")->row;

		if (!isset($row['key']) || empty($row['key'])) {
			return null;
		}

		$key = $row['key'];

		switch ($key) {
			case 'openbay_amazonus_order_status_shipped':
				$orderStatus = 'shipped';
				break;
			case 'openbay_amazonus_order_status_canceled':
				$orderStatus = 'canceled';
				break;

			default:
				$orderStatus = null;
				break;
		}

		return $orderStatus;
	}

	public function updateAmazonusOrderTracking($orderId, $courierId, $courierFromList, $trackingNo) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazonus_order`
			SET `courier_id` = '" . $courierId . "',
				`courier_other` = " . (int)!$courierFromList . ",
				`tracking_no` = '" . $trackingNo . "'
			WHERE `order_id` = " . (int)$orderId . "");
	}

	public function getAmazonusOrderId($orderId) {
		$row = $this->db->query("
			SELECT `amazonus_order_id`
			FROM `" . DB_PREFIX . "amazonus_order`
			WHERE `order_id` = " . (int)$orderId . "
			LIMIT 1")->row;

		if (isset($row['amazonus_order_id']) && !empty($row['amazonus_order_id'])) {
			return $row['amazonus_order_id'];
		}

		return null;
	}

	public function getAmazonusOrderedCases($orderId) {
		return $this->db->query("
			SELECT `aop`.`amazonus_order_item_id`, `op`.`quantity`
			FROM `" . DB_PREFIX . "amazonus_order_case` `aop`
			JOIN `" . DB_PREFIX . "order_case` `op` ON `op`.`order_case_id` = `aop`.`order_case_id`
				AND `op`.`order_id` = " . (int)$orderId)->rows;
	}

	public function getCaseQuantity($case_id, $var = '') {
		$this->load->library('amazonus');

		$result = null;

		if($var !== '' && $this->openbay->addonLoad('openstock')) {
			$this->load->model('tool/image');
			$this->load->model('openstock/openstock');
			$optionStocks = $this->model_openstock_openstock->getCaseOptionStocks($case_id);

			$option = null;
			foreach ($optionStocks as $optionIterator) {
				if($optionIterator['var'] === $var) {
					$option = $optionIterator;
					break;
				}
			}

			if($option != null) {
				$result = $option['stock'];
			}
		} else {
			$this->load->model('catalog/case');
			$case_info = $this->model_catalog_case->getCase($case_id);

			if (isset($case_info['quantity'])) {
				$result = $case_info['quantity'];
			}
		}
		return $result;
	}

	public function getCaseSearchTotal($data = array()) {
		$sql = "
			SELECT COUNT(*) AS case_total
			FROM " . DB_PREFIX . "`case` p
			LEFT JOIN " . DB_PREFIX . "amazonus_case_search aps ON p.case_id = aps.case_id
			LEFT JOIN " . DB_PREFIX . "amazonus_case_link apl ON p.case_id = apl.case_id
			LEFT JOIN " . DB_PREFIX . "amazonus_case ap ON p.case_id = ap.case_id
			WHERE apl.case_id IS NULL AND ap.case_id IS NULL ";

		if (!empty($data['status'])) {
			$sql .= " AND aps.status = '" . $this->db->escape($data['status']) . "'";
		}

		return $this->db->query($sql)->row['case_total'];
	}

	public function getCaseSearch($data = array()) {
		$sql = "
			SELECT p.case_id, aps.status, aps.data, aps.matches
			FROM " . DB_PREFIX . "`case` p
			LEFT JOIN " . DB_PREFIX . "amazonus_case_search aps ON p.case_id = aps.case_id
			LEFT JOIN " . DB_PREFIX . "amazonus_case_link apl ON p.case_id = apl.case_id
			LEFT JOIN " . DB_PREFIX . "amazonus_case ap ON p.case_id = ap.case_id
			WHERE apl.case_id IS NULL AND ap.case_id IS NULL ";

		if (!empty($data['status'])) {
			$sql .= " AND aps.status = '" . $this->db->escape($data['status']) . "'";
		}

		$sql .= " LIMIT " . (int)$data['start'] . ", " . (int)$data['limit'];

		$results = array();

		$rows = $this->db->query($sql)->rows;

		foreach ($rows as $row) {
			$results[] = array(
				'case_id' => $row['case_id'],
				'status' => $row['status'],
				'matches' => $row['matches'],
				'data' => json_decode($row['data'], 1),
			);
		}

		return $results;
	}

	public function updateAmazonSkusQuantities($skus) {
		$skuArray = array();

		foreach ($skus as $sku) {
			$skuArray[] = "'" . $this->db->escape($sku) . "'";
		}

		if ($this->openbay->addonLoad('openstock')) {
			$rows = $this->db->query("
				SELECT apl.amazon_sku, IF(por.case_id IS NULL, p.quantity, por.stock) AS 'quantity'
				FROM " . DB_PREFIX . "amazonus_case_link apl
				JOIN " . DB_PREFIX . "`case` p ON apl.case_id = p.case_id
				LEFT JOIN " . DB_PREFIX . "case_option_relation por ON apl.case_id = por.case_id AND apl.var = por.var
				WHERE apl.amazon_sku IN (" . implode(',', $skuArray) . ")
			")->rows;
		} else {
			$rows = $this->db->query("
				SELECT apl.amazon_sku, p.quantity
				FROM " . DB_PREFIX . "amazonus_case_link apl
				JOIN " . DB_PREFIX . "`case` p ON apl.case_id = p.case_id
				WHERE apl.amazon_sku IN (" . implode(',', $skuArray) . ")
			")->rows;
		}

		$return = array();

		foreach ($rows as $row) {
			$return[$row['amazon_sku']] = $row['quantity'];
		}

		$this->openbay->amazonus->updateQuantities($return);
	}

	public function getTotalUnlinkedItemsFromReport() {
		if ($this->openbay->addonLoad('openstock')) {
			$result = $this->db->query("
				SELECT alr.sku AS 'amazon_sku', alr.quantity AS 'amazon_quantity', alr.asin, alr.price AS 'amazon_price', oc_sku.case_id, pd.name, oc_sku.sku, oc_sku.var, oc_sku.quantity,
				  (
					SELECT GROUP_CONCAT(ovd.name ORDER BY o.sort_order SEPARATOR ' > ')
					FROM " . DB_PREFIX . "case_option_value pov
					JOIN " . DB_PREFIX . "option_value_description ovd ON ovd.option_value_id = pov.option_value_id AND ovd.language_id = " . (int)$this->config->get('config_language_id') . "
					JOIN `" . DB_PREFIX . "option` o ON o.option_id = pov.option_id
					WHERE oc_sku.var LIKE CONCAT('%:', pov.case_option_value_id ,':%') OR oc_sku.var LIKE CONCAT(pov.case_option_value_id ,':%')
					  OR oc_sku.var LIKE CONCAT('%:', pov.case_option_value_id) OR oc_sku.var LIKE pov.case_option_value_id
				  ) AS 'combination'
				FROM " . DB_PREFIX . "amazonus_listing_report alr
				LEFT JOIN (
				  SELECT p.case_id, IF(por.case_id IS NULL, p.sku, por.sku) AS 'sku', IF(por.case_id IS NULL, NULL, por.var) AS 'var', IF(por.case_id IS NULL, p.quantity, por.stock) AS 'quantity'
				  FROM " . DB_PREFIX . "`case` p
				  LEFT JOIN " . DB_PREFIX . "case_option_relation por USING(case_id)
				) AS oc_sku ON alr.sku = oc_sku.sku
				LEFT JOIN " . DB_PREFIX . "amazonus_case_link apl ON (oc_sku.var IS NULL AND oc_sku.case_id = apl.case_id) OR (oc_sku.var IS NOT NULL AND oc_sku.case_id = apl.case_id AND oc_sku.var = apl.var)
				LEFT JOIN " . DB_PREFIX . "case_description pd ON oc_sku.case_id = pd.case_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . "
				WHERE apl.case_id IS NULL
			");
		} else {
			$result = $this->db->query("
				SELECT alr.sku AS 'amazon_sku', alr.quantity AS 'amazon_quantity', alr.asin, alr.price AS 'amazon_price', oc_sku.case_id, pd.name, oc_sku.sku, oc_sku.var, oc_sku.quantity, '' AS combination
				FROM " . DB_PREFIX . "amazonus_listing_report alr
				LEFT JOIN (
					SELECT p.case_id, p.sku, NULL AS 'var', p.quantity
					FROM " . DB_PREFIX . "`case` p
				) AS oc_sku ON alr.sku = oc_sku.sku
				LEFT JOIN " . DB_PREFIX . "amazonus_case_link apl ON (oc_sku.var IS NULL AND oc_sku.case_id = apl.case_id) OR (oc_sku.var IS NOT NULL AND oc_sku.case_id = apl.case_id AND oc_sku.var = apl.var)
				LEFT JOIN " . DB_PREFIX . "case_description pd ON oc_sku.case_id = pd.case_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . "
				WHERE apl.case_id IS NULL
				ORDER BY alr.sku
			");
		}

		return (int)$result->num_rows;
	}

	public function getUnlinkedItemsFromReport($limit = 100, $page = 1) {
		$start = $limit * ($page - 1);

		$cases = array();

		if ($this->openbay->addonLoad('openstock')) {
			$rows = $this->db->query("
				SELECT alr.sku AS 'amazon_sku', alr.quantity AS 'amazon_quantity', alr.asin, alr.price AS 'amazon_price', oc_sku.case_id, pd.name, oc_sku.sku, oc_sku.var, oc_sku.quantity,
				  (
					SELECT GROUP_CONCAT(ovd.name ORDER BY o.sort_order SEPARATOR ' > ')
					FROM " . DB_PREFIX . "case_option_value pov
					JOIN " . DB_PREFIX . "option_value_description ovd ON ovd.option_value_id = pov.option_value_id AND ovd.language_id = " . (int)$this->config->get('config_language_id') . "
					JOIN `" . DB_PREFIX . "option` o ON o.option_id = pov.option_id
					WHERE oc_sku.var LIKE CONCAT('%:', pov.case_option_value_id ,':%') OR oc_sku.var LIKE CONCAT(pov.case_option_value_id ,':%')
					  OR oc_sku.var LIKE CONCAT('%:', pov.case_option_value_id) OR oc_sku.var LIKE pov.case_option_value_id
				  ) AS 'combination'
				FROM " . DB_PREFIX . "amazonus_listing_report alr
				LEFT JOIN (
				  SELECT p.case_id, IF(por.case_id IS NULL, p.sku, por.sku) AS 'sku', IF(por.case_id IS NULL, NULL, por.var) AS 'var', IF(por.case_id IS NULL, p.quantity, por.stock) AS 'quantity'
				  FROM " . DB_PREFIX . "`case` p
				  LEFT JOIN " . DB_PREFIX . "case_option_relation por USING(case_id)
				) AS oc_sku ON alr.sku = oc_sku.sku
				LEFT JOIN " . DB_PREFIX . "amazonus_case_link apl ON (oc_sku.var IS NULL AND oc_sku.case_id = apl.case_id) OR (oc_sku.var IS NOT NULL AND oc_sku.case_id = apl.case_id AND oc_sku.var = apl.var)
				LEFT JOIN " . DB_PREFIX . "case_description pd ON oc_sku.case_id = pd.case_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . "
				WHERE apl.case_id IS NULL
				ORDER BY alr.sku
				LIMIT " . (int)$start . "," . (int)$limit)->rows;
		} else {
			$rows = $this->db->query("
				SELECT alr.sku AS 'amazon_sku', alr.quantity AS 'amazon_quantity', alr.asin, alr.price AS 'amazon_price', oc_sku.case_id, pd.name, oc_sku.sku, oc_sku.var, oc_sku.quantity, '' AS combination
				FROM " . DB_PREFIX . "amazonus_listing_report alr
				LEFT JOIN (
					SELECT p.case_id, p.sku, NULL AS 'var', p.quantity
					FROM " . DB_PREFIX . "`case` p
				) AS oc_sku ON alr.sku = oc_sku.sku
				LEFT JOIN " . DB_PREFIX . "amazonus_case_link apl ON (oc_sku.var IS NULL AND oc_sku.case_id = apl.case_id) OR (oc_sku.var IS NOT NULL AND oc_sku.case_id = apl.case_id AND oc_sku.var = apl.var)
				LEFT JOIN " . DB_PREFIX . "case_description pd ON oc_sku.case_id = pd.case_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . "
				WHERE apl.case_id IS NULL
				ORDER BY alr.sku
				LIMIT " . (int)$start . "," . (int)$limit)->rows;
		}

		foreach ($rows as $row) {
			$cases[] = array(
				'case_id' => $row['case_id'],
				'name' => $row['name'],
				'sku' => $row['sku'],
				'var' => $row['var'],
				'quantity' => $row['quantity'],
				'amazon_sku' => $row['amazon_sku'],
				'amazon_quantity' => $row['amazon_quantity'],
				'amazon_price' => number_format($row['amazon_price'], 2, '.', ''),
				'asin' => $row['asin'],
				'combination' => $row['combination'],
			);
		}

		return $cases;
	}

	public function install(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_order` (
			  `order_id` int(11) NOT NULL ,
			  `amazonus_order_id` char(19) NOT NULL ,
			  `courier_id` varchar(255) NOT NULL ,
			  `courier_other` tinyint(1) NOT NULL,
			  `tracking_no` varchar(255) NOT NULL ,
			  PRIMARY KEY (`order_id`, `amazonus_order_id`)
		) DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_order_case` (
				`order_case_id` int(11) NOT NULL ,
				`amazonus_order_item_id` varchar(255) NOT NULL,
				PRIMARY KEY(`order_case_id`, `amazonus_order_item_id`)
		);");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_case_unshipped` (
				`order_id` int(11) NOT NULL,
				`case_id` int(11) NOT NULL,
				`quantity` int(11) NOT NULL DEFAULT '0',
				PRIMARY KEY (`order_id`,`case_id`)
			) DEFAULT COLLATE=utf8_general_ci;;");

		$this->db->query("
		CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_case` (
		  `version` int(11) NOT NULL DEFAULT 2,
		  `case_id`  int(11) NOT NULL ,
		  `category`  varchar(255) NOT NULL ,
		  `sku`  varchar(255) NOT NULL ,
		  `insertion_id` varchar(255) NOT NULL ,
		  `data`  text NOT NULL ,
		  `status` enum('saved','uploaded','ok','error') NOT NULL ,
		  `price`  decimal(15,4) NOT NULL COMMENT 'Price on Amazonus' ,
		  `var` char(100) NOT NULL DEFAULT '',
		  `marketplaces` text NOT NULL ,
		  `messages` text NOT NULL,
		  PRIMARY KEY (`case_id`, `var`)
		);");

		$this->db->query("
		CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_case_error` (
		  `error_id` int(11) NOT NULL AUTO_INCREMENT,
		  `sku` varchar(255) NOT NULL ,
		  `insertion_id` varchar(255) NOT NULL ,
		  `error_code` int(11) NOT NULL ,
		  `message` text NOT NULL ,
		  PRIMARY KEY (`error_id`)
		);");

		$this->db->query("
		CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_case_link` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `amazonus_sku` varchar(255) NOT NULL,
		  `var` char(100) NOT NULL DEFAULT '',
		  `case_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
		CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazonus_case_search` (
			`case_id` int(11) NOT NULL,
			`status` enum('searching','finished') NOT NULL,
			`matches` int(11) DEFAULT NULL,
			`data` text,
			PRIMARY KEY (`case_id`)
		) DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS`" . DB_PREFIX . "amazonus_listing_report` (
				`sku` varchar(255) NOT NULL,
				`quantity` int(10) unsigned NOT NULL,
				`asin` varchar(255) NOT NULL,
				`price` decimal(10,4) NOT NULL,
				PRIMARY KEY (`sku`)
			) DEFAULT COLLATE=utf8_general_ci;
		");
	}

	public function deleteListingReports() {
		$this->db->query("
			DELETE FROM " . DB_PREFIX . "amazonus_listing_report
		");
	}

	public function uninstall(){
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_order`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_order_case`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_case2`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_case`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_case_link`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_case_unshipped`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_case_error`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_process`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_case_search`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazonus_listing_report`");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = 'openbay_amazonus'");
	}
}
?>