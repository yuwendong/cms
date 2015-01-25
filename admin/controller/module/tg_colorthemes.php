<?php
class ControllerModuletgcolorthemes extends Controller {
	private $error = array(); 
	public function index() {   
		$this->load->language('module/tg_colorthemes');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('tool/image');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			
			$this->model_setting_setting->editSetting('tg_colorthemes', $this->request->post);								
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
	
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_color'] = $this->language->get('entry_color');
		$this->data['entry_view'] = $this->language->get('entry_view');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['button_save']          = $this->language->get('button_save');
		$this->data['button_cancel']        = $this->language->get('button_cancel');
        $this->data['button_addslide']      = $this->language->get('button_addslide');
        $this->data['button_remove']        = $this->language->get('button_remove');
        

		$this->data['tab_gen']              =  $this->language->get('tab_gen');

		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_image_manager'] =  $this->language->get('text_image_manager');
		$this->data['image_instruction'] =  $this->language->get('image_instruction');
		

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
				

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/tg_colorthemes', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/tg_colorthemes', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		
		

		$this->data['positions'] = array();
		
		$this->data['positions'][] = array(
			'position' => 'footer',
			'title'    => $this->language->get('text_footer'),
		);
		
		if (isset($this->request->post['tg_colorthemes_position'])) {
			$this->data['tg_colorthemes_position'] = $this->request->post['tg_colorthemes_position'];
		} else {
			$this->data['tg_colorthemes_position'] = $this->config->get('tg_colorthemes_position');
		}
		
		if (isset($this->request->post['tg_colorthemes_status'])) {
			$this->data['tg_colorthemes_status'] = $this->request->post['tg_colorthemes_status'];
		} else {
			$this->data['tg_colorthemes_status'] = $this->config->get('tg_colorthemes_status');
		}
		
		if (isset($this->request->post['tg_colorthemes_default_color'])) {
			$this->data['tg_colorthemes_default_color'] = $this->request->post['tg_colorthemes_default_color'];
		} else {
			$this->data['tg_colorthemes_default_color'] = $this->config->get('tg_colorthemes_default_color');
		}
		      
			
		$this->template = 'module/tg_colorthemes.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/tg_colorthemes')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	
	
	
}
?>
