<?php
class ControllerModuleFeatured extends Controller {
	protected function index($setting) {
		$this->language->load('module/featured'); 

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/case'); 
		
		$this->load->model('tool/image');

		$this->data['cases'] = array();
		$this->data['position'] = $setting['position'];

		$cases = explode(',', $this->config->get('featured_case'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}
		
		$cases = array_slice($cases, 0, (int)$setting['limit']);
		
		foreach ($cases as $case_id) {
			$case_info = $this->model_catalog_case->getcase($case_id);
			
			if ($case_info) {
				if ($case_info['image']) {
					$image = $this->model_tool_image->resize($case_info['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($case_info['price'], $case_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$case_info['special']) {
					$special = $this->currency->format($this->tax->calculate($case_info['special'], $case_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $case_info['rating'];
				} else {
					$rating = false;
				}
					
				$this->data['cases'][] = array(
					'case_id' => $case_info['case_id'],
					'thumb'   	 => $image,
					'name'    	 => $case_info['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$case_info['reviews']),
					'href'    	 => $this->url->link('case/case', 'case_id=' . $case_info['case_id'])
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/featured.tpl';
		} else {
			$this->template = 'default/template/module/featured.tpl';
		}

		$this->render();
	}
}
?>