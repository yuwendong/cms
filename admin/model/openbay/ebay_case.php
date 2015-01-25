<?php
class ModelOpenbayEbayCase extends Model {
	public function getTaxRate($class_id) {
		return $this->openbay->getTaxRate($class_id);
	}

	public function countImportImages() {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_image_import`");

		return $qry->num_rows;
	}

	public function getCaseOptions($case_id) {
		$case_option_data = array();

		$case_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.case_id = '" . (int)$case_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($case_option_query->rows as $case_option) {
			if ($case_option['type'] == 'select' || $case_option['type'] == 'radio' || $case_option['type'] == 'image') {
				$case_option_value_data = array();

				$case_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.case_option_id = '" . (int)$case_option['case_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($case_option_value_query->rows as $case_option_value) {
					$case_option_value_data[] = array(
						'case_option_value_id'   => $case_option_value['case_option_value_id'],
						'option_value_id'           => $case_option_value['option_value_id'],
						'name'                      => $case_option_value['name'],
						'image'                     => $case_option_value['image'],
						'image_thumb'               => (!empty($case_option_value['image'])?$this->model_tool_image->resize($case_option_value['image'], 100, 100):''),
						'quantity'                  => $case_option_value['quantity'],
						'subtract'                  => $case_option_value['subtract'],
						'price'                     => $case_option_value['price'],
						'price_prefix'              => $case_option_value['price_prefix'],
						'points'                    => $case_option_value['points'],
						'points_prefix'             => $case_option_value['points_prefix'],
						'weight'                    => $case_option_value['weight'],
						'weight_prefix'             => $case_option_value['weight_prefix']
					);
				}

				$case_option_data[] = array(
					'case_option_id'     => $case_option['case_option_id'],
					'option_id'             => $case_option['option_id'],
					'name'                  => $case_option['name'],
					'type'                  => $case_option['type'],
					'case_option_value'  => $case_option_value_data,
					'required'              => $case_option['required']
				);
			}
		}

		return $case_option_data;
	}

	public function repairLinks() {
		//get distinct case id's where they are active
		$sql = $this->db->query("
			SELECT DISTINCT `case_id`
			FROM `" . DB_PREFIX . "ebay_listing`
			WHERE `status` = '1'");

		//loop over cases and if count is more than 1, update all older entries to 0
		foreach($sql->rows as $row){
			$sql2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_listing` WHERE `case_id` = '".(int)$row['case_id']."' AND `status` = 1 ORDER BY `ebay_listing_id` DESC");

			if($sql2->num_rows > 1){
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_listing` SET `status` = 0  WHERE `case_id` = '".(int)$row['case_id']."'");
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_listing` SET `status` = 1  WHERE `ebay_listing_id` = '".(int)$sql2->row['ebay_listing_id']."'");
			}
		}
	}

	public function searchEbayCatalog($data) {

		if(!isset($data['page'])){ $page = 1; }else{ $page = $data['page']; }

		//validation for category id

		//validation for saerch term

	$response['data']   = $this->openbay->ebay->call('listing/searchCatalog/', array('page' => (int)$page, 'categoryId' => $data['categoryId'], 'search' => $data['search']));
		$response['error']  = $this->openbay->ebay->lasterror;
		$response['msg']    = $this->openbay->ebay->lastmsg;
		return $response;
	}
}
?>