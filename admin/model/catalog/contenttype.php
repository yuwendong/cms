<?php
class ModelCatalogContenttype extends Model {
	public function addConditionContenttype($name) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contenttype where name='".$this->db->escape($name)."'");

		if(!$query->row){
			$this->db->query("INSERT INTO " . DB_PREFIX . " contenttype SET sort_order = '0',name = '" . $this->db->escape($name) . "', bottom = '0', status = '1'");
			
			$contenttype_id = $this->db->getLastId(); 
			$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_description SET contenttype_id = '" . (int)$contenttype_id . "', language_id = '1', title = '', description = ''");

			$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_to_store SET contenttype_id = '" . (int)$contenttype_id . "', store_id = '0'");

			$this->cache->delete('contenttype');
			return $contenttype_id;
		}else{		
			return $query->row['contenttype_id'];
		}
	}
	public function addContenttype($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . " contenttype SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");
		
		$contenttype_id = $this->db->getLastId(); 

		foreach ($data['contenttype_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_description SET contenttype_id = '" . (int)$contenttype_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['contenttype_store'])) {
			foreach ($data['contenttype_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_to_store SET contenttype_id = '" . (int)$contenttype_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['contenttype_layout'])) {
			foreach ($data['contenttype_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_to_layout SET contenttype_id = '" . (int)$contenttype_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'contenttype_id=" . (int)$contenttype_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('contenttype');
	}

	public function editContenttype($contenttype_id, $data) {
				$this->db->query("UPDATE " . DB_PREFIX . "contenttype SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "contenttype_description WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		foreach ($data['contenttype_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_description SET contenttype_id = '" . (int)$contenttype_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "contenttype_to_store WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		if (isset($data['contenttype_store'])) {
			foreach ($data['contenttype_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_to_store SET contenttype_id = '" . (int)$contenttype_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "contenttype_to_layout WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		if (isset($data['contenttype_layout'])) {
			foreach ($data['contenttype_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "contenttype_to_layout SET contenttype_id = '" . (int)$contenttype_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'contenttype_id=" . (int)$contenttype_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'contenttype_id=" . (int)$contenttype_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('contenttype');
	}

	public function deleteContenttype($contenttype_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "contenttype WHERE contenttype_id = '" . (int)$contenttype_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "contenttype_description WHERE contenttype_id = '" . (int)$contenttype_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "contenttype_to_store WHERE contenttype_id = '" . (int)$contenttype_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "contenttype_to_layout WHERE contenttype_id = '" . (int)$contenttype_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'contenttype_id=" . (int)$contenttype_id . "'");

		$this->cache->delete('contenttype');
	}	

	public function getContenttype($contenttype_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'contenttype_id=" . (int)$contenttype_id . "') AS keyword FROM " . DB_PREFIX . "contenttype WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		return $query->row;
	}

	public function getContenttypes($data = array()) {		
			$sql = "SELECT * FROM " . DB_PREFIX . "contenttype";
			//$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$query = $this->db->query($sql);
			return $query->rows;
			
	}

	public function getContenttypeDescriptions($contenttype_id) {
		$contenttype_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contenttype_description WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		foreach ($query->rows as $result) {
			$contenttype_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}

		return $contenttype_description_data;
	}

	public function getContenttypeStores($contenttype_id) {
		$contenttype_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contenttype_to_store WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		foreach ($query->rows as $result) {
			$contenttype_store_data[] = $result['store_id'];
		}

		return $contenttype_store_data;
	}

	public function getContenttypeLayouts($contenttype_id) {
		$contenttype_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contenttype_to_layout WHERE contenttype_id = '" . (int)$contenttype_id . "'");

		foreach ($query->rows as $result) {
			$contenttype_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $contenttype_layout_data;
	}

	public function getTotalContenttypes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contenttype");

		return $query->row['total'];
	}	

	public function getTotalContenttypesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contenttype_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
?>