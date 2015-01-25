<?php
class ModelCatalogReview extends Model {		
	public function addReview($case_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', case_id = '" . (int)$case_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}
		
	public function getReviewsByCaseId($case_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}		
		
		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.case_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "`case` p ON (r.case_id = p.case_id) LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) WHERE p.case_id = '" . (int)$case_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
			
		return $query->rows;
	}

	public function getTotalReviewsByCaseId($case_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "`case` p ON (r.case_id = p.case_id) LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) WHERE p.case_id = '" . (int)$case_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}
}
?>