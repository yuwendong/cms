<?php 
class ControllerCatalogCase extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('catalog/case');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('catalog/case');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/case');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_case->addCase($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_figure'])) {
				$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_spread'])) {
				$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_time'])) {
				$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_location'])) {
				$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

			$this->redirect($this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/case');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_case->editCase($this->request->get['case_id'], $this->request->post);
			$this->openbay->caseUpdateListen($this->request->get['case_id'], $this->request->post);			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_figure'])) {
				$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_spread'])) {
				$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_time'])) {
				$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_location'])) {
				$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}	

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['case_id'])) {
				$url .= '&case_id=' . $this->request->get['case_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/case/update', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/case');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $case_id) {
				$this->model_catalog_case->deleteCase($case_id);
				$this->openbay->deleteCase($case_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_figure'])) {
				$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_spread'])) {
				$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_time'])) {
				$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_location'])) {
				$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

			$this->redirect($this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function copy() {
		$this->language->load('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/case');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $case_id) {
				$this->model_catalog_case->copyCase($case_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_figure'])) {
				$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_spread'])) {
				$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_time'])) {
				$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_location'])) {
				$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

			$this->redirect($this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_tag'])) {
			$filter_tag = $this->request->get['filter_tag'];
		} else {
			$filter_tag = null;
		}
		if (isset($this->request->get['filter_figure'])) {
			$filter_figure = $this->request->get['filter_figure'];
		} else {
			$filter_figure = null;
		}
		if (isset($this->request->get['filter_spread'])) {
			$filter_spread = $this->request->get['filter_spread'];
		} else {
			$filter_spread = null;
		}
		if (isset($this->request->get['filter_time'])) {
			$filter_time = $this->request->get['filter_time'];
		} else {
			$filter_time = null;
		}
		if (isset($this->request->get['filter_location'])) {
			$filter_location = $this->request->get['filter_location'];
		} else {
			$filter_location = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_tag'])) {
			$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_figure'])) {
			$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_spread'])) {
			$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_time'])) {
			$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/case/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/case/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['delete'] = $this->url->link('catalog/case/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['cases'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_tag'	  => $filter_tag, 
			'filter_figure'	  => $filter_figure, 
			'filter_spread'	  => $filter_spread, 
			'filter_time'	  => $filter_time, 
			'filter_location'	  => $filter_location, 
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');

		$case_total = $this->model_catalog_case->getTotalCases($data);

		$results = $this->model_catalog_case->getCases($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/case/update', 'token=' . $this->session->data['token'] . '&case_id=' . $result['case_id'] . $url, 'SSL')
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}

			$special = false;

			$case_specials = $this->model_catalog_case->getCaseSpecials($result['case_id']);

			foreach ($case_specials  as $case_special) {
				if (($case_special['date_start'] == '0000-00-00' || $case_special['date_start'] < date('Y-m-d')) && ($case_special['date_end'] == '0000-00-00' || $case_special['date_end'] > date('Y-m-d'))) {
					$special = $case_special['price'];

					break;
				}					
			}

			$this->data['cases'][] = array(
				'case_id' => $result['case_id'],
				'name'       => $result['name'],
				'tag'       => $result['tag'],
				'figure'       => $result['figure'],
				'spread'       => $result['spread'],
				'time'       => $result['time'],
				'location'       => $result['location'],
				'model'      => $result['model'],				
				'special'    => $special,
				'image'      => $image,				
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['case_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');		

		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		

		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_tag'] = $this->language->get('column_tag');		
		$this->data['column_figure'] = $this->language->get('column_figure');		
		$this->data['column_spread'] = $this->language->get('column_spread');		
		$this->data['column_time'] = $this->language->get('column_time');		
		$this->data['column_location'] = $this->language->get('column_location');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_price'] = $this->language->get('column_price');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_delete'] = $this->language->get('button_delete');		
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_tag'])) {
			$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_figure'])) {
			$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_spread'])) {
			$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_time'])) {
			$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_tag'])) {
			$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_figure'])) {
			$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_spread'])) {
			$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_time'])) {
			$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $case_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_tag'] = $filter_tag;
		$this->data['filter_figure'] = $filter_figure;
		$this->data['filter_spread'] = $filter_spread;
		$this->data['filter_time'] = $filter_time;
		$this->data['filter_location'] = $filter_location;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/case_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_label'] = $this->language->get('entry_label');
		$this->data['entry_keyfigures'] = $this->language->get('entry_keyfigures');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_sku'] = $this->language->get('entry_sku');

		$this->data['entry_point1'] = $this->language->get('entry_point1');
		$this->data['entry_point2'] = $this->language->get('entry_point2');
		$this->data['entry_point3'] = $this->language->get('entry_point3');
		$this->data['entry_point4'] = $this->language->get('entry_point4');
		$this->data['entry_point5'] = $this->language->get('entry_point5');
		$this->data['entry_point6'] = $this->language->get('entry_point6');
		$this->data['entry_point7'] = $this->language->get('entry_point7');
		$this->data['entry_point8'] = $this->language->get('entry_point8');
		$this->data['entry_point9'] = $this->language->get('entry_point9');
		$this->data['entry_point10'] = $this->language->get('entry_point10');
		$this->data['entry_city1'] = $this->language->get('entry_city1');
		$this->data['entry_city2'] = $this->language->get('entry_city2');
		$this->data['entry_city3'] = $this->language->get('entry_city3');
		$this->data['entry_city4'] = $this->language->get('entry_city4');
		$this->data['entry_city5'] = $this->language->get('entry_city5');
		$this->data['entry_city6'] = $this->language->get('entry_city6');
		$this->data['entry_city7'] = $this->language->get('entry_city7');
		$this->data['entry_city8'] = $this->language->get('entry_city8');
		$this->data['entry_city9'] = $this->language->get('entry_city9');
		$this->data['entry_city10'] = $this->language->get('entry_city10');
		$this->data['entry_city11'] = $this->language->get('entry_city11');
		$this->data['entry_city12'] = $this->language->get('entry_city12');
		$this->data['entry_city13'] = $this->language->get('entry_city13');
		$this->data['entry_city14'] = $this->language->get('entry_city14');
		$this->data['entry_city15'] = $this->language->get('entry_city15');
		$this->data['entry_city16'] = $this->language->get('entry_city16');
		$this->data['entry_city17'] = $this->language->get('entry_city17');
		$this->data['entry_city18'] = $this->language->get('entry_city18');
		$this->data['entry_city19'] = $this->language->get('entry_city19');
		$this->data['entry_city20'] = $this->language->get('entry_city20');


		$this->data['entry_upc'] = $this->language->get('entry_upc');
		$this->data['entry_ean'] = $this->language->get('entry_ean');
		$this->data['entry_jan'] = $this->language->get('entry_jan');
		$this->data['entry_isbn'] = $this->language->get('entry_isbn');
		$this->data['entry_mpn'] = $this->language->get('entry_mpn');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_time'] = $this->language->get('entry_time');
		$this->data['entry_spread'] = $this->language->get('entry_spread');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_date_available'] = $this->language->get('entry_date_available');
		$this->data['entry_date_unavailable'] = $this->language->get('entry_date_unavailable');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_points'] = $this->language->get('entry_points');
		$this->data['entry_option_points'] = $this->language->get('entry_option_points');
		$this->data['entry_subtract'] = $this->language->get('entry_subtract');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['entry_length'] = $this->language->get('entry_length');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_download'] = $this->language->get('entry_download');
		$this->data['entry_category'] = $this->language->get('entry_category');

	    $this->data['entry_abstract'] = $this->language->get('entry_abstract');
	    $this->data['entry_citylist'] = $this->language->get('entry_citylist');
	    $this->data['entry_sad'] = $this->language->get('entry_sad');
	    $this->data['entry_happy'] = $this->language->get('entry_happy');
	    $this->data['entry_angry'] = $this->language->get('entry_angry');
	    $this->data['entry_news'] = $this->language->get('entry_news');
	    $this->data['entry_pie'] = $this->language->get('entry_pie');
	    $this->data['entry_keywordlist'] = $this->language->get('entry_keywordlist');
	    $this->data['entry_pointlist'] = $this->language->get('entry_pointlist');




		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_tagrelated'] = $this->language->get('entry_tagrelated');
		$this->data['entry_totalrelated'] = $this->language->get('entry_totalrelated');
		$this->data['entry_figurerelated'] = $this->language->get('entry_figurerelated');
		$this->data['entry_spreadrelated'] = $this->language->get('entry_spreadrelated');
		$this->data['entry_timerelated'] = $this->language->get('entry_timerelated');
		$this->data['entry_locationrelated'] = $this->language->get('entry_locationrelated');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_figure'] = $this->language->get('entry_figure');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_reward'] = $this->language->get('entry_reward');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_profile'] = $this->language->get('entry_profile');
		
		//excerpt
		$this->data['entry_excerpt_type'] = $this->language->get('entry_excerpt_type');
		$this->data['entry_excerpt_body'] = $this->language->get('entry_excerpt_body');
		
		//download
		$this->data['entry_download_name'] = $this->language->get('entry_download_name');
		$this->data['entry_download_filename'] = $this->language->get('entry_download_filename');
		$this->data['entry_download_date_added'] = $this->language->get('entry_download_date_added');
		
		//content	
		$this->data['entry_content_title'] = $this->language->get('entry_content_title');
		$this->data['entry_content_url'] = $this->language->get('entry_content_url');
		$this->data['entry_content_body'] = $this->language->get('entry_content_body');
		$this->data['entry_content_keyword'] = $this->language->get('entry_content_keyword');
		$this->data['entry_content_date'] = $this->language->get('entry_content_date');
		$this->data['entry_content_type'] = $this->language->get('entry_content_type');
		$this->data['entry_content_column'] = $this->language->get('entry_content_column');

		$this->data['text_recurring_help'] = $this->language->get('text_recurring_help');
		$this->data['text_recurring_title'] = $this->language->get('text_recurring_title');
		$this->data['text_recurring_trial'] = $this->language->get('text_recurring_trial');
		$this->data['entry_recurring'] = $this->language->get('entry_recurring');
		$this->data['entry_recurring_price'] = $this->language->get('entry_recurring_price');
		$this->data['entry_recurring_freq'] = $this->language->get('entry_recurring_freq');
		$this->data['entry_recurring_cycle'] = $this->language->get('entry_recurring_cycle');
		$this->data['entry_recurring_length'] = $this->language->get('entry_recurring_length');
		$this->data['entry_trial'] = $this->language->get('entry_trial');
		$this->data['entry_trial_price'] = $this->language->get('entry_trial_price');
		$this->data['entry_trial_freq'] = $this->language->get('entry_trial_freq');
		$this->data['entry_trial_length'] = $this->language->get('entry_trial_length');
		$this->data['entry_trial_cycle'] = $this->language->get('entry_trial_cycle');

		$this->data['text_length_day'] = $this->language->get('text_length_day');
		$this->data['text_length_week'] = $this->language->get('text_length_week');
		$this->data['text_length_month'] = $this->language->get('text_length_month');
		$this->data['text_length_month_semi'] = $this->language->get('text_length_month_semi');
		$this->data['text_length_year'] = $this->language->get('text_length_year');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_view'] = $this->language->get('button_view');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_excerpt'] = $this->language->get('button_add_excerpt');
		$this->data['button_add_content'] = $this->language->get('button_add_content');
		$this->data['button_add_download'] = $this->language->get('button_add_download');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_add_profile'] = $this->language->get('button_add_profile');
		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_option'] = $this->language->get('tab_option');		
		$this->data['tab_profile'] = $this->language->get('tab_profile');
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_special'] = $this->language->get('tab_special');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_content'] = $this->language->get('tab_content');
		$this->data['tab_excerpt'] = $this->language->get('tab_excerpt');
		$this->data['tab_download'] = $this->language->get('tab_download');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');
		$this->data['tab_marketplace_links'] = $this->language->get('tab_marketplace_links');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

		if (isset($this->error['meta_description'])) {
			$this->data['error_meta_description'] = $this->error['meta_description'];
		} else {
			$this->data['error_meta_description'] = array();
		}		

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}	

		if (isset($this->error['model'])) {
			$this->data['error_model'] = $this->error['model'];
		} else {
			$this->data['error_model'] = '';
		}		

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}	
		
		if (isset($this->error['date_unavailable'])) {
			$this->data['error_date_unavailable'] = $this->error['date_unavailable'];
		} else {
			$this->data['error_date_unavailable'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_tag'])) {
			$url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_figure'])) {
			$url .= '&filter_figure=' . urlencode(html_entity_decode($this->request->get['filter_figure'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_spread'])) {
			$url .= '&filter_spread=' . urlencode(html_entity_decode($this->request->get['filter_spread'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_time'])) {
			$url .= '&filter_time=' . urlencode(html_entity_decode($this->request->get['filter_time'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . urlencode(html_entity_decode($this->request->get['filter_location'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['case_id'])) {
			$this->data['action'] = $this->url->link('catalog/case/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/case/update', 'token=' . $this->session->data['token'] . '&case_id=' . $this->request->get['case_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['case_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$case_info = $this->model_catalog_case->getCase($this->request->get['case_id']);
		}

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['case_description'])) {
			$this->data['case_description'] = $this->request->post['case_description'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_description'] = $this->model_catalog_case->getCaseDescriptions($this->request->get['case_id']);
		} else {
			$this->data['case_description'] = array();
		}

		if (isset($this->request->post['model'])) {
			$this->data['model'] = $this->request->post['model'];
		} elseif (!empty($case_info)) {
			$this->data['model'] = $case_info['model'];
		} else {
			$this->data['model'] = '';
		}

		if (isset($this->request->post['abstract'])) {
			$this->data['abstract'] = $this->request->post['abstract'];
		} elseif (!empty($case_info)) {
			$this->data['abstract'] = $case_info['abstract'];
		} else {
			$this->data['abstract'] = '';
		}

		if (isset($this->request->post['city1'])) {
			$this->data['city1'] = $this->request->post['city1'];
		} elseif (!empty($case_info)) {
			$this->data['city1'] = $case_info['city1'];
		} else {
			$this->data['city1'] = '';
		}

		if (isset($this->request->post['city2'])) {
			$this->data['city2'] = $this->request->post['city2'];
		} elseif (!empty($case_info)) {
			$this->data['city2'] = $case_info['city2'];
		} else {
			$this->data['city2'] = '';
		}

		if (isset($this->request->post['city3'])) {
			$this->data['city3'] = $this->request->post['city3'];
		} elseif (!empty($case_info)) {
			$this->data['city3'] = $case_info['city3'];
		} else {
			$this->data['city3'] = '';
		}

		if (isset($this->request->post['city4'])) {
			$this->data['city4'] = $this->request->post['city4'];
		} elseif (!empty($case_info)) {
			$this->data['city4'] = $case_info['city4'];
		} else {
			$this->data['city4'] = '';
		}
		if (isset($this->request->post['city5'])) {
			$this->data['city5'] = $this->request->post['city5'];
		} elseif (!empty($case_info)) {
			$this->data['city5'] = $case_info['city5'];
		} else {
			$this->data['city5'] = '';
		}

		if (isset($this->request->post['city6'])) {
			$this->data['city6'] = $this->request->post['city6'];
		} elseif (!empty($case_info)) {
			$this->data['city6'] = $case_info['city6'];
		} else {
			$this->data['city6'] = '';
		}

		if (isset($this->request->post['city7'])) {
			$this->data['city7'] = $this->request->post['city7'];
		} elseif (!empty($case_info)) {
			$this->data['city7'] = $case_info['city7'];
		} else {
			$this->data['city7'] = '';
		}

		if (isset($this->request->post['city8'])) {
			$this->data['city8'] = $this->request->post['city8'];
		} elseif (!empty($case_info)) {
			$this->data['city8'] = $case_info['city8'];
		} else {
			$this->data['city8'] = '';
		}

		if (isset($this->request->post['city9'])) {
			$this->data['city9'] = $this->request->post['city9'];
		} elseif (!empty($case_info)) {
			$this->data['city9'] = $case_info['city9'];
		} else {
			$this->data['city9'] = '';
		}

		if (isset($this->request->post['city10'])) {
			$this->data['city10'] = $this->request->post['city10'];
		} elseif (!empty($case_info)) {
			$this->data['city10'] = $case_info['city10'];
		} else {
			$this->data['city10'] = '';
		}
		if (isset($this->request->post['city11'])) {
			$this->data['city11'] = $this->request->post['city11'];
		} elseif (!empty($case_info)) {
			$this->data['city11'] = $case_info['city11'];
		} else {
			$this->data['city11'] = '';
		}

		if (isset($this->request->post['city12'])) {
			$this->data['city12'] = $this->request->post['city12'];
		} elseif (!empty($case_info)) {
			$this->data['city12'] = $case_info['city12'];
		} else {
			$this->data['city12'] = '';
		}

		if (isset($this->request->post['city13'])) {
			$this->data['city13'] = $this->request->post['city13'];
		} elseif (!empty($case_info)) {
			$this->data['city13'] = $case_info['city13'];
		} else {
			$this->data['city13'] = '';
		}

		if (isset($this->request->post['city14'])) {
			$this->data['city14'] = $this->request->post['city14'];
		} elseif (!empty($case_info)) {
			$this->data['city14'] = $case_info['city14'];
		} else {
			$this->data['city14'] = '';
		}
		if (isset($this->request->post['city15'])) {
			$this->data['city15'] = $this->request->post['city15'];
		} elseif (!empty($case_info)) {
			$this->data['city15'] = $case_info['city15'];
		} else {
			$this->data['city15'] = '';
		}

		if (isset($this->request->post['city16'])) {
			$this->data['city16'] = $this->request->post['city16'];
		} elseif (!empty($case_info)) {
			$this->data['city16'] = $case_info['city16'];
		} else {
			$this->data['city16'] = '';
		}

		if (isset($this->request->post['city17'])) {
			$this->data['city17'] = $this->request->post['city17'];
		} elseif (!empty($case_info)) {
			$this->data['city17'] = $case_info['city17'];
		} else {
			$this->data['city17'] = '';
		}

		if (isset($this->request->post['city18'])) {
			$this->data['city18'] = $this->request->post['city18'];
		} elseif (!empty($case_info)) {
			$this->data['city18'] = $case_info['city18'];
		} else {
			$this->data['city18'] = '';
		}

		if (isset($this->request->post['city19'])) {
			$this->data['city19'] = $this->request->post['city19'];
		} elseif (!empty($case_info)) {
			$this->data['city19'] = $case_info['city19'];
		} else {
			$this->data['city19'] = '';
		}

		if (isset($this->request->post['city20'])) {
			$this->data['city20'] = $this->request->post['city20'];
		} elseif (!empty($case_info)) {
			$this->data['city20'] = $case_info['city20'];
		} else {
			$this->data['city20'] = '';
		}

		if (isset($this->request->post['news_percent'])) {
			$this->data['news_percent'] = $this->request->post['news_percent'];
		} elseif (!empty($case_info)) {
			$this->data['news_percent'] = $case_info['news_percent'];
		} else {
			$this->data['news_percent'] = '';
		}

		if (isset($this->request->post['sad_percent'])) {
			$this->data['sad_percent'] = $this->request->post['sad_percent'];
		} elseif (!empty($case_info)) {
			$this->data['sad_percent'] = $case_info['sad_percent'];
		} else {
			$this->data['sad_percent'] = '';
		}

		if (isset($this->request->post['happy_percent'])) {
			$this->data['happy_percent'] = $this->request->post['happy_percent'];
		} elseif (!empty($case_info)) {
			$this->data['happy_percent'] = $case_info['happy_percent'];
		} else {
			$this->data['happy_percent'] = '';
		}

		if (isset($this->request->post['angry_percent'])) {
			$this->data['angry_percent'] = $this->request->post['angry_percent'];
		} elseif (!empty($case_info)) {
			$this->data['angry_percent'] = $case_info['angry_percent'];
		} else {
			$this->data['angry_percent'] = '';
		}

		if (isset($this->request->post['point1'])) {
			$this->data['point1'] = $this->request->post['point1'];
		} elseif (!empty($case_info)) {
			$this->data['point1'] = $case_info['point1'];
		} else {
			$this->data['point1'] = '';
		}

		if (isset($this->request->post['point2'])) {
			$this->data['point2'] = $this->request->post['point2'];
		} elseif (!empty($case_info)) {
			$this->data['point2'] = $case_info['point2'];
		} else {
			$this->data['point2'] = '';
		}

		if (isset($this->request->post['point3'])) {
			$this->data['point3'] = $this->request->post['point3'];
		} elseif (!empty($case_info)) {
			$this->data['point3'] = $case_info['point3'];
		} else {
			$this->data['point3'] = '';
		}

		if (isset($this->request->post['point4'])) {
			$this->data['point4'] = $this->request->post['point4'];
		} elseif (!empty($case_info)) {
			$this->data['point4'] = $case_info['point4'];
		} else {
			$this->data['point4'] = '';
		}
		if (isset($this->request->post['point5'])) {
			$this->data['point5'] = $this->request->post['point5'];
		} elseif (!empty($case_info)) {
			$this->data['point5'] = $case_info['point5'];
		} else {
			$this->data['point5'] = '';
		}

		if (isset($this->request->post['point6'])) {
			$this->data['point6'] = $this->request->post['point6'];
		} elseif (!empty($case_info)) {
			$this->data['point6'] = $case_info['point6'];
		} else {
			$this->data['point6'] = '';
		}

		if (isset($this->request->post['point7'])) {
			$this->data['point7'] = $this->request->post['point7'];
		} elseif (!empty($case_info)) {
			$this->data['point7'] = $case_info['point7'];
		} else {
			$this->data['point7'] = '';
		}

		if (isset($this->request->post['point8'])) {
			$this->data['point8'] = $this->request->post['point8'];
		} elseif (!empty($case_info)) {
			$this->data['point8'] = $case_info['point8'];
		} else {
			$this->data['point8'] = '';
		}

		if (isset($this->request->post['point9'])) {
			$this->data['point9'] = $this->request->post['point9'];
		} elseif (!empty($case_info)) {
			$this->data['point9'] = $case_info['point9'];
		} else {
			$this->data['point9'] = '';
		}

		if (isset($this->request->post['point10'])) {
			$this->data['point10'] = $this->request->post['point10'];
		} elseif (!empty($case_info)) {
			$this->data['point10'] = $case_info['point10'];
		} else {
			$this->data['point10'] = '';
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($case_info)) {
			$this->data['keyword'] = $case_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['sku'])) {
			$this->data['sku'] = $this->request->post['sku'];
		} elseif (!empty($case_info)) {
			$this->data['sku'] = $case_info['sku'];
		} else {
			$this->data['sku'] = '';
		}

		if (isset($this->request->post['upc'])) {
			$this->data['upc'] = $this->request->post['upc'];
		} elseif (!empty($case_info)) {
			$this->data['upc'] = $case_info['upc'];
		} else {
			$this->data['upc'] = '';
		}

		if (isset($this->request->post['ean'])) {
			$this->data['ean'] = $this->request->post['ean'];
		} elseif (!empty($case_info)) {
			$this->data['ean'] = $case_info['ean'];
		} else {
			$this->data['ean'] = '';
		}

		if (isset($this->request->post['jan'])) {
			$this->data['jan'] = $this->request->post['jan'];
		} elseif (!empty($case_info)) {
			$this->data['jan'] = $case_info['jan'];
		} else {
			$this->data['jan'] = '';
		}

		if (isset($this->request->post['isbn'])) {
			$this->data['isbn'] = $this->request->post['isbn'];
		} elseif (!empty($case_info)) {
			$this->data['isbn'] = $case_info['isbn'];
		} else {
			$this->data['isbn'] = '';
		}

		if (isset($this->request->post['mpn'])) {
			$this->data['mpn'] = $this->request->post['mpn'];
		} elseif (!empty($case_info)) {
			$this->data['mpn'] = $case_info['mpn'];
		} else {
			$this->data['mpn'] = '';
		}

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['case_store'])) {
			$this->data['case_store'] = $this->request->post['case_store'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_store'] = $this->model_catalog_case->getCaseStores($this->request->get['case_id']);
		} else {
			$this->data['case_store'] = array(0);
		}	

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($case_info)) {
			$this->data['keyword'] = $case_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($case_info)) {
			$this->data['image'] = $case_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($case_info) && $case_info['image'] && file_exists(DIR_IMAGE . $case_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($case_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (isset($this->request->post['shipping'])) {
			$this->data['shipping'] = $this->request->post['shipping'];
		} elseif (!empty($case_info)) {
			$this->data['shipping'] = $case_info['shipping'];
		} else {
			$this->data['shipping'] = 1;
		}

		if (isset($this->request->post['price'])) {
			$this->data['price'] = $this->request->post['price'];
		} elseif (!empty($case_info)) {
			$this->data['price'] = $case_info['price'];
		} else {
			$this->data['price'] = '';
		}

		$this->load->model('catalog/profile');

		$this->data['profiles'] = $this->model_catalog_profile->getProfiles();

		if (isset($this->request->post['case_profiles'])) {
			$this->data['case_profiles'] = $this->request->post['case_profiles'];
		} elseif (!empty($case_info)) {
			$this->data['case_profiles'] = $this->model_catalog_case->getProfiles($case_info['case_id']);
		} else {
			$this->data['case_profiles'] = array();
		}

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['tax_class_id'])) {
			$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
		} elseif (!empty($case_info)) {
			$this->data['tax_class_id'] = $case_info['tax_class_id'];
		} else {
			$this->data['tax_class_id'] = 0;
		}

		if (isset($this->request->post['date_available'])) {
			$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($case_info)) {
			$this->data['date_available'] = $case_info['date_available'];
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}

		if (isset($this->request->post['date_unavailable'])) {
			$this->data['date_unavailable'] = $this->request->post['date_unavailable'];
		} elseif (!empty($case_info)) {
			$this->data['date_unavailable'] = $case_info['date_unavailable'];
		} else {
			$this->data['date_unavailable'] = date('Y-m-d', time() - 86400);
		}
		
		if (isset($this->request->post['quantity'])) {
			$this->data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($case_info)) {
			$this->data['quantity'] = $case_info['quantity'];
		} else {
			$this->data['quantity'] = 1;
		}

		if (isset($this->request->post['minimum'])) {
			$this->data['minimum'] = $this->request->post['minimum'];
		} elseif (!empty($case_info)) {
			$this->data['minimum'] = $case_info['minimum'];
		} else {
			$this->data['minimum'] = 1;
		}

		if (isset($this->request->post['subtract'])) {
			$this->data['subtract'] = $this->request->post['subtract'];
		} elseif (!empty($case_info)) {
			$this->data['subtract'] = $case_info['subtract'];
		} else {
			$this->data['subtract'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($case_info)) {
			$this->data['sort_order'] = $case_info['sort_order'];
		} else {
			$this->data['sort_order'] = 1;
		}

		$this->load->model('localisation/stock_status');

		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
			$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
		} elseif (!empty($case_info)) {
			$this->data['stock_status_id'] = $case_info['stock_status_id'];
		} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($case_info)) {
			$this->data['status'] = $case_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		if (isset($this->request->post['weight'])) {
			$this->data['weight'] = $this->request->post['weight'];
		} elseif (!empty($case_info)) {
			$this->data['weight'] = $case_info['weight'];
		} else {
			$this->data['weight'] = '';
		}

		$this->load->model('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
			$this->data['weight_class_id'] = $this->request->post['weight_class_id'];
		} elseif (!empty($case_info)) {
			$this->data['weight_class_id'] = $case_info['weight_class_id'];
		} else {
			$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['length'])) {
			$this->data['length'] = $this->request->post['length'];
		} elseif (!empty($case_info)) {
			$this->data['length'] = $case_info['length'];
		} else {
			$this->data['length'] = '';
		}

		if (isset($this->request->post['width'])) {
			$this->data['width'] = $this->request->post['width'];
		} elseif (!empty($case_info)) {	
			$this->data['width'] = $case_info['width'];
		} else {
			$this->data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$this->data['height'] = $this->request->post['height'];
		} elseif (!empty($case_info)) {
			$this->data['height'] = $case_info['height'];
		} else {
			$this->data['height'] = '';
		}

		$this->load->model('localisation/length_class');

		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {
			$this->data['length_class_id'] = $this->request->post['length_class_id'];
		} elseif (!empty($case_info)) {
			$this->data['length_class_id'] = $case_info['length_class_id'];
		} else {
			$this->data['length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['manufacturer_id'])) {
			$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($case_info)) {
			$this->data['manufacturer_id'] = $case_info['manufacturer_id'];
		} else {
			$this->data['manufacturer_id'] = 0;
		}

		if (isset($this->request->post['manufacturer'])) {
			$this->data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($case_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($case_info['manufacturer_id']);

			if ($manufacturer_info) {		
				$this->data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$this->data['manufacturer'] = '';
			}	
		} else {
			$this->data['manufacturer'] = '';
		}

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['case_category'])) {
			$categories = $this->request->post['case_category'];
		} elseif (isset($this->request->get['case_id'])) {		
			$categories = $this->model_catalog_case->getCaseCategories($this->request->get['case_id']);
		} else {
			$categories = array();
		}

		$this->data['case_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$this->data['case_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}

		// Filters
		$this->load->model('catalog/filter');

		if (isset($this->request->post['case_filter'])) {
			$filters = $this->request->post['case_filter'];
		} elseif (isset($this->request->get['case_id'])) {
			$filters = $this->model_catalog_case->getCaseFilters($this->request->get['case_id']);
		} else {
			$filters = array();
		}

		$this->data['case_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['case_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}		

		// Attributes
		$this->load->model('catalog/attribute');

		if (isset($this->request->post['case_attribute'])) {
			$case_attributes = $this->request->post['case_attribute'];
		} elseif (isset($this->request->get['case_id'])) {
			$case_attributes = $this->model_catalog_case->getCaseAttributes($this->request->get['case_id']);
		} else {
			$case_attributes = array();
		}

		$this->data['case_attributes'] = array();

		foreach ($case_attributes as $case_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($case_attribute['attribute_id']);

			if ($attribute_info) {
				$this->data['case_attributes'][] = array(
					'attribute_id'                  => $case_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'case_attribute_description' => $case_attribute['case_attribute_description']
				);
			}
		}		

		// Options
		$this->load->model('catalog/option');

		if (isset($this->request->post['case_option'])) {
			$case_options = $this->request->post['case_option'];
		} elseif (isset($this->request->get['case_id'])) {
			$case_options = $this->model_catalog_case->getCaseOptions($this->request->get['case_id']);			
		} else {
			$case_options = array();
		}			

		$this->data['case_options'] = array();

		foreach ($case_options as $case_option) {
			if ($case_option['type'] == 'select' || $case_option['type'] == 'radio' || $case_option['type'] == 'checkbox' || $case_option['type'] == 'image') {
				$case_option_value_data = array();

				foreach ($case_option['case_option_value'] as $case_option_value) {
					$case_option_value_data[] = array(
						'case_option_value_id' => $case_option_value['case_option_value_id'],
						'option_value_id'         => $case_option_value['option_value_id'],
						
						'subtract'                => $case_option_value['subtract'],
						
						'price_prefix'            => $case_option_value['price_prefix'],
						'points'                  => $case_option_value['points'],
						'points_prefix'           => $case_option_value['points_prefix'],						
						'weight'                  => $case_option_value['weight'],
						'weight_prefix'           => $case_option_value['weight_prefix']	
					);
				}

				$this->data['case_options'][] = array(
					'case_option_id'    => $case_option['case_option_id'],
					'case_option_value' => $case_option_value_data,
					'option_id'            => $case_option['option_id'],
					'name'                 => $case_option['name'],
					'type'                 => $case_option['type'],
					'required'             => $case_option['required']
				);				
			} else {
				$this->data['case_options'][] = array(
					'case_option_id' => $case_option['case_option_id'],
					'option_id'         => $case_option['option_id'],
					'name'              => $case_option['name'],
					'type'              => $case_option['type'],
					'option_value'      => $case_option['option_value'],
					'required'          => $case_option['required']
				);				
			}
		}

		$this->data['option_values'] = array();

		foreach ($this->data['case_options'] as $case_option) {
			if ($case_option['type'] == 'select' || $case_option['type'] == 'radio' || $case_option['type'] == 'checkbox' || $case_option['type'] == 'image') {
				if (!isset($this->data['option_values'][$case_option['option_id']])) {
					$this->data['option_values'][$case_option['option_id']] = $this->model_catalog_option->getOptionValues($case_option['option_id']);
				}
			}
		}

		$this->load->model('sale/customer_group');
		$this->load->model('catalog/contenttype');
		$this->load->model('catalog/contentcolumn');

		$this->data['content_types'] = $this->model_catalog_contenttype->getContenttypes();
		$this->data['content_columns'] = $this->model_catalog_contentcolumn->getContentcolumns();
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['case_discount'])) {
			$this->data['case_discounts'] = $this->request->post['case_discount'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_discounts'] = $this->model_catalog_case->getCaseDiscounts($this->request->get['case_id']);
		} else {
			$this->data['case_discounts'] = array();
		}

		if (isset($this->request->post['case_special'])) {
			$this->data['case_specials'] = $this->request->post['case_special'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_specials'] = $this->model_catalog_case->getCaseSpecials($this->request->get['case_id']);
		} else {
			$this->data['case_specials'] = array();
		}
		
		if (isset($this->request->post['case_content'])) {
			$this->data['case_contents'] = $this->request->post['case_content'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_contents'] = $this->model_catalog_case->getCaseContents($this->request->get['case_id']);
		} else {
			$this->data['case_contents'] = array();
		}
		
		if (isset($this->request->post['case_excerpt'])) {
			$this->data['case_excerpts'] = $this->request->post['case_excerpt'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_excerpts'] = $this->model_catalog_case->getCaseExcerpts($this->request->get['case_id']);
		} else {
			$this->data['case_excerpts'] = array();
		}
		
		if (isset($this->request->post['case_download'])) {
			$case_downloads = $this->request->post['case_download'];
		} elseif (isset($this->request->get['case_id'])) {
			$case_downloads = $this->model_catalog_case->getCaseDownloads($this->request->get['case_id']);
		} else {
			$case_downloads = array();
		}
		$this->data['case_downloads'] = array();		
		foreach ($case_downloads as $case_download) {
				$this->data['case_downloads'][] = array(
					'case_download_id' => $case_download['case_download_id'],
					'name'        => $case_download['name'],
					'filename'        => $case_download['filename'],
					'href'        => $this->url->link('catalog/case/download', 'token=' . $this->session->data['token'] . '&case_download_id=' . $case_download['case_download_id'], 'SSL'),
					'mask'        => $case_download['mask']
				);
		}
		
		// Images
		if (isset($this->request->post['case_image'])) {
			$case_images = $this->request->post['case_image'];
		} elseif (isset($this->request->get['case_id'])) {
			$case_images = $this->model_catalog_case->getCaseImages($this->request->get['case_id']);
		} else {
			$case_images = array();
		}

		$this->data['case_images'] = array();

		foreach ($case_images as $case_image) {
			if ($case_image['image'] && file_exists(DIR_IMAGE . $case_image['image'])) {
				$image = $case_image['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$this->data['case_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $case_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		
		//Labels
		$this->load->model('catalog/label');

		if (isset($this->request->post['case_label'])) {
			$case_labels = $this->request->post['case_label'];
		} elseif (isset($this->request->get['case_id'])) {
			$case_labels = $this->model_catalog_case->getCaseLabels($this->request->get['case_id']);
		} else {
			$case_labels = array();
		}

		$this->data['case_labels'] = array();

		foreach ($case_labels as $label_id) {
			$label_info = $this->model_catalog_label->getLabel($label_id);

			if ($label_info) {
				$this->data['case_labels'][] = array(
					'label_id' => $label_info['label_id'],
					'name'        => $label_info['name']
				);
			}
		}
		//KeyFigures
		$this->load->model('catalog/keyfigures');

		if (isset($this->request->post['case_keyfigures'])) {
			$case_keyfiguress = $this->request->post['case_keyfigures'];
		} elseif (isset($this->request->get['case_id'])) {
			$case_keyfiguress = $this->model_catalog_case->getCaseKeyFiguress($this->request->get['case_id']);
		} else {
			$case_keyfiguress = array();
		}
		$this->data['case_keyfiguress'] = array();

		foreach ($case_keyfiguress as $keyfigures_id) {
			$keyfigures_info = $this->model_catalog_keyfigures->getKeyFigures($keyfigures_id);
			
			if ($keyfigures_info) {
				$this->data['case_keyfiguress'][] = array(
					'keyfigures_id' => $keyfigures_info['keyfigures_id'],
					'name'        => $keyfigures_info['name']
				);
			}
		}

		if (isset($this->request->post['case_related'])) {
			$cases = $this->request->post['case_related'];
		} elseif (isset($this->request->get['case_id'])) {		
			$cases = $this->model_catalog_case->getCaseRelated($this->request->get['case_id']);
		} else {
			$cases = array();
		}

		$this->data['case_related'] = array();

		foreach ($cases as $case_id) {
			$related_info = $this->model_catalog_case->getCase($case_id);

			if ($related_info) {
				$this->data['case_related'][] = array(
					'case_id' => $related_info['case_id'],
					'name'       => $related_info['name']
				);
			}
		}

		//Tag related
		if (isset($this->request->post['case_tagrelated'])) {
			$tagcases = $this->request->post['case_tagrelated'];
		} elseif (isset($this->request->get['case_id'])) {		
			$tagcases = $this->model_catalog_case->getCaseRelatedByType($this->request->get['case_id'],1);
		} else {
			$tagcases = array();
		}

		$this->data['case_tagrelated'] = array();
		foreach ($tagcases as $tagcase) {
			$related_info = $this->model_catalog_case->getCase($tagcase['related_id']);
			if ($related_info) {
				$this->data['case_tagrelated'][] = array(
					'related_id' => $related_info['case_id'],
					'value' => $tagcase['value'],
					'status' => $tagcase['status'],
					'weight' => $tagcase['weight'],
					'name'       => $related_info['name']
				);
			}
		}
				
		//Figure related
		if (isset($this->request->post['case_figurerelated'])) {
			$figurecases = $this->request->post['case_figurerelated'];
		} elseif (isset($this->request->get['case_id'])) {		
			$figurecases = $this->model_catalog_case->getCaseRelatedByType($this->request->get['case_id'],2);
		} else {
			$figurecases = array();
		}

		$this->data['case_figurerelated'] = array();
		foreach ($figurecases as $figurecase) {
			$related_info = $this->model_catalog_case->getCase($figurecase['related_id']);
			if ($related_info) {
				$this->data['case_figurerelated'][] = array(
					'related_id' => $related_info['case_id'],
					'value' => $figurecase['value'],
					'status' => $figurecase['status'],
					'weight' => $figurecase['weight'],
					'name'       => $related_info['name']
				);
			}
		}
		
		//Spread related
		if (isset($this->request->post['case_spreadrelated'])) {
			$spreadcases = $this->request->post['case_spreadrelated'];
		} elseif (isset($this->request->get['case_id'])) {		
			$spreadcases = $this->model_catalog_case->getCaseRelatedByType($this->request->get['case_id'],3);
		} else {
			$spreadcases = array();
		}

		$this->data['case_spreadrelated'] = array();
		foreach ($spreadcases as $spreadcase) {
			$related_info = $this->model_catalog_case->getCase($spreadcase['related_id']);
			if ($related_info) {
				$this->data['case_spreadrelated'][] = array(
					'related_id' => $related_info['case_id'],
					'value' => $spreadcase['value'],
					'status' => $spreadcase['status'],
					'weight' => $spreadcase['weight'],
					'name'       => $related_info['name']
				);
			}
		}
		
		//Location related
		if (isset($this->request->post['case_locationrelated'])) {
			$locationcases = $this->request->post['case_locationrelated'];
		} elseif (isset($this->request->get['case_id'])) {		
			$locationcases = $this->model_catalog_case->getCaseRelatedByType($this->request->get['case_id'],4);
		} else {
			$locationcases = array();
		}

		$this->data['case_locationrelated'] = array();
		foreach ($locationcases as $locationcase) {
			$related_info = $this->model_catalog_case->getCase($locationcase['related_id']);
			if ($related_info) {
				$this->data['case_locationrelated'][] = array(
					'related_id' => $related_info['case_id'],
					'value' => $locationcase['value'],
					'status' => $locationcase['status'],
					'weight' => $locationcase['weight'],
					'name'       => $related_info['name']
				);
			}
		}
		
		//Time related
		if (isset($this->request->post['case_timerelated'])) {
			$timecases = $this->request->post['case_timerelated'];
		} elseif (isset($this->request->get['case_id'])) {		
			$timecases = $this->model_catalog_case->getCaseRelatedByType($this->request->get['case_id'],5);
		} else {
			$timecases = array();
		}

		$this->data['case_timerelated'] = array();
		foreach ($timecases as $timecase) {
			$related_info = $this->model_catalog_case->getCase($timecase['related_id']);
			if ($related_info) {
				$this->data['case_timerelated'][] = array(
					'related_id' => $related_info['case_id'],
					'value' => $timecase['value'],
					'status' => $timecase['status'],
					'weight' => $timecase['weight'],
					'name'       => $related_info['name']
				);
			}
		}
		
		//Total related
		if (isset($this->request->post['case_totalrelated'])) {
			$totalcases = $this->request->post['case_totalrelated'];
		} elseif (isset($this->request->get['case_id'])) {		
			$totalcases = $this->model_catalog_case->getCaseRelatedByTotal($this->request->get['case_id']);
		} else {
			$totalcases = array();
		}

		$this->data['case_totalrelated'] = array();
		foreach ($totalcases as $totalcase) {
			$related_info = $this->model_catalog_case->getCase($totalcase['related_id']);
			if ($related_info) {
				$this->data['case_totalrelated'][] = array(
					'related_id' => $related_info['case_id'],
					'weight' => $totalcase['weight']/$totalcase['count'],
					'name'       => $related_info['name']
				);
			}
		}
		
		if (isset($this->request->post['points'])) {
			$this->data['points'] = $this->request->post['points'];
		} elseif (!empty($case_info)) {
			$this->data['points'] = $case_info['points'];
		} else {
			$this->data['points'] = '';
		}

		if (isset($this->request->post['case_reward'])) {
			$this->data['case_reward'] = $this->request->post['case_reward'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_reward'] = $this->model_catalog_case->getCaseRewards($this->request->get['case_id']);
		} else {
			$this->data['case_reward'] = array();
		}

		if (isset($this->request->post['case_layout'])) {
			$this->data['case_layout'] = $this->request->post['case_layout'];
		} elseif (isset($this->request->get['case_id'])) {
			$this->data['case_layout'] = $this->model_catalog_case->getCaseLayouts($this->request->get['case_id']);
		} else {
			$this->data['case_layout'] = array();
		}

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'catalog/case_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function download() {

		$this->load->model('catalog/case');

		if (isset($this->request->get['case_download_id'])) {
			$case_download_id = $this->request->get['case_download_id'];
		} else {
			$case_download_id = 0;
		}

		$download_info = $this->model_catalog_case->getDownload($case_download_id);

		if ($download_info) {			
			$file = DIR_DOWNLOAD . iconv("UTF-8","gb2312",$download_info['filename']);
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
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
					$this->getForm();
		}
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/case')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['case_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/case')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/case')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/case');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			

			$data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_case->getCases($data);

			foreach ($results as $result) {
				$option_data = array();

				$case_options = $this->model_catalog_case->getCaseOptions($result['case_id']);	

				foreach ($case_options as $case_option) {
					$option_info = $this->model_catalog_option->getOption($case_option['option_id']);

					if ($option_info) {				
						if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
							$option_value_data = array();

							foreach ($case_option['case_option_value'] as $case_option_value) {
								$option_value_info = $this->model_catalog_option->getOptionValue($case_option_value['option_value_id']);

								if ($option_value_info) {
									$option_value_data[] = array(
										'case_option_value_id' => $case_option_value['case_option_value_id'],
										'option_value_id'         => $case_option_value['option_value_id'],
										'name'                    => $option_value_info['name'],
										'price'                   => (float)$case_option_value['price'] ? $this->currency->format($case_option_value['price'], $this->config->get('config_currency')) : false,
										'price_prefix'            => $case_option_value['price_prefix']
									);
								}
							}

							$option_data[] = array(
								'case_option_id' => $case_option['case_option_id'],
								'option_id'         => $case_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $option_value_data,
								'required'          => $case_option['required']
							);	
						} else {
							$option_data[] = array(
								'case_option_id' => $case_option['case_option_id'],
								'option_id'         => $case_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $case_option['option_value'],
								'required'          => $case_option['required']
							);				
						}
					}
				}

				$json[] = array(
					'case_id' => $result['case_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),	
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>