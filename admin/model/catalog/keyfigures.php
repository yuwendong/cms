<?php
class ModelCatalogKeyFigures extends Model {
	public function addKeyFigures($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . " keyfigures SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");
		
		$keyfigures_id = $this->db->getLastId(); 

		foreach ($data['keyfigures_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "keyfigures_description SET keyfigures_id = '" . (int)$keyfigures_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['keyfigures_store'])) {
			foreach ($data['keyfigures_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "keyfigures_to_store SET keyfigures_id = '" . (int)$keyfigures_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['keyfigures_layout'])) {
			foreach ($data['keyfigures_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "keyfigures_to_layout SET keyfigures_id = '" . (int)$keyfigures_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'keyfigures_id=" . (int)$keyfigures_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('keyfigures');
	}

	public function editKeyFigures($keyfigures_id, $data) {
				$this->db->query("UPDATE " . DB_PREFIX . "keyfigures SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "keyfigures_description WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		foreach ($data['keyfigures_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "keyfigures_description SET keyfigures_id = '" . (int)$keyfigures_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "keyfigures_to_store WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		if (isset($data['keyfigures_store'])) {
			foreach ($data['keyfigures_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "keyfigures_to_store SET keyfigures_id = '" . (int)$keyfigures_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "keyfigures_to_layout WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		if (isset($data['keyfigures_layout'])) {
			foreach ($data['keyfigures_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "keyfigures_to_layout SET keyfigures_id = '" . (int)$keyfigures_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'keyfigures_id=" . (int)$keyfigures_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'keyfigures_id=" . (int)$keyfigures_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('keyfigures');
	}

	public function deleteKeyFigures($keyfigures_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "keyfigures WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "keyfigures_description WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "keyfigures_to_store WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "keyfigures_to_layout WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'keyfigures_id=" . (int)$keyfigures_id . "'");

		$this->cache->delete('keyfigures');
	}	

	public function getKeyFigures($keyfigures_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'keyfigures_id=" . (int)$keyfigures_id . "') AS keyword FROM " . DB_PREFIX . "keyfigures WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		return $query->row;
	}

	public function getKeyFiguress($data = array()) {		
			$sql = "SELECT * FROM " . DB_PREFIX . "keyfigures";
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$query = $this->db->query($sql);
			return $query->rows;
			
	}

	public function getKeyFiguresDescriptions($keyfigures_id) {
		$keyfigures_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "keyfigures_description WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		foreach ($query->rows as $result) {
			$keyfigures_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}

		return $keyfigures_description_data;
	}

	public function getKeyFiguresStores($keyfigures_id) {
		$keyfigures_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "keyfigures_to_store WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		foreach ($query->rows as $result) {
			$keyfigures_store_data[] = $result['store_id'];
		}

		return $keyfigures_store_data;
	}

	public function getKeyFiguresLayouts($keyfigures_id) {
		$keyfigures_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "keyfigures_to_layout WHERE keyfigures_id = '" . (int)$keyfigures_id . "'");

		foreach ($query->rows as $result) {
			$keyfigures_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $keyfigures_layout_data;
	}

	public function getTotalKeyFiguress() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "keyfigures");

		return $query->row['total'];
	}	

	public function getTotalKeyFiguressByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "keyfigures_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
?>