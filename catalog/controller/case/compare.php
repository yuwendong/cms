<?php  
class ControllerCaseCompare extends Controller {
	public function index() { 
		$this->language->load('case/compare');

		$this->load->model('catalog/case');

		$this->load->model('tool/image');

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}	

		if (isset($this->request->get['remove'])) {
			$key = array_search($this->request->get['remove'], $this->session->data['compare']);

			if ($key !== false) {
				unset($this->session->data['compare'][$key]);
			}

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->redirect($this->url->link('case/compare'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),			
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('case/compare'),			
			'separator' => $this->language->get('text_separator')
		);	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_case'] = $this->language->get('text_case');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['text_tag'] = $this->language->get('text_tag');
		$this->data['text_figure'] = $this->language->get('text_figure');
		$this->data['text_spread'] = $this->language->get('text_spread');
		$this->data['text_location'] = $this->language->get('text_location');
		$this->data['text_time'] = $this->language->get('text_time');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_image'] = $this->language->get('text_image');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_availability'] = $this->language->get('text_availability');
		$this->data['text_rating'] = $this->language->get('text_rating');
		$this->data['text_summary'] = $this->language->get('text_summary');
		$this->data['text_weight'] = $this->language->get('text_weight');
		$this->data['text_dimension'] = $this->language->get('text_dimension');
		$this->data['text_empty'] = $this->language->get('text_empty');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['review_status'] = $this->config->get('config_review_status');

		$this->data['cases'] = array();

		$this->data['attribute_groups'] = array();

		foreach ($this->session->data['compare'] as $key => $case_id) {
			$case_info = $this->model_catalog_case->getCase($case_id);

			if ($case_info) {
				if ($case_info['image']) {
					$image = $this->model_tool_image->resize($case_info['image'], $this->config->get('config_image_compare_width'), $this->config->get('config_image_compare_height'));
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

				if ($case_info['quantity'] <= 0) {
					$availability = $case_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$availability = $case_info['quantity'];
				} else {
					$availability = $this->language->get('text_instock');
				}				

				$attribute_data = array();

				$attribute_groups = $this->model_catalog_case->getCaseAttributes($case_id);

				foreach ($attribute_groups as $attribute_group) {
					foreach ($attribute_group['attribute'] as $attribute) {
						$attribute_data[$attribute['attribute_id']] = $attribute['text'];
					}
				}
			
			$this->data['tags'] = array();
			if ($case_info['tag']) {
				$tags = explode('|', $case_info['tag']);

				foreach ($tags as $tag) {
					$this->data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('case/search', 'tag=' . trim($tag))
					);
				}
			}

			$this->data['figures'] = array();
			if ($case_info['figure']) {
				$figures = explode('|', $case_info['figure']);

				foreach ($figures as $figure) {
					$this->data['figures'][] = array(
						'figure'  => trim($figure),
						'href' => $this->url->link('case/search', 'figure=' . trim($figure))
					);
				}
			}
			$this->data['spreads'] = array();
			if ($case_info['spread']) {
				$spreads = explode('|', $case_info['spread']);

				foreach ($spreads as $spread) {
					$this->data['spreads'][] = array(
						'spread'  => trim($spread),
						'href' => $this->url->link('case/search', 'spread=' . trim($spread))
					);
				}
			}

			$this->data['locations'] = array();
			if ($case_info['location']) {
				$locations = explode('|', $case_info['location']);

				foreach ($locations as $location) {
					$this->data['locations'][] = array(
						'location'  => trim($location),
						'href' => $this->url->link('case/search', 'location=' . trim($location))
					);
				}
			}			
			$this->data['times'] = array();
			if ($case_info['time']) {
				$times = explode('|', $case_info['time']);

				foreach ($times as $time) {
					$this->data['times'][] = array(
						'time'  => trim($time),
						'href' => $this->url->link('case/search', 'time=' . trim($time))
					);
				}
			}

				$this->data['cases'][$case_id] = array(
					'case_id'   => $case_info['case_id'],
					'name'         => $case_info['name'],
					'tag'         => $this->data['tags'],
					'figure'         => $this->data['figures'],
					'spread'         => $this->data['spreads'],
					'location'         => $this->data['locations'],
					'time'         => $this->data['times'],
					'thumb'        => $image,
					'price'        => $price,
					'special'      => $special,
					'description'  => utf8_substr(strip_tags(html_entity_decode($case_info['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..',
					'model'        => $case_info['model'],
					'manufacturer' => $case_info['manufacturer'],
					'availability' => $availability,
					'rating'       => (int)$case_info['rating'],
					'reviews'      => sprintf($this->language->get('text_reviews'), (int)$case_info['reviews']),
					'weight'       => $this->weight->format($case_info['weight'], $case_info['weight_class_id']),
					'length'       => $this->length->format($case_info['length'], $case_info['length_class_id']),
					'width'        => $this->length->format($case_info['width'], $case_info['length_class_id']),
					'height'       => $this->length->format($case_info['height'], $case_info['length_class_id']),
					'attribute'    => $attribute_data,
					'href'         => $this->url->link('case/case', 'case_id=' . $case_id),
					'remove'       => $this->url->link('case/compare', 'remove=' . $case_id)
				);

				foreach ($attribute_groups as $attribute_group) {
					$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];

					foreach ($attribute_group['attribute'] as $attribute) {
						$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
					}
				}
			} else {
				unset($this->session->data['compare'][$key]);
			}
		}

		$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/case/compare.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/case/compare.tpl';
		} else {
			$this->template = 'default/template/case/compare.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	public function add() {
		$this->language->load('case/compare');

		$json = array();

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}

		if (isset($this->request->post['case_id'])) {
			$case_id = $this->request->post['case_id'];
		} else {
			$case_id = 0;
		}

		$this->load->model('catalog/case');

		$case_info = $this->model_catalog_case->getCase($case_id);

		if ($case_info) {
			if (!in_array($this->request->post['case_id'], $this->session->data['compare'])) {	
				if (count($this->session->data['compare']) >= 4) {
					array_shift($this->session->data['compare']);
				}

				$this->session->data['compare'][] = $this->request->post['case_id'];
			}

			$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('case/case', 'case_id=' . $this->request->post['case_id']), $case_info['name'], $this->url->link('case/compare'));				

			$json['total'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		}	

		$this->response->setOutput(json_encode($json));
	}
}
?>