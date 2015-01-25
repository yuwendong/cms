<?php 
class ControllerFeedGoogleBase extends Controller {
	public function index() {
		if ($this->config->get('google_base_status')) { 
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
			$output .= '<channel>';
			$output .= '<title>' . $this->config->get('config_name') . '</title>'; 
			$output .= '<description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '<link>' . HTTP_SERVER . '</link>';

			$this->load->model('catalog/category');

			$this->load->model('catalog/case');

			$this->load->model('tool/image');

			$cases = $this->model_catalog_case->getCases();

			foreach ($cases as $case) {
				if ($case['description']) {
					$output .= '<item>';
					$output .= '<title>' . $case['name'] . '</title>';
					$output .= '<link>' . $this->url->link('case/case', 'case_id=' . $case['case_id']) . '</link>';
					$output .= '<description>' . $case['description'] . '</description>';
					$output .= '<g:brand>' . html_entity_decode($case['manufacturer'], ENT_QUOTES, 'UTF-8') . '</g:brand>';
					$output .= '<g:condition>new</g:condition>';
					$output .= '<g:id>' . $case['case_id'] . '</g:id>';

					if ($case['image']) {
						$output .= '<g:image_link>' . $this->model_tool_image->resize($case['image'], 500, 500) . '</g:image_link>';
					} else {
						$output .= '<g:image_link>' . $this->model_tool_image->resize('no_image.jpg', 500, 500) . '</g:image_link>';
					}

					$output .= '<g:mpn>' . $case['model'] . '</g:mpn>';

					$currencies = array(
						'USD', 
						'EUR', 
						'GBP'
					);

					if (in_array($this->currency->getCode(), $currencies)) {
						$currency_code = $this->currency->getCode();
						$currency_value = $this->currency->getValue();
					} else {
						$currency_code = 'USD';
						$currency_value = $this->currency->getValue('USD');
					}

					if ((float)$case['special']) {
						$output .= '<g:price>' .  $this->currency->format($this->tax->calculate($case['special'], $case['tax_class_id']), $currency_code, $currency_value, false) . '</g:price>';
					} else {
						$output .= '<g:price>' . $this->currency->format($this->tax->calculate($case['price'], $case['tax_class_id']), $currency_code, $currency_value, false) . '</g:price>';
					}

					$categories = $this->model_catalog_case->getCategories($case['case_id']);

					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);

						if ($path) {
							$string = '';

							foreach (explode('_', $path) as $path_id) {
								$category_info = $this->model_catalog_category->getCategory($path_id);

								if ($category_info) {
									if (!$string) {
										$string = $category_info['name'];
									} else {
										$string .= ' &gt; ' . $category_info['name'];
									}
								}
							}

							$output .= '<g:case_type>' . $string . '</g:case_type>';
						}
					}

					$output .= '<g:quantity>' . $case['quantity'] . '</g:quantity>';
					$output .= '<g:upc>' . $case['upc'] . '</g:upc>'; 
					$output .= '<g:weight>' . $this->weight->format($case['weight'], $case['weight_class_id']) . '</g:weight>';
					$output .= '<g:availability>' . ($case['quantity'] ? 'in stock' : 'out of stock') . '</g:availability>';
					$output .= '</item>';
				}
			}

			$output .= '</channel>'; 
			$output .= '</rss>';	

			$this->response->addHeader('Content-Type: application/rss+xml');
			$this->response->setOutput($output);
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}	

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}		
}
?>