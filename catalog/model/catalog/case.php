<?php
class ModelCatalogCase extends Model {
	public function updateViewed($case_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "`case` SET viewed = (viewed + 1) WHERE case_id = '" . (int)$case_id . "'");
	}

	public function getCase($case_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "case_discount pd2 WHERE pd2.case_id = p.case_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "case_special ps WHERE ps.case_id = p.case_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "case_reward pr WHERE pr.case_id = p.case_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.case_id = p.case_id AND r1.status = '1' GROUP BY r1.case_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.case_id = p.case_id AND r2.status = '1' GROUP BY r2.case_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.case_id = '" . (int)$case_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'case_id'          => $query->row['case_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'figure'           => $query->row['figure'],
				'spread'           => $query->row['spread'],
				'location'         => $query->row['location'],
				'time'             => $query->row['time'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getCases($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

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

		if (!empty($data['filter_name'])) {
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
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.tag LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.figure LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.spread LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.time LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.location LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
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

		if (!empty($data['filter_tag'])) {
			$sql .= " AND pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";	
		}

		if (!empty($data['filter_spread'])) {
			$sql .= " AND pd.spread LIKE '%" . $this->db->escape($data['filter_spread']) . "%'";	
		}

		if (!empty($data['filter_figure'])) {
			$sql .= " AND pd.figure LIKE '%" . $this->db->escape($data['filter_figure']) . "%'";	
		}

		if (!empty($data['filter_time'])) {
			$sql .= " AND pd.time LIKE '%" . $this->db->escape($data['filter_time']) . "%'";	
		}

		if (!empty($data['filter_location'])) {
			$sql .= " AND pd.location LIKE '%" . $this->db->escape($data['filter_location']) . "%'";	
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

		return $case_data;
	}

	public function getCaseSpecials($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$sql = "SELECT DISTINCT ps.case_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.case_id = ps.case_id AND r1.status = '1' GROUP BY r1.case_id) AS rating FROM " . DB_PREFIX . "case_special ps LEFT JOIN " . DB_PREFIX . "`case` p ON (ps.case_id = p.case_id) LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.case_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
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

		return $case_data;
	}

	public function getLatestCases($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$case_data = $this->cache->get('case.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . (int)$limit);

		if (!$case_data) { 
			$query = $this->db->query("SELECT p.case_id FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$case_data[$result['case_id']] = $this->getCase($result['case_id']);
			}

			$this->cache->set('case.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $case_data);
		}

		return $case_data;
	}

	public function getPopularCases($limit) {
		$case_data = array();

		$query = $this->db->query("SELECT p.case_id FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) { 		
			$case_data[$result['case_id']] = $this->getCase($result['case_id']);
		}

		return $case_data;
	}

	public function getBestSellerCases($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$case_data = $this->cache->get('case.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit);

		if (!$case_data) { 
			$case_data = array();

			$query = $this->db->query("SELECT op.case_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_case op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "`case`` p ON (op.case_id = p.case_id) LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.case_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) { 		
				$case_data[$result['case_id']] = $this->getCase($result['case_id']);
			}

			$this->cache->set('case.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $case_data);
		}

		return $case_data;
	}

	public function getCaseAttributes($case_id) {
		$case_attribute_group_data = array();

		$case_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "case_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.case_id = '" . (int)$case_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($case_attribute_group_query->rows as $case_attribute_group) {
			$case_attribute_data = array();

			$case_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "case_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.case_id = '" . (int)$case_id . "' AND a.attribute_group_id = '" . (int)$case_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($case_attribute_query->rows as $case_attribute) {
				$case_attribute_data[] = array(
					'attribute_id' => $case_attribute['attribute_id'],
					'name'         => $case_attribute['name'],
					'text'         => $case_attribute['text']		 	
				);
			}

			$case_attribute_group_data[] = array(
				'attribute_group_id' => $case_attribute_group['attribute_group_id'],
				'name'               => $case_attribute_group['name'],
				'attribute'          => $case_attribute_data
			);			
		}

		return $case_attribute_group_data;
	}

	public function getCaseOptions($case_id) {
		$case_option_data = array();

		$case_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.case_id = '" . (int)$case_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($case_option_query->rows as $case_option) {
			if ($case_option['type'] == 'select' || $case_option['type'] == 'radio' || $case_option['type'] == 'checkbox' || $case_option['type'] == 'image') {
				$case_option_value_data = array();

				$case_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.case_id = '" . (int)$case_id . "' AND pov.case_option_id = '" . (int)$case_option['case_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($case_option_value_query->rows as $case_option_value) {
					$case_option_value_data[] = array(
						'case_option_value_id' => $case_option_value['case_option_value_id'],
						'option_value_id'         => $case_option_value['option_value_id'],
						'name'                    => $case_option_value['name'],
						'image'                   => $case_option_value['image'],
						'quantity'                => $case_option_value['quantity'],
						'subtract'                => $case_option_value['subtract'],
						'price'                   => $case_option_value['price'],
						'price_prefix'            => $case_option_value['price_prefix'],
						'weight'                  => $case_option_value['weight'],
						'weight_prefix'           => $case_option_value['weight_prefix']
					);
				}

				$case_option_data[] = array(
					'case_option_id' => $case_option['case_option_id'],
					'option_id'         => $case_option['option_id'],
					'name'              => $case_option['name'],
					'type'              => $case_option['type'],
					'option_value'      => $case_option_value_data,
					'required'          => $case_option['required']
				);
			} else {
				$case_option_data[] = array(
					'case_option_id' => $case_option['case_option_id'],
					'option_id'         => $case_option['option_id'],
					'name'              => $case_option['name'],
					'type'              => $case_option['type'],
					'option_value'      => $case_option['option_value'],
					'required'          => $case_option['required']
				);				
			}
		}

		return $case_option_data;
	}

	public function getCaseDiscounts($case_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_discount WHERE case_id = '" . (int)$case_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;		
	}
	
	public function getCaseExcerpts($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_excerpt WHERE case_id = '" . (int)$case_id . "' order by case_excerpt_id");

		return $query->rows;		
	}

	public function getCaseContents($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_content WHERE case_id = '" . (int)$case_id . "' order by case_content_id");

		return $query->rows;		
	}
	
	public function getCaseDownloads($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_download WHERE case_id = '" . (int)$case_id . "' order by case_download_id");

		return $query->rows;		
	}
	public function getCaseDownload($case_download_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_download WHERE case_download_id = '" . (int)$case_download_id . "'");

		return $query->row;		
	}
	
	public function getCaseImages($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_image WHERE case_id = '" . (int)$case_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getCaseRelated($case_id) {
		$case_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related pr LEFT JOIN " . DB_PREFIX . "`case` p ON (pr.related_id = p.case_id) LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE pr.case_id = '" . (int)$case_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) { 
			$case_data[$result['related_id']] = $this->getCase($result['related_id']);
		}

		return $case_data;
	}
	
	public function getCaseRelatedJSON($json_check,$case_id,$case_name,$level) {
		$this->load->model('catalog/case');
		$nodes = array();
		$edges = array();
		$json = array();
		$flag_add = true;
		foreach ($json_check['nodes'] as $node){
			if($node['id']==$case_name){
				$flag_add = false;
			}
		}
		if($flag_add){
			$nodes[] = array(
						'id' => $case_name,
						'label' => $case_name,
						'name' => '<a href="'.$this->url->link('case/case', 'case_id=' . trim($case_id)).'">'.$case_name.'</a>',					
						'shape' => 'star',
						'size' => 2,
						'group'=> 1
				);
			$case_info = $this->getCase($case_id);
			if ($case_info['tag']) {
				$tags = explode(',', $case_info['tag']);

				foreach ($tags as $tag) {
					$nodes[] = array(
							'id' => 'Tag:'.$tag,
							'name' => 'Tag:'.$tag,						
							'shape' => 'square',
							'parentNode' => $case_name,
							'size' => 1,
							'group'=> 2
					);
					$edges[] = array(
							'id1' => $case_name,
							'id2' => 'Tag:'.$tag,
							'type'=> 'arrowHeadLine'
					);
				}
			}
			
			$json = array(
					'nodes' => $nodes,
					'edges' => $edges
			);
		
		}
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_related pr LEFT JOIN " . DB_PREFIX . "`case` p ON (pr.related_id = p.case_id) LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE pr.case_id = '" . (int)$case_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) { 
			$case_data = $this->getCase($result['related_id']);
			$flag = true;
			foreach ($json_check['nodes'] as $node){
				if($node['id']==$case_data['name']){
					$flag = false;
				}
			}
			if($flag){
			$json['nodes'][] = array(
					'id' => $case_data['name'],
					'name' => $case_data['name'],
					'size' => 2
			);
			//add tag
			$case_info = $this->getCase($case_data['case_id']);
			if ($case_info['tag']) {
				$tags = explode(',', $case_info['tag']);

				foreach ($tags as $tag) {
					$json['nodes'][] = array(
							'id' => 'Tag:'.$tag,
							'name' => 'Tag:'.$tag,						
							'shape' => 'square', 
							'parentNode' => $case_data['name'],
							'size' => 1,
							'group'=> 2
					);
					$json['edges'][] = array(
							'id1' => $case_data['name'],
							'id2' => 'Tag:'.$tag,
							'type'=> 'arrowHeadLine'
					);
				}
			}
			
			
			$json['edges'][] = array(
					'id1' => $case_name,
					'id2'       => $case_data['name'],	
					'value'      => $level
			);
			$back_json = $this->model_catalog_case->getCaseRelatedJSON($json,$case_data['case_id'],$case_data['name'],$level+1);
			foreach ($back_json['nodes'] as $back_node){
				$json['nodes'][] = $back_node;
				}
			foreach ($back_json['edges'] as $back_edge){
				$json['edges'][] = $back_edge;
				}
			}
		}
	
		return $json;
	}

	public function getCaseLayoutId($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_layout WHERE case_id = '" . (int)$case_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}

	public function getCategories($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_category WHERE case_id = '" . (int)$case_id . "'");

		return $query->rows;
	}	

	public function getTotalCases($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$sql = "SELECT COUNT(DISTINCT p.case_id) AS total"; 

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

		if (!empty($data['filter_name'])) {
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
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.tag LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.figure LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.spread LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.time LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
			}
			if (!empty($data['filter_name'])) {
				$sql .= " OR pd.location LIKE '%" . $this->db->escape($data['filter_name']) . "%'";	
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
		if (!empty($data['filter_tag'])) {
			$sql .= " AND pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";	
		}

		if (!empty($data['filter_spread'])) {
			$sql .= " AND pd.spread LIKE '%" . $this->db->escape($data['filter_spread']) . "%'";	
		}

		if (!empty($data['filter_figure'])) {
			$sql .= " AND pd.figure LIKE '%" . $this->db->escape($data['filter_figure']) . "%'";	
		}

		if (!empty($data['filter_time'])) {
			$sql .= " AND pd.time LIKE '%" . $this->db->escape($data['filter_time']) . "%'";	
		}

		if (!empty($data['filter_location'])) {
			$sql .= " AND pd.location LIKE '%" . $this->db->escape($data['filter_location']) . "%'";	
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfiles($case_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "case_profile` `pp` JOIN `" . DB_PREFIX . "profile_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`profile_id` = `pp`.`profile_id` JOIN `" . DB_PREFIX . "profile` `p` ON `p`.`profile_id` = `pd`.`profile_id` WHERE `case_id` = " . (int)$case_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$customer_group_id . " ORDER BY `sort_order` ASC")->rows;

	}

	public function getProfile($case_id, $profile_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "profile` `p` JOIN `" . DB_PREFIX . "case_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`case_id` = " . (int)$case_id . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;
	}

	public function getTotalCaseSpecials() {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		$query = $this->db->query("SELECT COUNT(DISTINCT ps.case_id) AS total FROM " . DB_PREFIX . "case_special ps LEFT JOIN " . DB_PREFIX . "`case` p ON (ps.case_id = p.case_id) LEFT JOIN " . DB_PREFIX . "case_to_store p2s ON (p.case_id = p2s.case_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
}
?>