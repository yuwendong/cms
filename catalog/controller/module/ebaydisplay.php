<?php
class ControllerModuleEbaydisplay extends Controller {
	protected function index($setting) {
		$this->language->load('module/ebaydisplay');
		$this->load->model('tool/image');
		$this->load->model('openbay/ebay_case');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['cases'] = array();

		$cases = $this->cache->get('ebaydisplay.'.md5(serialize($setting)));

		if(!$cases){
			$cases = $this->model_openbay_ebay_case->getDisplayCases();
			$this->cache->set('ebaydisplay.'.md5(serialize($setting)), $cases);
		}

		foreach ($cases['cases'] as $case) {

			if(isset($case['pictures'][0])){
				$image = $this->model_openbay_ebay_case->resize($case['pictures'][0], $setting['image_width'], $setting['image_height']);
			}else{
				$image = '';
			}

			$this->data['cases'][] = array(
				'thumb'   	 => $image,
				'name'    	 => base64_decode($case['Title']),
				'price'   	 => $this->currency->format($case['priceGross']),
				'href'    	 => (string)$case['link'],
			);
		}

		$this->data['tracking_pixel'] = $cases['tracking_pixel'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ebaydisplay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/ebaydisplay.tpl';
		} else {
			$this->template = 'default/template/module/ebaydisplay.tpl';
		}

		$this->render();
	}
}
?>