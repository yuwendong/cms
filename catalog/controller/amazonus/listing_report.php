<?php
class ControllerAmazonusListingReport extends Controller {
	public function index() {
		if ($this->config->get('amazonus_status') != '1') {
			return;
		}

		$this->load->model('openbay/amazonus_case');

		$logger = new Log('amazonus.log');
		$logger->write('amazonus/listing_reports - started');

		$token = $this->config->get('openbay_amazonus_token');

		$incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incomingToken !== $token) {
			$logger->write('amazonus/listing_reports - Incorrect token: ' . $incomingToken);
			return;
		}

		$decrypted = $this->openbay->amazonus->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$logger->write('amazonus/listing_reports - Failed to decrypt data');
			return;
		}

		$logger->write('Received Listing Report: ' . $decrypted);

		$request = json_decode($decrypted, 1);

		$data = array();

		foreach ($request['cases'] as $case) {
			$data[] = array(
				'sku' => $case['sku'],
				'quantity' => $case['quantity'],
				'asin' => $case['asin'],
				'price' => $case['price'],
			);
		}

		if ($data) {
			$this->model_openbay_amazonus_case->addListingReport($data);
		}

		$this->model_openbay_amazonus_case->removeListingReportLock($request['marketplace']);

		$logger->write('amazonus/listing_reports - Finished');
	}
}
?>