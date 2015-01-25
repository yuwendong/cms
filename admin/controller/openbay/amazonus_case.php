<?php
class ControllerOpenbayAmazonusCase extends Controller{
	public function index() {
		$this->load->language('catalog/case');
		$this->load->language('openbay/amazonus');

		$this->load->model('openbay/amazonus');
		$this->load->model('catalog/case');
		$this->load->model('tool/image');

		$this->load->library('amazonus');

		$this->data = array_merge($this->data, $this->load->language('openbay/amazonus_listing'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/openbay.js');
		$this->document->setTitle($this->language->get('lang_title'));

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_desc'])) {
			$url .= '&filter_desc=' . $this->request->get['filter_desc'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => 'Cases',
			'href' => $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if(isset($this->request->get['case_id'])) {
			$case_id = $this->request->get['case_id'];
		} else {
			die('No case id');
		}

		if(isset($this->request->get['var'])) {
			$variation = $this->request->get['var'];
		} else {
			$variation = '';
		}
		$this->data['variation'] = $variation;
		$this->data['errors'] = array();
		/*
		 * Perform updates to database if form is posted
		 */
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$dataArray = $this->request->post;

			$this->model_openbay_amazonus->saveCase($case_id, $dataArray);

			if($dataArray['upload_after'] === 'true') {
				$uploadResult = $this->uploadSaved();
				if($uploadResult['status'] == 'ok') {
					$this->session->data['success'] = $this->language->get('uploaded_alert_text');
					$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				} else {
					$this->data['errors'][] = Array('message' => $uploadResult['error_message']);
				}
			} else {
				$this->session->data['success'] = $this->language->get('saved_localy_text');
				$this->redirect($this->url->link('openbay/amazonus_case', 'token=' . $this->session->data['token'] . '&case_id=' . $case_id . $url, 'SSL'));
			}
		}

		if(isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$savedListingData = $this->model_openbay_amazonus->getCase($case_id, $variation);
		if(empty($savedListingData)) {
			$listingSaved = false;
		} else {
			$listingSaved = true;
		}

		$errors = $this->model_openbay_amazonus->getCaseErrors($case_id);
		foreach($errors as $error) {
			$error['message'] =  'Error for SKU: "' . $error['sku'] . '" - ' . $this->formatUrlsInText($error['message']);
			$this->data['errors'][] = $error;
		}
		if(!empty($errors)) {
			$this->data['has_listing_errors'] = true;
		} else {
			$this->data['has_listing_errors'] = false;
		}

		$case_info = $this->model_catalog_case->getCase($case_id);
		$this->data['listing_name'] = $case_info['name'] . " : " . $case_info['model'];
		$this->data['listing_sku'] = $case_info['sku'];
		$this->data['listing_url'] = $this->url->link('catalog/case/update', 'token=' . $this->session->data['token'] . '&case_id=' . $case_id . $url, 'SSL');

		if($listingSaved) {
			$this->data['edit_case_category'] = $savedListingData['category'];
		} else {
			$this->data['edit_case_category'] = '';
		}

		/*
		 * Load available categories
		 */
		$this->data['amazonus_categories'] = array();

		$amazonus_templates = $this->openbay->amazonus->getCategoryTemplates();

		foreach($amazonus_templates as $template) {
			$template = (array)$template;
			$categoryData = array(
				'friendly_name' => $template['friendly_name'],
				'name' => $template['name'],
				'template' => $template['xml']
			);
			$this->data['amazonus_categories'][] = $categoryData;
		}


		if($listingSaved) {
			$this->data['template_parser_url'] = $this->url->link('openbay/amazonus_case/parseTemplateAjax&edit_id=' . $case_id, 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['template_parser_url'] = $this->url->link('openbay/amazonus_case/parseTemplateAjax&case_id=' . $case_id, 'token=' . $this->session->data['token'], 'SSL');
		}

		$this->data['url_remove_errors'] = $this->url->link('openbay/amazonus_case/removeErrors', 'token=' . $this->session->data['token'] . '&case_id=' . $case_id . $url, 'SSL');
		$this->data['cancel_url'] = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['saved_listings_url'] = $this->url->link('openbay/amazonus/savedListings', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['main_url'] = $this->url->link('openbay/amazonus_case', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if ($this->openbay->addonLoad('openstock')) {
			$this->load->model('openstock/openstock');
			$this->data['options'] = $this->model_openstock_openstock->getCaseOptionStocks($case_id);
		} else {
			$this->data['options'] = array();
		}

		$this->template = 'openbay/amazonus_listing_advanced.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function removeErrors() {
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_desc'])) {
			$url .= '&filter_desc=' . $this->request->get['filter_desc'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['case_id'])) {
			$case_id = $this->request->get['case_id'];
		} else {
			$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('openbay/amazonus');
		$this->model_openbay_amazonus->removeAdvancedErrors($case_id);
		$this->session->data['success'] = 'Errors removed';
		$this->redirect($this->url->link('openbay/amazonus_case', 'token=' . $this->session->data['token'] . '&case_id=' . $case_id . $url, 'SSL'));
	}

	public function uploadSavedAjax() {


		ob_start();
		$json = json_encode($this->uploadSaved());
		ob_clean();

		$this->response->setOutput($json);
	}

	private function uploadSaved() {
		$this->load->language('openbay/amazonus_listing');
		$this->load->library('amazonus');
		$this->load->model('openbay/amazonus');
		$logger = new Log('amazonus_case.log');

		$logger->write('Uploading process started.');

		$savedCases = $this->model_openbay_amazonus->getSavedCasesData();

		if(empty($savedCases)) {
			$logger->write('No saved listings found. Uploading canceled.');
			$result['status'] = 'error';
			$result['error_message'] = 'No saved listings. Nothing to upload. Aborting.';
			return $result;
		}

		foreach($savedCases as $savedCase) {
			$caseDataDecoded = (array)json_decode($savedCase['data']);

			$catalog = defined(HTTPS_CATALOG) ? HTTPS_CATALOG : HTTP_CATALOG;
			$response_data = array("response_url" => $catalog . 'index.php?route=amazonus/case/inbound');
			$category_data = array('category' => (string)$savedCase['category']);
			$fields_data = array('fields' => (array)$caseDataDecoded['fields']);

			$mpArray = array(); //Amazon US does not have marketplace selection
			$marketplaces_data = array('marketplaces' => $mpArray);

			$caseData = array_merge($category_data, $fields_data, $response_data, $marketplaces_data);
			$insertion_response = $this->openbay->amazonus->insertCase($caseData);

			$logger->write("Uploading case with data:" . print_r($caseData, true) . "
				Got response:" . print_r($insertion_response, true));

			if(!isset($insertion_response['status']) || $insertion_response['status'] == 'error') {
				$details = isset($insertion_response['info']) ? $insertion_response['info'] : 'Unknown';
				$result['error_message'] = sprintf($this->language->get('upload_failed'), $savedCase['sku'], $details);
				$result['status'] = 'error';
				break;
			}
			$logger->write('Case upload success');
			$this->model_openbay_amazonus->setCaseUploaded($savedCase['case_id'], $insertion_response['insertion_id'], $savedCase['var']);
		}

		if(!isset($result['status'])) {
			$result['status'] = 'ok';
			$logger->write('Uploading process completed successfully.');
		} else {
			$logger->write('Uploading process failed with message: ' . $result['error_message']);
		}
		return $result;
	}

	public function parseTemplateAjax() {


		$this->load->model('tool/image');
		$this->load->library('amazonus');
		$this->load->library('log');
		$log = new Log('amazonus_case.log');

		$result = array();

		if(isset($this->request->get['xml'])) {
			$request = array('template' => $this->request->get['xml'], 'version' => 2);
			$response = $this->openbay->amazonus->callWithResponse("casev2/GetTemplateXml", $request);
			if ($response) {
				$template = $this->openbay->amazonus->parseCategoryTemplate($response);
				if ($template) {
					$variation = isset($this->request->get['var']) ? $this->request->get['var'] : '';

					if (isset($this->request->get['case_id'])) {
						$template['fields'] = $this->fillDefaultValues($this->request->get['case_id'], $template['fields'], $variation);
					} elseif (isset($this->request->get['edit_id'])) {
						$template['fields'] = $this->fillSavedValues($this->request->get['edit_id'], $template['fields'], $variation);
					}

					foreach($template['fields'] as $key => $field) {
						if($field['accepted']['type'] == 'image') {
							$template['fields'][$key]['thumb'] = $this->model_tool_image->resize(str_replace(HTTPS_CATALOG . 'image/', '', $field['value']), 100, 100);
							if(empty($field['thumb'])) {
								$template['fields'][$key]['thumb'] = '';
							}
						}
					}

					$result = array(
						"category" => $template['category'],
						"fields" => $template['fields'],
						"tabs" => $template['tabs']
					);
				} else {
					$json_decoded = json_decode($response);
					if ($json_decoded) {
						$result = $json_decoded;
					} else {
						$result = array('status' => 'error');
						$log->write("admin/openbay/amazon_case/parseTemplateAjax failed to parse template response: " . $response);
					}
				}
			} else {
				$log->write("admin/openbay/amazonus_case/parseTemplateAjax failed calling casev2/GetTemplateXml with params: " . print_r($request, true));
			}
		}

		$this->response->setOutput(json_encode($result));

	}

	private function fillDefaultValues($case_id, $fields_array, $var = '') {
		$this->load->model('catalog/case');
		$this->load->model('setting/setting');
		$this->load->model('openbay/amazonus');

		$openbay_settings = $this->model_setting_setting->getSetting('openbay_amazonus');

		$case_info = $this->model_catalog_case->getCase($case_id);
		$case_info['description'] = trim(utf8_encode(strip_tags(html_entity_decode($case_info['description']), "<br>")));
		$case_info['image'] = HTTPS_CATALOG . 'image/' . $case_info['image'];

		$tax_added = isset($openbay_settings['openbay_amazonus_listing_tax_added']) ? $openbay_settings['openbay_amazonus_listing_tax_added'] : 0;
		$default_condition =  isset($openbay_settings['openbay_amazonus_listing_default_condition']) ? $openbay_settings['openbay_amazonus_listing_default_condition'] : '';
		$case_info['price'] = number_format($case_info['price'] + $tax_added / 100 * $case_info['price'], 2, '.', '');

		/*Key must be lowecase */
		$defaults = array(
			'sku' => $case_info['sku'],
			'title' => $case_info['name'],
			'quantity' => $case_info['quantity'],
			'standardprice' => $case_info['price'],
			'description' => $case_info['description'],
			'mainimage' => $case_info['image'],
			'currency' => $this->config->get('config_currency'),
			'shippingweight' => number_format($case_info['weight'], 2, '.', ''),
			'conditiontype' => $default_condition,
		);

		$this->load->model('localisation/weight_class');
		$weightClass = $this->model_localisation_weight_class->getWeightClass($case_info['weight_class_id']);
		if(!empty($weightClass)) {
			$defaults['shippingweightunitofmeasure'] = $weightClass['unit'];
		}

		$this->load->model('catalog/manufacturer');
		$manufacturer = $this->model_catalog_manufacturer->getManufacturer($case_info['manufacturer_id']);
		if(!empty($manufacturer)) {
			$defaults['manufacturer'] = $manufacturer['name'];
			$defaults['brand'] = $manufacturer['name'];
		}

		$caseImages = $this->model_catalog_case->getCaseImages($case_id);
		$imageIndex = 1;
		foreach($caseImages as $caseImage) {
			$defaults['pt' . $imageIndex] = HTTPS_CATALOG . 'image/' . $caseImage['image'];
			$imageIndex ++;
		}

		if(!empty($case_info['upc'])) {
			$defaults['type'] = 'UPC';
			$defaults['value'] = $case_info['upc'];
		} else if(!empty($case_info['ean'])) {
			$defaults['type'] = 'EAN';
			$defaults['value'] = $case_info['ean'];
		}

		$meta_keywords = explode(',', $case_info['meta_keyword']);
		foreach ($meta_keywords as $index => $meta_keyword) {
			$defaults['searchterms' . $index] = trim($meta_keyword);
		}

		$this->load->library('amazonus');
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
				$defaults['sku'] = $option['sku'];
				$defaults['quantity'] = $option['stock'];
				$defaults['standardprice'] = number_format($option['price'] + $tax_added / 100 * $option['price'], 2, '.', '');
				$defaults['shippingweight'] = number_format($option['weight'], 2, '.', '');

				if(!empty($option['image'])) {
					$defaults['mainimage'] = HTTPS_CATALOG . 'image/' . $option['image'];
				}
			}
		}

		if($defaults['shippingweight'] <= 0) {
			unset($defaults['shippingweight']);
			unset($defaults['shippingweightunitofmeasure']);
		}

		$filledArray = array();

		foreach($fields_array as $field) {

			$value_array = array('value' => '');

			if(isset($defaults[strtolower($field['name'])])) {
				$value_array = array('value' => $defaults[strtolower($field['name'])]);
			}

			$filledItem = array_merge($field, $value_array);

			$filledArray[] = $filledItem;
		}
		return $filledArray;
	}

	private function fillSavedValues($case_id, $fields_array, $var = '') {

		$this->load->model('openbay/amazonus');
		$savedListing = $this->model_openbay_amazonus->getCase($case_id, $var);

		$decoded_data = (array)json_decode($savedListing['data']);
		$saved_fields = (array)$decoded_data['fields'];

		//Show current quantity instead of last uploaded
		$saved_fields['Quantity'] = $this->model_openbay_amazonus->getCaseQuantity($case_id, $var);

		$filledArray = array();

		foreach($fields_array as $field) {
			$value_array = array('value' => '');

			if(isset($saved_fields[$field['name']])) {
				$value_array = array('value' => $saved_fields[$field['name']]);
			}

			$filledItem = array_merge($field, $value_array);

			$filledArray[] = $filledItem;
		}

		return $filledArray;
	}

	public function resetPending() {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazonus_case` SET `status` = 'saved' WHERE `status` = 'uploaded'");
	}

	private function validateForm() {
		return true;
	}

	private function formatUrlsInText($text) {
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		preg_match_all($reg_exUrl, $text, $matches);
		$usedPatterns = array();
		foreach($matches[0] as $pattern) {
			if(!array_key_exists($pattern, $usedPatterns)) {
				$usedPatterns[$pattern]=true;
				$text = str_replace($pattern, "<a target='_blank' href=" .$pattern .">" . $pattern . "</a>", $text);
			}
		}
		return $text;
	}
}
?>