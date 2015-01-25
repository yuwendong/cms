<?php
class ModelOpenbayAmazonusListing extends Model {
	private $tabs = array();

	public function search($search_string) {

		$search_params = array(
			'search_string' => $search_string,
		);

		$results = json_decode($this->openbay->amazonus->callWithResponse('casev3/search', $search_params), 1);

		$cases = array();

		foreach ($results['Cases'] as $result) {

			$price = '';

			if ($result['price']['amount'] && $result['price']['currency']) {
				$price = $result['price']['amount'] . ' ' . $result['price']['currency'];
			} else {
				$price = '-';
			}

			$link = 'http://www.amazon.com/gp/case/' . $result['asin'] . '/';

			$cases[] = array(
				'name' => $result['name'],
				'asin' => $result['asin'],
				'image' => $result['image'],
				'price' => $price,
				'link' => $link,
			);
		}

		return $cases;
	}

	public function getCaseByAsin($asin) {
		$data = array('asin' => $asin);

		$results = json_decode($this->openbay->amazonus->callWithResponse('casev3/getCase', $data), 1);

		return $results;
	}

	public function getBestPrice($asin, $condition) {
		$search_params = array(
			'asin' => $asin,
			'condition' => $condition,
		);

		$bestPrice = '';

		$result = json_decode($this->openbay->amazonus->callWithResponse('casev3/getPrice', $search_params), 1);

		if (isset($result['Price']['Amount']) && $result['Price']['Currency'] && $this->currency->has($result['Price']['Currency'])) {
			$bestPrice['amount'] = number_format($this->currency->convert($result['Price']['Amount'], $result['Price']['Currency'], $this->config->get('config_currency')), 2);
			$bestPrice['shipping'] = number_format($this->currency->convert($result['Price']['Shipping'], $result['Price']['Currency'], $this->config->get('config_currency')), 2);
			$bestPrice['currency'] = $result['Price']['Currency'];
		}

		return $bestPrice;
	}

	public function simpleListing($data) {
		$request = array(
			'asin' => $data['asin'],
			'sku' => $data['sku'],
			'quantity' => $data['quantity'],
			'price' => $data['price'],
			'sale' => array(
				'price' => $data['sale_price'],
				'from' => $data['sale_from'],
				'to' => $data['sale_to'],
			),
			'condition' => $data['condition'],
			'condition_note' => $data['condition_note'],
			'start_selling' => $data['start_selling'],
			'restock_date' => $data['restock_date'],
			'response_url' => HTTPS_CATALOG . 'index.php?route=amazonus/listing',
			'case_id' => $data['case_id'],
		);

		$response = $this->openbay->amazonus->callWithResponse('casev3/simpleListing', $request);
		$response = json_decode($response);
		if (empty($response)) {
			return array(
				'status' => 0,
				'message' => 'Problem connecting OpenBay: API'
			);
		}
		$response = (array)$response;

		if ($response['status'] === 1) {
			$this->db->query(" REPLACE INTO `" . DB_PREFIX . "amazonus_case` SET `case_id` = " . (int)$data['case_id'] . ", `status` = 'uploaded', `version` = 3, `var` = '" . isset($data['var']) ? $this->db->escape($data['var']) : '' . "'");
		}

		return $response;
	}

	public function getBrowseNodes($request){
		return $this->openbay->amazonus->callWithResponse('casev3/getBrowseNodes', $request);
	}

	public function deleteSearchResults($case_ids) {
		$imploded_ids = array();

		foreach ($case_ids as $case_id) {
			$imploded_ids[] = (int)$case_id;
		}

		$imploded_ids = implode(',', $imploded_ids);

		$this->db->query("
			DELETE FROM " . DB_PREFIX .  "amazonus_case_search
			WHERE case_id IN ($imploded_ids)
		");
	}

	public function doBulkListing($data) {
		$this->load->model('catalog/case');
		$request = array();

		foreach($data['cases'] as $case_id => $asin) {
			$case = $this->model_catalog_case->getCase($case_id);

			if ($case) {
				$price = $case['price'];

				if ($this->config->get('openbay_amazonus_listing_tax_added') && $this->config->get('openbay_amazonus_listing_tax_added') > 0) {
					$price += $price * ($this->config->get('openbay_amazonus_listing_tax_added') / 100);
				}

				$request[] = array(
					'asin' => $asin,
					'sku' => $case['sku'],
					'quantity' => $case['quantity'],
					'price' => number_format($price, 2, '.', ''),
					'sale' => array(),
					'condition' => (isset($data['condition']) ? $data['condition'] : ''),
					'condition_note' => (isset($data['condition_note']) ? $data['condition_note'] : ''),
					'start_selling' => (isset($data['start_selling']) ? $data['start_selling'] : ''),
					'restock_date' => '',
					'response_url' => HTTPS_CATALOG . 'index.php?route=amazonus/listing',
					'case_id' => $case['case_id'],
				);
			}
		}

		if ($request) {
			$response = $this->openbay->amazonus->callWithResponse('casev3/bulkListing', $request);

			$response = json_decode($response, 1);

			if ($response['status'] == 1) {
				foreach ($request as $case) {
					$this->db->query("
						REPLACE INTO `" . DB_PREFIX . "amazonus_case`
						SET `case_id` = " . (int)$case['case_id'] . ",
							`status` = 'uploaded',
							`var` = '',
							`version` = 3
					");
				}

				return true;
			}
		}

		return false;
	}

	public function doBulkSearch($search_data) {
		foreach ($search_data as $cases) {
			foreach ($cases as $case) {
				$this->db->query("
					REPLACE INTO " . DB_PREFIX . "amazonus_case_search (case_id, `status`)
					VALUES (" . (int)$case['case_id'] . ", 'searching')");
			}
		}

		$request_data = array(
			'search' => $search_data,
			'response_url' => HTTPS_CATALOG . 'index.php?route=amazonus/search'
		);

		$response = $this->openbay->amazonus->callWithResponse('casev3/bulkSearch', $request_data);
	}
}
?>