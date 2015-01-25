<?php
class ModelOpenbayAmazonListing extends Model {
	private $tabs = array();

	public function search($search_string, $marketplace) {

		$search_params = array(
			'search_string' => $search_string,
			'marketplace' => $marketplace,
		);

		$results = json_decode($this->openbay->amazon->callWithResponse('casev3/search', $search_params), 1);

		$cases = array();

		foreach ($results['Cases'] as $result) {

			$price = '';

			if ($result['price']['amount'] && $result['price']['currency']) {
				$price = $result['price']['amount'] . ' ' . $result['price']['currency'];
			} else {
				$price = '-';
			}

			$link = '';

			switch ($marketplace) {
				case 'uk':
					$link = 'https://www.amazon.co.uk/dp/' . $result['asin'] . '/';
					break;
				case 'de':
					$link = 'https://www.amazon.de/dp/' . $result['asin'] . '/';
					break;
				case 'fr':
					$link = 'https://www.amazon.fr/dp/' . $result['asin'] . '/';
					break;
				case 'it':
					$link = 'https://www.amazon.it/dp/' . $result['asin'] . '/';
					break;
				case 'es':
					$link = 'https://www.amazon.es/dp/' . $result['asin'] . '/';
					break;
			}

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

	public function getCaseByAsin($asin, $market) {
		$data = array(
			'asin' => $asin,
			'marketplace' => $market,
		);

		$results = json_decode($this->openbay->amazon->callWithResponse('casev3/getCase', $data), 1);

		return $results;
	}

	public function getBestPrice($asin, $condition, $marketplace) {
		$search_params = array(
			'asin' => $asin,
			'condition' => $condition,
			'marketplace' => $marketplace,
		);

		$bestPrice = '';

		$result = json_decode($this->openbay->amazon->callWithResponse('casev3/getPrice', $search_params), 1);

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
			'marketplace' => $data['marketplace'],
			'response_url' => HTTPS_CATALOG . 'index.php?route=amazon/listing',
			'case_id' => $data['case_id'],
		);

		$response = $this->openbay->amazon->callWithResponse('casev3/simpleListing', $request);
		$response = json_decode($response);

		if(empty($response)) {
			return array(
				'status' => 0,
				'message' => 'Problem connecting OpenBay: API'
			);
		}

		$response = (array)$response;

		if($response['status'] === 1) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "amazon_case` SET `case_id` = " . (int)$data['case_id'] . ", `status` = 'uploaded', `marketplaces` = '" . $this->db->escape($data['marketplace']) . "', `version` = 3, `var` = '" . isset($data['var']) ? $this->db->escape($data['var']) : '' . "'");
		}

		return $response;
	}

	public function getBrowseNodes($request) {
		return $this->openbay->amazon->callWithResponse('casev3/getBrowseNodes', $request);
	}

	public function doBulkSearch($search_data) {
		foreach ($search_data as $cases) {
			foreach ($cases as $case) {
				$this->db->query("
					REPLACE INTO " . DB_PREFIX . "amazon_case_search (case_id, `status`, marketplace)
					VALUES (" . (int)$case['case_id'] . ", 'searching', '" . $this->db->escape($case['marketplace']) . "')");
			}
		}

		$request_data = array(
			'search' => $search_data,
			'response_url' => HTTPS_CATALOG . 'index.php?route=amazon/search'
		);

		$this->openbay->amazon->callWithResponse('casev3/bulkSearch', $request_data);
	}

	public function deleteSearchResults($marketplace, $case_ids) {
		$imploded_ids = array();

		foreach ($case_ids as $case_id) {
			$imploded_ids[] = (int)$case_id;
		}

		$imploded_ids = implode(',', $imploded_ids);

		$this->db->query("
			DELETE FROM " . DB_PREFIX .  "amazon_case_search
			WHERE marketplace = '" . $this->db->escape($marketplace) . "' AND case_id IN ($imploded_ids)
		");
	}

	public function doBulkListing($data) {
		$this->load->model('catalog/case');
		$request = array();

		$marketplaceMapping = array(
			'uk' => 'A1F83G8C2ARO7P',
			'de' => 'A1PA6795UKMFR9',
			'fr' => 'A13V1IB3VIYZZH',
			'it' => 'APJ6JRA9NG5V4',
			'es' => 'A1RKKUPIHCS9HS',
		);

		foreach($data['cases'] as $case_id => $asin) {
			$case = $this->model_catalog_case->getCase($case_id);

			if ($case) {
				$price = $case['price'];

				if ($this->config->get('openbay_amazon_listing_tax_added') && $this->config->get('openbay_amazon_listing_tax_added') > 0) {
					$price += $price * ($this->config->get('openbay_amazon_listing_tax_added') / 100);
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
					'marketplace' => $data['marketplace'],
					'response_url' => HTTPS_CATALOG . 'index.php?route=amazon/listing',
					'case_id' => $case['case_id'],
				);
			}
		}

		if ($request) {
			$response = $this->openbay->amazon->callWithResponse('casev3/bulkListing', $request);

			$response = json_decode($response, 1);

			if ($response['status'] == 1) {
				foreach ($request as $case) {
					$this->db->query("
						REPLACE INTO `" . DB_PREFIX . "amazon_case`
						SET `case_id` = " . (int)$case['case_id'] . ",
							`status` = 'uploaded',
							`marketplaces` = '" . $this->db->escape($data['marketplace']) . "',
							`version` = 3,
							`var` = ''
					");
				}

				return true;
			}
		}

		return false;
	}
}
?>