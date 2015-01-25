<?php
class ModelCatalogContentcolumn extends Model {
	public function addConditionContentcolumn($name) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contentcolumn where name='".$this->db->escape($name)."'");

		if(!$query->row){
			$this->db->query("INSERT INTO " . DB_PREFIX . " contentcolumn SET sort_order = '0',name = '" . $this->db->escape($name) . "', bottom = '0', status = '1'");
			
			$contentcolumn_id = $this->db->getLastId(); 
			$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_description SET contentcolumn_id = '" . (int)$contentcolumn_id . "', language_id = '1', title = '', description = ''");

			$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_to_store SET contentcolumn_id = '" . (int)$contentcolumn_id . "', store_id = '0'");

			$this->cache->delete('contentcolumn');
			return $contentcolumn_id;
		}else{		
			return $query->row['contentcolumn_id'];
		}
	}
	public function addContentcolumn($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . " contentcolumn SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");
		
		$contentcolumn_id = $this->db->getLastId(); 

		foreach ($data['contentcolumn_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_description SET contentcolumn_id = '" . (int)$contentcolumn_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['contentcolumn_store'])) {
			foreach ($data['contentcolumn_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_to_store SET contentcolumn_id = '" . (int)$contentcolumn_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['contentcolumn_layout'])) {
			foreach ($data['contentcolumn_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_to_layout SET contentcolumn_id = '" . (int)$contentcolumn_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'contentcolumn_id=" . (int)$contentcolumn_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('contentcolumn');
	}

	public function editContentcolumn($contentcolumn_id, $data) {
				$this->db->query("UPDATE " . DB_PREFIX . "contentcolumn SET sort_order = '" . (int)$data['sort_order'] . "',name = '" . $this->db->escape($data['name']) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "contentcolumn_description WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		foreach ($data['contentcolumn_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_description SET contentcolumn_id = '" . (int)$contentcolumn_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "contentcolumn_to_store WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		if (isset($data['contentcolumn_store'])) {
			foreach ($data['contentcolumn_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_to_store SET contentcolumn_id = '" . (int)$contentcolumn_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "contentcolumn_to_layout WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		if (isset($data['contentcolumn_layout'])) {
			foreach ($data['contentcolumn_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "contentcolumn_to_layout SET contentcolumn_id = '" . (int)$contentcolumn_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'contentcolumn_id=" . (int)$contentcolumn_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'contentcolumn_id=" . (int)$contentcolumn_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('contentcolumn');
	}

	public function deleteContentcolumn($contentcolumn_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "contentcolumn WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "contentcolumn_description WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "contentcolumn_to_store WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "contentcolumn_to_layout WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'contentcolumn_id=" . (int)$contentcolumn_id . "'");

		$this->cache->delete('contentcolumn');
	}	

	public function getContentcolumn($contentcolumn_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'contentcolumn_id=" . (int)$contentcolumn_id . "') AS keyword FROM " . DB_PREFIX . "contentcolumn WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		return $query->row;
	}

	public function getContentcolumns($data = array()) {		
			$sql = "SELECT * FROM " . DB_PREFIX . "contentcolumn";
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$query = $this->db->query($sql);
			return $query->rows;
			
	}

	public function getContentcolumnDescriptions($contentcolumn_id) {
		$contentcolumn_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contentcolumn_description WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		foreach ($query->rows as $result) {
			$contentcolumn_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}

		return $contentcolumn_description_data;
	}

	public function getContentcolumnStores($contentcolumn_id) {
		$contentcolumn_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contentcolumn_to_store WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		foreach ($query->rows as $result) {
			$contentcolumn_store_data[] = $result['store_id'];
		}

		return $contentcolumn_store_data;
	}

	public function getContentcolumnLayouts($contentcolumn_id) {
		$contentcolumn_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contentcolumn_to_layout WHERE contentcolumn_id = '" . (int)$contentcolumn_id . "'");

		foreach ($query->rows as $result) {
			$contentcolumn_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $contentcolumn_layout_data;
	}

	public function getTotalContentcolumns() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contentcolumn");

		return $query->row['total'];
	}	

	public function getTotalContentcolumnsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contentcolumn_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
?>