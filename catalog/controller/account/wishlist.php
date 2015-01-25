<?php 
class ControllerAccountWishList extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/wishlist');

		$this->load->model('catalog/case');

		$this->load->model('tool/image');

		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}

		if (isset($this->request->get['remove'])) {
			$key = array_search($this->request->get['remove'], $this->session->data['wishlist']);

			if ($key !== false) {
				unset($this->session->data['wishlist'][$key]);
			}

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->redirect($this->url->link('account/wishlist'));
		}

		$this->document->setTitle($this->language->get('heading_title'));	

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/wishlist'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');	

		$this->data['text_empty'] = $this->language->get('text_empty');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_stock'] = $this->language->get('column_stock');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['cases'] = array();

		foreach ($this->session->data['wishlist'] as $key => $case_id) {
			$case_info = $this->model_catalog_case->getCase($case_id);

			if ($case_info) { 
				if ($case_info['image']) {
					$image = $this->model_tool_image->resize($case_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($case_info['quantity'] <= 0) {
					$stock = $case_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $case_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
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

				$this->data['cases'][] = array(
					'case_id' => $case_info['case_id'],
					'thumb'      => $image,
					'name'       => $case_info['name'],
					'model'      => $case_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => $this->url->link('case/case', 'case_id=' . $case_info['case_id']),
					'remove'     => $this->url->link('account/wishlist', 'remove=' . $case_info['case_id'])
				);
			} else {
				unset($this->session->data['wishlist'][$key]);
			}
		}	

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/wishlist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/wishlist.tpl';
		} else {
			$this->template = 'default/template/account/wishlist.tpl';
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
		$this->language->load('account/wishlist');

		$json = array();

		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}

		if (isset($this->request->post['case_id'])) {
			$case_id = $this->request->post['case_id'];
		} else {
			$case_id = 0;
		}

		$this->load->model('catalog/case');

		$case_info = $this->model_catalog_case->getCase($case_id);

		if ($case_info) {
			if (!in_array($this->request->post['case_id'], $this->session->data['wishlist'])) {	
				$this->session->data['wishlist'][] = $this->request->post['case_id'];
			}

			if ($this->customer->isLogged()) {			
				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('case/case', 'case_id=' . $this->request->post['case_id']), $case_info['name'], $this->url->link('account/wishlist'));				
			} else {
				$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'), $this->url->link('case/case', 'case_id=' . $this->request->post['case_id']), $case_info['name'], $this->url->link('account/wishlist'));				
			}

			$json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}	

		$this->response->setOutput(json_encode($json));
	}	
}
?>