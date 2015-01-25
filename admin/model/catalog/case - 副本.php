<?php
class ModelCatalogCase extends Model {
	public function addCase($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "`case` SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', date_unavailable = '" . $this->db->escape($data['date_unavailable']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "',  points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '1', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$case_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "`case` SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE case_id = '" . (int)$case_id . "'");
		}

		foreach ($data['case_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "case_description SET case_id = '" . (int)$case_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', figure = '" . $this->db->escape($value['figure']) . "', spread = '" . $this->db->escape($value['spread']) . "', location = '" . $this->db->escape($value['location']) . "', time = '" . $this->db->escape($value['time']) . "'");
		}

		if (isset($data['case_store'])) {
			foreach ($data['case_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_store SET case_id = '" . (int)$case_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['case_attribute'])) {
			foreach ($data['case_attribute'] as $case_attribute) {
				if ($case_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "case_attribute WHERE case_id = '" . (int)$case_id . "' AND attribute_id = '" . (int)$case_attribute['attribute_id'] . "'");

					foreach ($case_attribute['case_attribute_description'] as $language_id => $case_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_attribute SET case_id = '" . (int)$case_id . "', attribute_id = '" . (int)$case_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($case_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['case_option'])) {
			foreach ($data['case_option'] as $case_option) {
				if ($case_option['type'] == 'select' || $case_option['type'] == 'radio' || $case_option['type'] == 'checkbox' || $case_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "case_option SET case_id = '" . (int)$case_id . "', option_id = '" . (int)$case_option['option_id'] . "', required = '" . (int)$case_option['required'] . "'");

					$case_option_id = $this->db->getLastId();

					if (isset($case_option['case_option_value']) && count($case_option['case_option_value']) > 0 ) {
						foreach ($case_option['case_option_value'] as $case_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "case_option_value SET case_option_id = '" . (int)$case_option_id . "', case_id = '" . (int)$case_id . "', option_id = '" . (int)$case_option['option_id'] . "', option_value_id = '" . (int)$case_option_value['option_value_id'] . "', subtract = '" . (int)$case_option_value['subtract'] . "',  price_prefix = '" . $this->db->escape($case_option_value['price_prefix']) . "', points = '" . (int)$case_option_value['points'] . "', points_prefix = '" . $this->db->escape($case_option_value['points_prefix']) . "', weight = '" . (float)$case_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($case_option_value['weight_prefix']) . "'");
						} 
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "case_option WHERE case_option_id = '".$case_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "case_option SET case_id = '" . (int)$case_id . "', option_id = '" . (int)$case_option['option_id'] . "', option_value = '" . $this->db->escape($case_option['option_value']) . "', required = '" . (int)$case_option['required'] . "'");
				}
			}
		}

		if (isset($data['case_discount'])) {
			foreach ($data['case_discount'] as $case_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_discount SET case_id = '" . (int)$case_id . "', customer_group_id = '" . (int)$case_discount['customer_group_id'] . "',  priority = '" . (int)$case_discount['priority'] . "',  date_start = '" . $this->db->escape($case_discount['date_start']) . "', date_end = '" . $this->db->escape($case_discount['date_end']) . "'");
			}
		}

		if (isset($data['case_special'])) {
			foreach ($data['case_special'] as $case_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_special SET case_id = '" . (int)$case_id . "', customer_group_id = '" . (int)$case_special['customer_group_id'] . "', priority = '" . (int)$case_special['priority'] . "',  date_start = '" . $this->db->escape($case_special['date_start']) . "', date_end = '" . $this->db->escape($case_special['date_end']) . "'");
			}
		}
		
		if (isset($data['case_content'])) {
			foreach ($data['case_content'] as $case_content) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_content SET case_id = '" . (int)$case_id . "', contenttype_id = '" . (int)$case_content['contenttype_id'] . "', contentcolumn_id = '" . (int)$case_content['contentcolumn_id'] . "',  title = '" . $this->db->escape($case_content['title']) . "',  url = '" . $this->db->escape($case_content['url']) . "', content = '" . $this->db->escape($case_content['content']) . "', keyword = '" . $this->db->escape($case_content['keyword']) . "', date_file = '" . $this->db->escape($case_content['date_file']) . "'");
			}
		}
		
		if (isset($data['case_excerpt'])) {
			foreach ($data['case_excerpt'] as $case_excerpt) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_excerpt SET case_id = '" . (int)$case_id . "', contenttype_id = '" . (int)$case_excerpt['contenttype_id'] . "', content = '" . $this->db->escape($case_excerpt['content']) . "'");
			}
		}
		
		if (isset($data['case_download'])) {
			foreach ($data['case_download'] as $case_download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_download SET case_id = '" . (int)$case_id . "', name = '" . $this->db->escape($case_download['name']) . "', mask = '" . $this->db->escape($case_download['mask']) . "', filename = '" . $this->db->escape($case_download['filename']) . "', date_added = NOW()");
			}
		}
		
		if (isset($data['case_image'])) {
			foreach ($data['case_image'] as $case_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_image SET case_id = '" . (int)$case_id . "', image = '" . $this->db->escape(html_entity_decode($case_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$case_image['sort_order'] . "'");
			}
		}

		if (isset($data['case_category'])) {
			foreach ($data['case_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_category SET case_id = '" . (int)$case_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['case_filter'])) {
			foreach ($data['case_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_filter SET case_id = '" . (int)$case_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}
		
		if (isset($data['case_label'])) {
			foreach ($data['case_label'] as $label_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_label SET case_id = '" . (int)$case_id . "', label_id = '" . (int)$label_id . "'");
			}
		}
		
		if (isset($data['case_keyfigures'])) {
			foreach ($data['case_keyfigures'] as $keyfigures_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_keyfigures SET case_id = '" . (int)$case_id . "', keyfigures_id = '" . (int)$keyfigures_id . "'");
			}
		}

		if (isset($data['case_related'])) {
			foreach ($data['case_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$related_id . "'");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "'");
			}
		}

		if (isset($data['case_tagrelated'])) {
			foreach ($data['case_tagrelated'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$related_id . "' AND type=1");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$related_id . "', type=1");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "'");
			}
		}
		//add tagrelated
		if (isset($data['case_figurerelated'])) {
			foreach ($data['case_figurerelated'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$related_id . "' AND type=2");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$related_id . "', type=2");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "'");
			}
		}
		//add figurerelated
		if (isset($data['case_spreadrelated'])) {
			foreach ($data['case_spreadrelated'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$related_id . "' AND type=3");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$related_id . "', type=3");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "'");
			}
		}
		//add spreadrelated
		if (isset($data['case_locationrelated'])) {
			foreach ($data['case_locationrelated'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$related_id . "' AND type=4");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$related_id . "', type=4");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "'");
			}
		}
		//add locationrelated
		if (isset($data['case_timerelated'])) {
			foreach ($data['case_timerelated'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$related_id . "' AND type=5");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$related_id . "', type=5");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "'");
			}
		}
		//add timerelated
		
		if (isset($data['case_reward'])) {
			foreach ($data['case_reward'] as $customer_group_id => $case_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_reward SET case_id = '" . (int)$case_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$case_reward['points'] . "'");
			}
		}

		if (isset($data['case_layout'])) {
			foreach ($data['case_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_layout SET case_id = '" . (int)$case_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'case_id=" . (int)$case_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['case_profiles'])) {
			foreach ($data['case_profiles'] as $profile) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "case_profile` SET `case_id` = " . (int)$case_id . ", customer_group_id = " . (int)$profile['customer_group_id'] . ", `profile_id` = " . (int)$profile['profile_id']);
			}
		} 

		$this->cache->delete('case');
	}

	public function editCase($case_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "`case` SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "',  minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', date_unavailable = '" . $this->db->escape($data['date_unavailable']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "',  points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '1', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "`case` SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE case_id = '" . (int)$case_id . "'");
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "case_related` SET invalid='1' WHERE case_id = '" . (int)$case_id . "' OR related_id = '" . (int)$case_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE related_id = '" . (int)$case_id . "'");

		if (isset($data['case_related'])) {
			foreach ($data['case_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$related_id . "' AND type=0");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$related_id . "',weight=1,type=0");
				$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "' AND type=0");
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "',weight=1,type=0");
			}
		}
		
		
		if (isset($data['case_tagrelated'])) {
			foreach ($data['case_tagrelated'] as $case_related) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$case_related['related_id'] . "' AND type=1");
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$case_related['related_id'] . "' AND type='1'");
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$case_related['related_id'] . "' AND type='1'");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$related_id . "' AND related_id = '" . (int)$case_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$related_id . "', related_id = '" . (int)$case_id . "'");
			}
		}
		
		if (isset($data['case_figurerelated'])) {
			foreach ($data['case_figurerelated'] as $case_related) {
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$case_related['related_id'] . "' AND type='2'");
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$case_related['related_id'] . "' AND type='2'");
			}
		}
		if (isset($data['case_spreadrelated'])) {
			foreach ($data['case_spreadrelated'] as $case_related) {
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$case_related['related_id'] . "' AND type='3'");
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$case_related['related_id'] . "' AND type='3'");
			}
		}
		if (isset($data['case_locationrelated'])) {
			foreach ($data['case_locationrelated'] as $case_related) {
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$case_related['related_id'] . "' AND type='4'");
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$case_related['related_id'] . "' AND type='4'");
			}
		}
		if (isset($data['case_timerelated'])) {
			foreach ($data['case_timerelated'] as $case_related) {
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$case_related['related_id'] . "' AND type='5'");
				$this->db->query("UPDATE " . DB_PREFIX . "case_related SET status = '" . (int)$case_related['status'] . "', value = '" . $this->db->escape($case_related['value']) . "', weight = '" . (float)$case_related['weight'] . "' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$case_related['related_id'] . "' AND type='5'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_description WHERE case_id = '" . (int)$case_id . "'");
		
		foreach ($data['case_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "case_description SET case_id = '" . (int)$case_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', figure = '" . $this->db->escape($value['figure']) . "', spread = '" . $this->db->escape($value['spread']) . "', location = '" . $this->db->escape($value['location']) . "', time = '" . $this->db->escape($value['time']) . "'");
			
			$this->db->query("UPDATE `" . DB_PREFIX . "case_related` SET weight = '0',value='' WHERE (case_id = '" . (int)$case_id . "' OR related_id = '" . (int)$case_id . "') AND type>0");
			
			//tag
			$tags = explode(',', $value['tag']);
			foreach ($tags as $tag) {
				if(trim($tag)!=''){
				$s_data = array(
				'filter_tag'          => trim($tag)
				);
				$results = $this->getAdvanceCases($s_data);				
				foreach ($results as $result) {
					if($result['case_id']!=$case_id){
					$temp_query_case = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$result['case_id'] . "' and language_id='1'");
					$temp_query_related = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$case_id . "' and language_id='1'");

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "' AND type=1");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['tag']))*count(explode(',', $temp_query_related->row['tag'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['tag']))*count(explode(',', $temp_query_related->row['tag'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($tag) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=1");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($tag)) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=1");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['tag']))*count(explode(',', $temp_query_related->row['tag'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($tag) . "',weight='".(float)$weight."', type=1");
					}

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "' AND type=1");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['tag']))*count(explode(',', $temp_query_related->row['tag'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['tag']))*count(explode(',', $temp_query_related->row['tag'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($tag) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=1");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($tag)) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=1");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['tag']))*count(explode(',', $temp_query_related->row['tag'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET related_id = '" . (int)$case_id . "', case_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($tag) . "',weight='".(float)$weight."', type=1");
					}
					}
				}
				}
			}

			//figure
			$figures = explode(',', $value['figure']);
			foreach ($figures as $figure) {
				if(trim($figure)!=''){
				$s_data = array(
				'filter_figure'          => trim($figure)
				);
				$results = $this->getAdvanceCases($s_data);				
				foreach ($results as $result) {
					if($result['case_id']!=$case_id){
					$temp_query_case = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$result['case_id'] . "' and language_id='1'");
					$temp_query_related = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$case_id . "' and language_id='1'");

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "' AND type=2");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['figure']))*count(explode(',', $temp_query_related->row['figure'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['figure']))*count(explode(',', $temp_query_related->row['figure'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($figure) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=2");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($figure)) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=2");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['figure']))*count(explode(',', $temp_query_related->row['figure'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($figure) . "',weight='".(float)$weight."', type=2");
					}

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "' AND type=2");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['figure']))*count(explode(',', $temp_query_related->row['figure'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['figure']))*count(explode(',', $temp_query_related->row['figure'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($figure) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=2");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($figure)) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=2");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['figure']))*count(explode(',', $temp_query_related->row['figure'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET related_id = '" . (int)$case_id . "', case_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($figure) . "',weight='".(float)$weight."', type=2");
					}
					}
				}
				}
			}

			//spread
			$spreads = explode(',', $value['spread']);
			foreach ($spreads as $spread) {
				if(trim($spread)!=''){
				$s_data = array(
				'filter_spread'          => trim($spread)
				);
				$results = $this->getAdvanceCases($s_data);				
				foreach ($results as $result) {
					if($result['case_id']!=$case_id){
					$temp_query_case = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$result['case_id'] . "' and language_id='1'");
					$temp_query_related = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$case_id . "' and language_id='1'");

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "' AND type=3");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['spread']))*count(explode(',', $temp_query_related->row['spread'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['spread']))*count(explode(',', $temp_query_related->row['spread'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($spread) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=3");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($spread)) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=3");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['spread']))*count(explode(',', $temp_query_related->row['spread'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($spread) . "',weight='".(float)$weight."', type=3");
					}

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "' AND type=3");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['spread']))*count(explode(',', $temp_query_related->row['spread'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['spread']))*count(explode(',', $temp_query_related->row['spread'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($spread) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=3");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($spread)) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=3");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['spread']))*count(explode(',', $temp_query_related->row['spread'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET related_id = '" . (int)$case_id . "', case_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($spread) . "',weight='".(float)$weight."', type=3");
					}
					}
				}
				}
			}

			//location
			$locations = explode(',', $value['location']);
			foreach ($locations as $location) {
				if(trim($location)!=''){
				$s_data = array(
				'filter_location'          => trim($location)
				);
				$results = $this->getAdvanceCases($s_data);				
				foreach ($results as $result) {
					if($result['case_id']!=$case_id){
					$temp_query_case = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$result['case_id'] . "' and language_id='1'");
					$temp_query_related = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$case_id . "' and language_id='1'");

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "' AND type=4");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['location']))*count(explode(',', $temp_query_related->row['location'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['location']))*count(explode(',', $temp_query_related->row['location'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($location) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=4");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($location)) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=4");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['location']))*count(explode(',', $temp_query_related->row['location'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($location) . "',weight='".(float)$weight."', type=4");
					}

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "' AND type=4");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['location']))*count(explode(',', $temp_query_related->row['location'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['location']))*count(explode(',', $temp_query_related->row['location'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($location) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=4");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($location)) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=4");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['location']))*count(explode(',', $temp_query_related->row['location'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET related_id = '" . (int)$case_id . "', case_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($location) . "',weight='".(float)$weight."', type=4");
					}
					}
				}
				}
			}
			//time
			$times = explode(',', $value['time']);
			foreach ($times as $time) {
				if(trim($time)!=''){
				$this->log->write('time:|'.$time.'|');
				$s_data = array(
				'filter_time'          => trim($time)
				);
				$results = $this->getAdvanceCases($s_data);				
				foreach ($results as $result) {
					if($result['case_id']!=$case_id){
					$temp_query_case = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$result['case_id'] . "' and language_id='1'");
					$temp_query_related = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description where case_id = '" . (int)$case_id . "' and language_id='1'");

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "' AND type=5");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['time']))*count(explode(',', $temp_query_related->row['time'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['time']))*count(explode(',', $temp_query_related->row['time'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($time) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=5");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($time)) . "',invalid = '0',weight='".(float)$weight."' WHERE case_id = '" . (int)$case_id . "' AND related_id = '" . (int)$result['case_id'] . "'AND type=5");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['time']))*count(explode(',', $temp_query_related->row['time'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET case_id = '" . (int)$case_id . "', related_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($time) . "',weight='".(float)$weight."', type=5");
					}

					// add case_id relation
					$temp_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "' AND type=5");
					if($temp_query->row){
						if(trim($temp_query->row['value'])!=''){
						$weight = (count(explode(',', $temp_query->row['value']))+1)*(count(explode(',', $temp_query->row['value']))+1)/(count(explode(',', $temp_query_case->row['time']))*count(explode(',', $temp_query_related->row['time'])));
						}else{
						$weight = (count(explode(',', $temp_query->row['value'])))*(count(explode(',', $temp_query->row['value'])))/(count(explode(',', $temp_query_case->row['time']))*count(explode(',', $temp_query_related->row['time'])));
						}
						if(trim($temp_query->row['value'])==''){
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . trim($time) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=5");
						}else{
							$this->db->query("UPDATE " . DB_PREFIX . "case_related SET value = '" . $this->db->escape($temp_query->row['value'].', '.trim($time)) . "',invalid = '0',weight='".(float)$weight."' WHERE related_id = '" . (int)$case_id . "' AND case_id = '" . (int)$result['case_id'] . "'AND type=5");
						}
					}else{
						$weight = 1/(count(explode(',', $temp_query_case->row['time']))*count(explode(',', $temp_query_related->row['time'])));
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_related SET related_id = '" . (int)$case_id . "', case_id = '" . (int)$result['case_id'] . "',status = '0', value = '" . $this->db->escape($time) . "',weight='".(float)$weight."', type=5");
					}
					}
				}
				}
			}			
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_store WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_store'])) {
			foreach ($data['case_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_store SET case_id = '" . (int)$case_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_attribute WHERE case_id = '" . (int)$case_id . "'");

		if (!empty($data['case_attribute'])) {
			foreach ($data['case_attribute'] as $case_attribute) {
				if ($case_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "case_attribute WHERE case_id = '" . (int)$case_id . "' AND attribute_id = '" . (int)$case_attribute['attribute_id'] . "'");

					foreach ($case_attribute['case_attribute_description'] as $language_id => $case_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "case_attribute SET case_id = '" . (int)$case_id . "', attribute_id = '" . (int)$case_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($case_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_option WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_option_value WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_option'])) {
			foreach ($data['case_option'] as $case_option) {
				if ($case_option['type'] == 'select' || $case_option['type'] == 'radio' || $case_option['type'] == 'checkbox' || $case_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "case_option SET case_option_id = '" . (int)$case_option['case_option_id'] . "', case_id = '" . (int)$case_id . "', option_id = '" . (int)$case_option['option_id'] . "', required = '" . (int)$case_option['required'] . "'");

					$case_option_id = $this->db->getLastId();

					if (isset($case_option['case_option_value'])  && count($case_option['case_option_value']) > 0 ) {
						foreach ($case_option['case_option_value'] as $case_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "case_option_value SET case_option_value_id = '" . (int)$case_option_value['case_option_value_id'] . "', case_option_id = '" . (int)$case_option_id . "', case_id = '" . (int)$case_id . "', option_id = '" . (int)$case_option['option_id'] . "', option_value_id = '" . (int)$case_option_value['option_value_id'] . "',  subtract = '" . (int)$case_option_value['subtract'] . "',  price_prefix = '" . $this->db->escape($case_option_value['price_prefix']) . "', points = '" . (int)$case_option_value['points'] . "', points_prefix = '" . $this->db->escape($case_option_value['points_prefix']) . "', weight = '" . (float)$case_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($case_option_value['weight_prefix']) . "'");
						}
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "case_option WHERE case_option_id = '".$case_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "case_option SET case_option_id = '" . (int)$case_option['case_option_id'] . "', case_id = '" . (int)$case_id . "', option_id = '" . (int)$case_option['option_id'] . "', option_value = '" . $this->db->escape($case_option['option_value']) . "', required = '" . (int)$case_option['required'] . "'");
				}					
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_content WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_content'])) {
			foreach ($data['case_content'] as $case_content) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_content SET case_id = '" . (int)$case_id . "', contenttype_id = '" . (int)$case_content['contenttype_id'] . "', contentcolumn_id = '" . (int)$case_content['contentcolumn_id'] . "',  title = '" . $this->db->escape($case_content['title']) . "',  url = '" . $this->db->escape($case_content['url']) . "', content = '" . $this->db->escape($case_content['content']) . "', keyword = '" . $this->db->escape($case_content['keyword']) . "', date_file = '" . $this->db->escape($case_content['date_file']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_excerpt WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_excerpt'])) {
			foreach ($data['case_excerpt'] as $case_excerpt) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_excerpt SET case_id = '" . (int)$case_id . "', contenttype_id = '" . (int)$case_excerpt['contenttype_id'] . "', content = '" . $this->db->escape($case_excerpt['content']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_download WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_download'])) {
			foreach ($data['case_download'] as $case_download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_download SET case_id = '" . (int)$case_id . "', name = '" . $this->db->escape($case_download['name']) . "', mask = '" . $this->db->escape($case_download['mask']) . "', filename = '" . $this->db->escape($case_download['filename']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_discount WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_discount'])) {
			foreach ($data['case_discount'] as $case_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_discount SET case_id = '" . (int)$case_id . "', customer_group_id = '" . (int)$case_discount['customer_group_id'] . "',  priority = '" . (int)$case_discount['priority'] . "',  date_start = '" . $this->db->escape($case_discount['date_start']) . "', date_end = '" . $this->db->escape($case_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_special WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_special'])) {
			foreach ($data['case_special'] as $case_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_special SET case_id = '" . (int)$case_id . "', customer_group_id = '" . (int)$case_special['customer_group_id'] . "', priority = '" . (int)$case_special['priority'] . "',  date_start = '" . $this->db->escape($case_special['date_start']) . "', date_end = '" . $this->db->escape($case_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_image WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_image'])) {
			foreach ($data['case_image'] as $case_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_image SET case_id = '" . (int)$case_id . "', image = '" . $this->db->escape(html_entity_decode($case_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$case_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_label WHERE case_id = '" . (int)$case_id . "'");
		
		if (isset($data['case_label'])) {
			foreach ($data['case_label'] as $label_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_label SET case_id = '" . (int)$case_id . "', label_id = '" . (int)$label_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_keyfigures WHERE case_id = '" . (int)$case_id . "'");
		
		if (isset($data['case_keyfigures'])) {
			foreach ($data['case_keyfigures'] as $keyfigures_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_keyfigures SET case_id = '" . (int)$case_id . "', keyfigures_id = '" . (int)$keyfigures_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_category WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_category'])) {
			foreach ($data['case_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_category SET case_id = '" . (int)$case_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_category WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_category'])) {
			foreach ($data['case_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_category SET case_id = '" . (int)$case_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_filter WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_filter'])) {
			foreach ($data['case_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_filter SET case_id = '" . (int)$case_id . "', filter_id = '" . (int)$filter_id . "'");
			}		
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_reward WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_reward'])) {
			foreach ($data['case_reward'] as $customer_group_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "case_reward SET case_id = '" . (int)$case_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_layout WHERE case_id = '" . (int)$case_id . "'");

		if (isset($data['case_layout'])) {
			foreach ($data['case_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "case_to_layout SET case_id = '" . (int)$case_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'case_id=" . (int)$case_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'case_id=" . (int)$case_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "case_profile` WHERE case_id = " . (int)$case_id);		if (isset($data['case_profiles'])) {			foreach ($data['case_profiles'] as $profile) {				$this->db->query("INSERT INTO `" . DB_PREFIX . "case_profile` SET `case_id` = " . (int)$case_id . ", customer_group_id = " . (int)$profile['customer_group_id'] . ", `profile_id` = " . (int)$profile['profile_id']);			}		}		$this->cache->delete('case');
	}

	public function copyCase($case_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) WHERE p.case_id = '" . (int)$case_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = array();

			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data = array_merge($data, array('case_attribute' => $this->getCaseAttributes($case_id)));
			$data = array_merge($data, array('case_description' => $this->getCaseDescriptions($case_id)));			
			$data = array_merge($data, array('case_discount' => $this->getCaseDiscounts($case_id)));
			$data = array_merge($data, array('case_filter' => $this->getCaseFilters($case_id)));
			$data = array_merge($data, array('case_image' => $this->getCaseImages($case_id)));		
			$data = array_merge($data, array('case_option' => $this->getCaseOptions($case_id)));
			$data = array_merge($data, array('case_related' => $this->getCaseRelated($case_id)));
			$data = array_merge($data, array('case_reward' => $this->getCaseRewards($case_id)));
			$data = array_merge($data, array('case_special' => $this->getCaseSpecials($case_id)));
			$data = array_merge($data, array('case_category' => $this->getCaseCategories($case_id)));
			$data = array_merge($data, array('case_download' => $this->getCaseDownloads($case_id)));
			$data = array_merge($data, array('case_layout' => $this->getCaseLayouts($case_id)));
			$data = array_merge($data, array('case_store' => $this->getCaseStores($case_id)));
			$data = array_merge($data, array('case_profiles' => $this->getProfiles($case_id)));
			$this->addCase($data);
		}
	}

	public function deleteCase($case_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "`case` WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_attribute WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_description WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_discount WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_filter WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_image WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_option WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_option_value WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_related WHERE related_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_reward WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_special WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_content WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_excerpt WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_download WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_category WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_download WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_layout WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "case_to_store WHERE case_id = '" . (int)$case_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "case_profile` WHERE `case_id` = " . (int)$case_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE case_id = '" . (int)$case_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'case_id=" . (int)$case_id. "'");

		$this->cache->delete('case');
	}

	public function getCase($case_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'case_id=" . (int)$case_id . "') AS keyword FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) WHERE p.case_id = '" . (int)$case_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getAdvanceCases($data = array()) {
		$customer_group_id = $this->config->get('config_customer_group_id');

		$sql = "SELECT p.case_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.case_id = p.case_id AND r1.status = '1' GROUP BY r1.case_id) AS rating, (SELECT price FROM " . DB_PREFIX . "case_discount pd2 WHERE pd2.case_id = p.case_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "case_special ps WHERE ps.case_id = p.case_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special"; 

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "case_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "case_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "case_filter pf ON (p2c.case_id = pf.case_id) LEFT JOIN " . DB_PREFIX . "`case` p ON (pf.case_id = p.case_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "`case` p ON (p2c.case_id = p.case_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "`case` p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}	

		if (!empty($data['filter_name']) || !empty($data['filter_tag']) || !empty($data['filter_figure']) || !empty($data['filter_spread']) || !empty($data['filter_location']) || !empty($data['filter_time'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
			//tag
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}
			//figure
			if (!empty($data['filter_name']) && !empty($data['filter_figure'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_figure'])) {
				$sql .= "pd.figure LIKE '%" . $this->db->escape($data['filter_figure']) . "%'";
			}
			//spread
			if (!empty($data['filter_name']) && !empty($data['filter_spread'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_spread'])) {
				$sql .= "pd.spread LIKE '%" . $this->db->escape($data['filter_spread']) . "%'";
			}
			//location
			if (!empty($data['filter_name']) && !empty($data['filter_location'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_location'])) {
				$sql .= "pd.location LIKE '%" . $this->db->escape($data['filter_location']) . "%'";
			}
			//time
			if (!empty($data['filter_name']) && !empty($data['filter_time'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_time'])) {
				$sql .= "pd.time LIKE '%" . $this->db->escape($data['filter_time']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.case_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$case_data = array();
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$case_data[$result['case_id']] = $this->getCase($result['case_id']);
		}
		$this->log->write("SQL:".$sql);
		return $case_data;
	}

	public function getCases($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "case_to_category p2c ON (p.case_id = p2c.case_id)";			
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}


		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.case_id";

		$sort_data = array(
			'pd.name',
			'p.model',
		
			
			'p.status',
			'p.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCasesByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) LEFT JOIN " . DB_PREFIX . "case_to_category p2c ON (p.case_id = p2c.case_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	} 

	public function getCaseDescriptions($case_id) {
		$case_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_description WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'tag'              => $result['tag'],
				'figure'              => $result['figure'],
				'spread'              => $result['spread'],
				'location'              => $result['location'],
				'time'              => $result['time']
			);
		}

		return $case_description_data;
	}

	public function getCaseCategories($case_id) {
		$case_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_category WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_category_data[] = $result['category_id'];
		}

		return $case_category_data;
	}

	public function getCaseFilters($case_id) {
		$case_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_filter WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_filter_data[] = $result['filter_id'];
		}

		return $case_filter_data;
	}

	public function getCaseAttributes($case_id) {
		$case_attribute_data = array();

		$case_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "case_attribute WHERE case_id = '" . (int)$case_id . "' GROUP BY attribute_id");

		foreach ($case_attribute_query->rows as $case_attribute) {
			$case_attribute_description_data = array();

			$case_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_attribute WHERE case_id = '" . (int)$case_id . "' AND attribute_id = '" . (int)$case_attribute['attribute_id'] . "'");

			foreach ($case_attribute_description_query->rows as $case_attribute_description) {
				$case_attribute_description_data[$case_attribute_description['language_id']] = array('text' => $case_attribute_description['text']);
			}

			$case_attribute_data[] = array(
				'attribute_id'                  => $case_attribute['attribute_id'],
				'case_attribute_description' => $case_attribute_description_data
			);
		}

		return $case_attribute_data;
	}

	public function getCaseOptions($case_id) {
		$case_option_data = array();

		$case_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "case_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.case_id = '" . (int)$case_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($case_option_query->rows as $case_option) {
			$case_option_value_data = array();	

			$case_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_option_value WHERE case_option_id = '" . (int)$case_option['case_option_id'] . "'");

			foreach ($case_option_value_query->rows as $case_option_value) {
				$case_option_value_data[] = array(
					'case_option_value_id' => $case_option_value['case_option_value_id'],
					'option_value_id'         => $case_option_value['option_value_id'],
					
					'subtract'                => $case_option_value['subtract'],
					
					'price_prefix'            => $case_option_value['price_prefix'],
					'points'                  => $case_option_value['points'],
					'points_prefix'           => $case_option_value['points_prefix'],						
					'weight'                  => $case_option_value['weight'],
					'weight_prefix'           => $case_option_value['weight_prefix']					
				);
			}

			$case_option_data[] = array(
				'case_option_id'    => $case_option['case_option_id'],
				'option_id'            => $case_option['option_id'],
				'name'                 => $case_option['name'],
				'type'                 => $case_option['type'],			
				'case_option_value' => $case_option_value_data,
				'option_value'         => $case_option['option_value'],
				'required'             => $case_option['required']				
			);
		}

		return $case_option_data;
	}

	public function getCaseImages($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_image WHERE case_id = '" . (int)$case_id . "'");

		return $query->rows;
	}

	public function getCaseDiscounts($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_discount WHERE case_id = '" . (int)$case_id . "' ORDER BY priority");

		return $query->rows;
	}

	public function getCaseSpecials($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_special WHERE case_id = '" . (int)$case_id . "' ORDER BY priority");

		return $query->rows;
	}
	
	public function getCaseContents($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_content WHERE case_id = '" . (int)$case_id . "' ORDER BY case_content_id");

		return $query->rows;
	}
	
	public function getCaseExcerpts($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_excerpt WHERE case_id = '" . (int)$case_id . "' ORDER BY case_excerpt_id");

		return $query->rows;
	}
	
	public function getCaseDownloads($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_download WHERE case_id = '" . (int)$case_id . "' ORDER BY case_download_id");

		return $query->rows;
	}

	public function getDownload($case_download_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_download WHERE case_download_id = '" . (int)$case_download_id . "'");

		return $query->row;
	}

	public function getCaseRewards($case_id) {
		$case_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_reward WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $case_reward_data;
	}

	public function getCaseLabels($case_id) {
		$case_label_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_label WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_label_data[] = $result['label_id'];
		}

		return $case_label_data;
	}

	public function getCaseKeyFiguress($case_id) {
		$case_keyfigures_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_keyfigures WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_keyfigures_data[] = $result['keyfigures_id'];
		}

		return $case_keyfigures_data;
	}

	public function getCaseStores($case_id) {
		$case_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_store WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_store_data[] = $result['store_id'];
		}

		return $case_store_data;
	}

	public function getCaseLayouts($case_id) {
		$case_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_layout WHERE case_id = '" . (int)$case_id . "'");

		foreach ($query->rows as $result) {
			$case_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $case_layout_data;
	}

	public function getCaseRelated($case_id) {
		$case_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' and type=0");

		foreach ($query->rows as $result) {
			$case_related_data[] = $result['related_id'];
		}

		return $case_related_data;
	}
	
	public function getCaseRelatedByType($case_id,$type) {
		$case_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' and type='" . (int)$type . "' and invalid=0");
		return $query->rows;
	}
	public function getCaseRelatedByTotal($case_id) {
		$case_related_data = array();

		$query = $this->db->query("SELECT case_id,related_id,sum(weight) as weight FROM " . DB_PREFIX . "case_related WHERE case_id = '" . (int)$case_id . "' and invalid='0' group by related_id");
		return $query->rows;
	}

	public function getProfiles($case_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "case_profile` WHERE case_id = " . (int)$case_id)->rows;
	}

	public function getTotalCases($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.case_id) AS total FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "case_to_category p2c ON (p.case_id = p2c.case_id)";			
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}


		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}	

	public function getTotalCasesByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "`case` WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalCasesByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "`case` WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalCasesByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "`case` WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalCasesByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "`case` WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalCasesByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "case_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalCasesByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "`case` WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalCasesByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "case_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}	

	public function getTotalCasesByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "case_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}	

	public function getTotalCasesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "case_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalCasesByName($name) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "case_description WHERE name = '" . $this->db->escape($name) . "'");

		return $query->row['total'];
	}
}
?>
