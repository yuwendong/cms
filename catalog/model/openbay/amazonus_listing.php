<?php
class ModelOpenbayAmazonusListing extends Model {
	public function listingSuccessful($case_id) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazonus_case`
			SET `status` = 'ok'
			WHERE case_id = " . (int)$case_id . " AND `version` = 3
		");
	}

	public function listingFailed($case_id, $messages) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazonus_case`
			SET `status` = 'error',
				`messages` = '" . $this->db->escape(json_encode($messages)) . "'
			WHERE case_id = " . (int)$case_id . " AND `version` = 3
		");
	}
}
?>