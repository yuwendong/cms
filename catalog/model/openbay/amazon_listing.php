<?php
class ModelOpenbayAmazonListing extends Model {
	public function listingSuccessful($case_id, $marketplace) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazon_case`
			SET `status` = 'ok'
			WHERE case_id = " . (int)$case_id . " AND `marketplaces` = '" . $this->db->escape($marketplace) . "' AND `version` = 3
		");
	}

	public function listingFailed($case_id, $marketplace, $messages) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazon_case`
			SET `status` = 'error',
			`messages` = '" . $this->db->escape(json_encode($messages)) . "'
			WHERE case_id = " . (int)$case_id . " AND `marketplaces` = '" . $this->db->escape($marketplace) . "' AND `version` = 3
		");
	}
}
?>