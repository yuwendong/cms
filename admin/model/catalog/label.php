<?php
class ModelCatalogLabel extends Model {
	public function addConditionLabel($name) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "label where name='".$this->db->escape($name)."'");

		if(!$query->row){
			$this->db->query("INSERT INTO " . DB_PREFIX . " label SET sort_order = '0',name = '" . $this->db->escape($name) . "', bottom = '0', status = '1'");
			
			$label_id = $this->db->getLastId(); 
			$this->db->query("INSERT INTO " . DB_PREFIX . "label_description SET label_id = '" . (int)$label_id . "', language_id = '1', title = '', description = ''");

			$this->db->query("INSERT INTO " . DB_PREFIX . "label_to_store SET label_id = '" . (int)$label_id . "', store_id = '0'");

			$this->cache->delete('label');
			return $label_id;
		}else{		
			return $query->row['label_id'];
		}
	}
	public function addLabel($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . " label SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");
		
		$label_id = $this->db->getLastId(); 

		foreach ($data['label_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "label_description SET label_id = '" . (int)$label_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['label_store'])) {
			foreach ($data['label_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "label_to_store SET label_id = '" . (int)$label_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['label_layout'])) {
			foreach ($data['label_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "label_to_layout SET label_id = '" . (int)$label_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'label_id=" . (int)$label_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('label');
	}

	public function editLabel($label_id, $data) {
				$this->db->query("UPDATE " . DB_PREFIX . "label SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE label_id = '" . (int)$label_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "label_description WHERE label_id = '" . (int)$label_id . "'");

		foreach ($data['label_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "label_description SET label_id = '" . (int)$label_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "label_to_store WHERE label_id = '" . (int)$label_id . "'");

		if (isset($data['label_store'])) {
			foreach ($data['label_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "label_to_store SET label_id = '" . (int)$label_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "label_to_layout WHERE label_id = '" . (int)$label_id . "'");

		if (isset($data['label_layout'])) {
			foreach ($data['label_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "label_to_layout SET label_id = '" . (int)$label_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'label_id=" . (int)$label_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'label_id=" . (int)$label_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('label');
	}

	public function deleteLabel($label_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "label WHERE label_id = '" . (int)$label_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "label_description WHERE label_id = '" . (int)$label_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "label_to_store WHERE label_id = '" . (int)$label_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "label_to_layout WHERE label_id = '" . (int)$label_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'label_id=" . (int)$label_id . "'");

		$this->cache->delete('label');
	}	

	public function getLabel($label_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'label_id=" . (int)$label_id . "') AS keyword FROM " . DB_PREFIX . "label WHERE label_id = '" . (int)$label_id . "'");

		return $query->row;
	}

	public function getLabels($data = array()) {		
			$sql = "SELECT * FROM " . DB_PREFIX . "label";
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$query = $this->db->query($sql);
			return $query->rows;
			
	}

	public function getLabelDescriptions($label_id) {
		$label_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "label_description WHERE label_id = '" . (int)$label_id . "'");

		foreach ($query->rows as $result) {
			$label_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}

		return $label_description_data;
	}

	public function getLabelStores($label_id) {
		$label_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "label_to_store WHERE label_id = '" . (int)$label_id . "'");

		foreach ($query->rows as $result) {
			$label_store_data[] = $result['store_id'];
		}

		return $label_store_data;
	}

	public function getLabelLayouts($label_id) {
		$label_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "label_to_layout WHERE label_id = '" . (int)$label_id . "'");

		foreach ($query->rows as $result) {
			$label_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $label_layout_data;
	}

	public function getTotalLabels() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "label");

		return $query->row['total'];
	}	

	public function getTotalLabelsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "label_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
?>