<?php 
class ControllerToolBackup extends Controller { 
	private $error = array();

	public function index() {		
		$this->language->load('tool/backup');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/backup');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/backup')) {
			if (is_uploaded_file($this->request->files['import']['tmp_name'])) {
				$content = file_get_contents($this->request->files['import']['tmp_name']);
				file_put_contents('case.xlsx',$content);
				$filePath = 'case.xlsx'; 
			} else {
				$content = false;
			}

			if ($content) {
				require_once(DIR_SYSTEM . 'library/PHPExcel.php');
				//read excel file
				$PHPExcel = new PHPExcel(); 
				/**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/ 
				$PHPReader = new PHPExcel_Reader_Excel2007(); 
				if(!$PHPReader->canRead($filePath)){ 
					$PHPReader = new PHPExcel_Reader_Excel5(); 
					if(!$PHPReader->canRead($filePath)){} 
				}else{
					$this->load->model('catalog/case');
					$this->load->model('catalog/contentcolumn');
					$this->load->model('catalog/contenttype');
					$this->load->model('catalog/manufacturer');
					$this->load->model('catalog/label');
					$PHPExcel = $PHPReader->load($filePath); 
					/**读取excel文件中的第一个工作表*/ 
					$sheetCount = $PHPExcel->getSheetCount();
					for($currentSheetIndex = 0;$currentSheetIndex < $sheetCount;$currentSheetIndex++){ 
						$currentSheet = $PHPExcel->getSheet($currentSheetIndex); 
						$sheetTitle = $PHPExcel->getSheet($currentSheetIndex)->getTitle(); 
						/**取得最大的列号*/ 
						$allColumn = $currentSheet->getHighestColumn();
						/**取得一共有多少行*/ 
						$allRow = $currentSheet->getHighestRow(); 
						/**从第二行开始输出，因为excel表中第一行为列名*/ 
						$data['case_description'][1]['name']=$sheetTitle;
						$start = 9999999;
						$end = 0;
						$data['model']='------'; 
						$data['status']=1;
						$temp_count = 0;
						$excerpt = '';
						$excerpt_count = 0;
						for($currentRow = 2;$currentRow <= $allRow;$currentRow++){		
							if(trim($currentSheet->getCellByColumnAndRow(ord('A') - 65,$currentRow)->getValue())!=''){
							/**从第A列开始输出*/	
								for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){ 
									$val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/ 
									$val = trim($val);
									if($currentColumn == 'A'){ 
										$data['case_content'][$currentRow-2]['title']=$val; 
									}else if($currentColumn == 'B') { 
										$data['case_content'][$currentRow-2]['url']=$val; 
									}else if($currentColumn == 'C') { 
										$data['case_content'][$currentRow-2]['content']=$val; 
									}else if($currentColumn == 'D') { 
										$val = str_replace("  "," ",$val);
										$val = str_replace(" ",", ",$val);
										$data['case_content'][$currentRow-2]['keyword']=$val; 
									}else if($currentColumn == 'E') {
										if($val<$start){
											$start = $val;
										}
										if($val>$end){
											$end = $val;
										}
										$val = intval((($val - 25569) * 3600 * 24)-3600);
										$val = date('Y-m-d H:i:s', $val);
										$data['case_content'][$currentRow-2]['date_file']=$val; 
									}else if($currentColumn == 'F') { 
										if($val==''){
											$data['case_content'][$currentRow-2]['contenttype_id'] = 0;
										}else{
											$contenttype_id = $this->model_catalog_contenttype->addConditionContenttype($val);
											$data['case_content'][$currentRow-2]['contenttype_id'] = $contenttype_id;
										}
									}else if($currentColumn == 'G') { 
										if($val==''){
											$data['case_content'][$currentRow-2]['contentcolumn_id'] = 0;
										}else{
											$val = substr($val,strpos($val,'：'));
											$contentcolumn_id = $this->model_catalog_contentcolumn->addConditionContentcolumn(str_replace("：","",$val));
											$data['case_content'][$currentRow-2]['contentcolumn_id'] = $contentcolumn_id;
										}
									} 
								}
							}else{
								if(trim($currentSheet->getCellByColumnAndRow(ord('C') - 65,$currentRow)->getValue())!=''){
									$excerpt = $excerpt.trim($currentSheet->getCellByColumnAndRow(ord('C') - 65,$currentRow)->getValue())."\n";
								}
							}												
							if(trim($currentSheet->getCellByColumnAndRow(ord('A') - 65,$currentRow)->getValue())==''&&trim($currentSheet->getCellByColumnAndRow(ord('C') - 65,$currentRow)->getValue())==''){
								$temp_count ++;
								if($temp_count>4){
									break;
								}else if($temp_count==2){
									if($excerpt!=''){
									$data['case_excerpt'][$excerpt_count]['content']=$excerpt; 
									$excerpt_count++;
									}
									$excerpt = '';
								}
							}else{
								if($currentRow==$allRow){	
									if($excerpt!=''){
									$data['case_excerpt'][$excerpt_count]['content']=$excerpt; 
									$excerpt_count++;
									}
									$excerpt = '';
								}
								$temp_count = 0;
							}
						}
						$start = intval((($start - 25569) * 3600 * 24)-3600);
						$start = date('Y-m-d H:i:s', $start);
						$data['date_available']=$start; 
						$end = intval((($end - 25569) * 3600 * 24)-3600);
						$end = date('Y-m-d H:i:s', $end);
						$data['date_unavailable']=$end; 
						if($this->model_catalog_case->getTotalCasesByName($data['case_description'][1]['name'])<1){
							$this->model_catalog_case->addCase($data);
						}					
					}
				}
				//$this->model_tool_backup->restore($content);

				$this->session->data['success'] = $this->language->get('text_success');

				$this->redirect($this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
			$this->error['warning'] = $this->language->get('error_empty');
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$this->data['entry_restore'] = $this->language->get('entry_restore');
		$this->data['entry_backup'] = $this->language->get('entry_backup');

		$this->data['button_backup'] = $this->language->get('button_backup');
		$this->data['button_restore'] = $this->language->get('button_restore');

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['restore'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['backup'] = $this->url->link('tool/backup/backup', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['tables'] = $this->model_tool_backup->getTables();

		$this->template = 'tool/backup.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function backup() {
		$this->language->load('tool/backup');

		if (!isset($this->request->post['backup'])) {
			$this->session->data['error'] = $this->language->get('error_backup');

			$this->redirect($this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL'));
		} elseif ($this->user->hasPermission('modify', 'tool/backup')) {
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . date('Y-m-d_H-i-s', time()).'_backup.sql');
			$this->response->addheader('Content-Transfer-Encoding: binary');

			$this->load->model('tool/backup');

			$this->response->setOutput($this->model_tool_backup->backup($this->request->post['backup']));
		} else {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->redirect($this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL'));			
		}
	}
}
?>