<?php
class ControllerCaseCase extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('case/case');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->load->model('catalog/category');
		$this->load->model('catalog/contenttype');
		$this->load->model('catalog/contentcolumn');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('case/category', 'path=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$this->data['breadcrumbs'][] = array(
					'text'      => $category_info['name'],
					'href'      => $this->url->link('case/category', 'path=' . $this->request->get['path'].$url),
					'separator' => $this->language->get('text_separator')
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('case/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('case/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),
					'separator' => $this->language->get('text_separator')
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag']) || isset($this->request->get['figure']) || isset($this->request->get['spread']) || isset($this->request->get['time']) || isset($this->request->get['location'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['figure'])) {
				$url .= '&figure=' . $this->request->get['figure'];
			}

			if (isset($this->request->get['spread'])) {
				$url .= '&spread=' . $this->request->get['spread'];
			}

			if (isset($this->request->get['time'])) {
				$url .= '&time=' . $this->request->get['time'];
			}

			if (isset($this->request->get['location'])) {
				$url .= '&location=' . $this->request->get['location'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('case/search', $url),
				'separator' => $this->language->get('text_separator')
			);
		}

		if (isset($this->request->get['case_id'])) {
			$case_id = (int)$this->request->get['case_id'];
		} else {
			$case_id = 0;
		}

		$this->load->model('catalog/case');

		$case_info = $this->model_catalog_case->getCase($case_id);

		if ($case_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}
			
			if (isset($this->request->get['figure'])) {
				$url .= '&figure=' . $this->request->get['figure'];
			}
			
			if (isset($this->request->get['spread'])) {
				$url .= '&spread=' . $this->request->get['spread'];
			}
			
			if (isset($this->request->get['time'])) {
				$url .= '&time=' . $this->request->get['time'];
			}
			
			if (isset($this->request->get['location'])) {
				$url .= '&location=' . $this->request->get['location'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $case_info['name'],
				'href'      => $this->url->link('case/case', $url . '&case_id=' . $this->request->get['case_id']),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($case_info['name']);
			$this->document->setDescription($case_info['meta_description']);
			$this->document->setKeywords($case_info['meta_keyword']);
			$this->document->addLink($this->url->link('case/case', 'case_id=' . $this->request->get['case_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

			$this->data['heading_title'] = $case_info['name'];

			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $case_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['text_figures'] = $this->language->get('text_figures');
			$this->data['text_spreads'] = $this->language->get('text_spreads');
			$this->data['text_locations'] = $this->language->get('text_locations');
			$this->data['text_times'] = $this->language->get('text_times');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			
			
			$this->data['entry_title'] = $this->language->get('entry_title');
			$this->data['entry_url'] = $this->language->get('entry_url');
			$this->data['entry_content'] = $this->language->get('entry_content');
			$this->data['entry_keyword'] = $this->language->get('entry_keyword');
			$this->data['entry_date_file'] = $this->language->get('entry_date_file');
			$this->data['entry_contenttype'] = $this->language->get('entry_contenttype');
			$this->data['entry_contentcolumn'] = $this->language->get('entry_contentcolumn');

			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $case_info['reviews']);
			$this->data['tab_related'] = $this->language->get('tab_related');
			$this->data['tab_keyfigure'] = $this->language->get('tab_keyfigure');
			$this->data['tab_label'] = $this->language->get('tab_label');
			$this->data['tab_download'] = $this->language->get('tab_download');
			$this->data['tab_excerpt'] = $this->language->get('tab_excerpt');

			$this->data['case_id'] = $this->request->get['case_id'];
			$this->data['manufacturer'] = $case_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('case/manufacturer/info', 'manufacturer_id=' . $case_info['manufacturer_id']);
			$this->data['model'] = $case_info['model'];
			$this->data['reward'] = $case_info['reward'];
			$this->data['points'] = $case_info['points'];

			if ($case_info['quantity'] <= 0) {
				$this->data['stock'] = $case_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $case_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if ($case_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($case_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}

			if ($case_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($case_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = '';
			}

			$this->data['images'] = array();

			$results = $this->model_catalog_case->getCaseImages($this->request->get['case_id']);

			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($case_info['price'], $case_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}

			if ((float)$case_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($case_info['special'], $case_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$case_info['special'] ? $case_info['special'] : $case_info['price']);
			} else {
				$this->data['tax'] = false;
			}

			$discounts = $this->model_catalog_case->getCaseDiscounts($this->request->get['case_id']);

			$this->data['discounts'] = array();

			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $case_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}

			$this->data['options'] = array();

			foreach ($this->model_catalog_case->getCaseOptions($this->request->get['case_id']) as $option) {
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_value_data = array();

					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $case_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}

							$option_value_data[] = array(
								'case_option_value_id' => $option_value['case_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}

					$this->data['options'][] = array(
						'case_option_id' => $option['case_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'case_option_id' => $option['case_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);
				}
			}

			if ($case_info['minimum']) {
				$this->data['minimum'] = $case_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}

			$this->data['review_status'] = $this->config->get('config_review_status');
			$this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$case_info['reviews']);
			$this->data['rating'] = (int)$case_info['rating'];
			$this->data['description'] = html_entity_decode($case_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_case->getCaseAttributes($this->request->get['case_id']);
			
			//处置
			$this->data['excerpts'] = array();
			$results = $this->model_catalog_case->getCaseExcerpts($this->request->get['case_id']);	
			foreach ($results as $result) {
				$contenttype = $this->model_catalog_contenttype->getContenttype($result['contenttype_id']);	
				$this->data['excerpts'][] = array(
					'case_excerpt_id' => $result['case_excerpt_id'],
					'contenttype'   	 => $contenttype['name'],
					'content' 	 => $result['content']
				);

			}		
			//附件信息
			$this->data['downloads'] = array();
			$results = $this->model_catalog_case->getCaseDownloads($this->request->get['case_id']);	
			foreach ($results as $result) {	
				$this->data['downloads'][] = array(
					'case_download_id' => $result['case_download_id'],
					'name'   	 => $result['name'],
					'href' 	 => $this->url->link('case/case/download', '&case_download_id=' . $result['case_download_id'])
				);

			}		

			$this->data['cases'] = array();
			$results = $this->model_catalog_case->getCaseRelated($this->request->get['case_id']);	
	
			foreach ($results as $result) {
				
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
				}	
			
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$this->data['cases'][] = array(
					'case_id' => $result['case_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('case/case', 'case_id=' . $result['case_id'])
				);
			}
			
			//$this->data['json'] =  (json_encode(($this->model_catalog_case->getCaseRelatedJSON(array(),$case_info['case_id'],$case_info['name'],1))));
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
			
			$this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
			$this->data['profiles'] = $this->model_catalog_case->getProfiles($case_info['case_id']);

			$this->model_catalog_case->updateViewed($this->request->get['case_id']);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/case/case.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/case/case.tpl';
			} else {
				$this->template = 'default/template/case/case.tpl';
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
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['figure'])) {
				$url .= '&figure=' . $this->request->get['figure'];
			}
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('case/case', $url . '&case_id=' . $case_id),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($this->language->get('text_error'));

			$this->data['heading_title'] = $this->language->get('text_error');

			$this->data['text_error'] = $this->language->get('text_error');

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
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
	}

	public function review() {
		$this->language->load('case/case');

		$this->load->model('catalog/review');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByCaseId($this->request->get['case_id']);

		$results = $this->model_catalog_review->getReviewsByCaseId($this->request->get['case_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$this->data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('case/case/review', 'case_id=' . $this->request->get['case_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/case/review.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/case/review.tpl';
		} else {
			$this->template = 'default/template/case/review.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function getRecurringDescription() {
		$this->language->load('case/case');
		$this->load->model('catalog/case');

		if (isset($this->request->post['case_id'])) {
			$case_id = $this->request->post['case_id'];
		} else {
			$case_id = 0;
		}

		if (isset($this->request->post['profile_id'])) {
			$profile_id = $this->request->post['profile_id'];
		} else {
			$profile_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$case_info = $this->model_catalog_case->getCase($case_id);
		$profile_info = $this->model_catalog_case->getProfile($case_id, $profile_id);

		$json = array();

		if ($case_info && $profile_info) {

			if (!$json) {
				$frequencies = array(
					'day' => $this->language->get('text_day'),
					'week' => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month' => $this->language->get('text_month'),
					'year' => $this->language->get('text_year'),
				);

				if ($profile_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($profile_info['trial_price'] * $quantity, $case_info['tax_class_id'], $this->config->get('config_tax')));
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $profile_info['trial_cycle'], $frequencies[$profile_info['trial_frequency']], $profile_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($profile_info['price'] * $quantity, $case_info['tax_class_id'], $this->config->get('config_tax')));

				if ($profile_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_until_canceled_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function write() {
		$this->language->load('case/case');

		$this->load->model('catalog/review');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}

			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}

			if (!isset($json['error'])) {
				$this->model_catalog_review->addReview($this->request->get['case_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function captcha() {
		$this->load->library('captcha');

		$captcha = new Captcha();

		$this->session->data['captcha'] = $captcha->getCode();

		$captcha->showImage();
	}

	public function upload() {
		$this->language->load('case/case');

		$json = array();

		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();

			$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			$file = basename($filename) . '.' . md5(mt_rand());

			// Hide the uploaded file name so people can not link to it directly.
			$json['file'] = $this->encryption->encrypt($file);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->setOutput(json_encode($json));
	}
		
	function url_encode($str) {  
    if(is_array($str)) {  
        foreach($str as $key=>$value) {  
            $str[urlencode($key)] = $this->url_encode($value);  
        }  
    } else {  
        $str = urlencode($str);  
    }  
      
    return $str;  
	}

	public function download() {

		$this->load->model('catalog/case');

		if (isset($this->request->get['case_download_id'])) {
			$case_download_id = $this->request->get['case_download_id'];
		} else {
			$case_download_id = 0;
		}

		$download_info = $this->model_catalog_case->getCaseDownload($case_download_id);

		if ($download_info) {
			$file = DIR_DOWNLOAD . iconv("UTF-8","gb2312",$download_info['filename']);
			$mask = $download_info['mask'];

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . $mask. '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					if (ob_get_level()) ob_end_clean();

					readfile($file, 'rb');


					exit;
				} else {
					exit('错误: 找不到文件!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
					exit('错误: 找不到文件!');
		}
	}		
	
}
?>