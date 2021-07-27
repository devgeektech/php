<?php
set_time_limit(0);
ini_set('memory_limit','999M');
require_once(DIR_SYSTEM.'/library/PHPExcel.php');
class ControllerExtensionModuleCustomerGroupPrice extends Controller {
	private $error = array();
public function install()
	{
	$this->load->model('extension/customergroupprice');
	$this->model_extension_customergroupprice->install();
	}	
	public function uninstall()
	{
	$this->load->model('extension/customergroupprice');
	$this->model_extension_customergroupprice->uninstall();
	}
	public function index() {

		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		= 'user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}
		$this->load->language('extension/module/customergroupprice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
	if (version_compare(VERSION, '3.0.0.0', '>=')) {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if(isset($this->request->post['customergroupprice_status'])) {

				$status=$this->request->post['customergroupprice_status'];
			}
			
			$postdata['module_customergroupprice_status']=$status;

			$this->model_setting_setting->editSetting('module_customergroupprice',$postdata);
			
			$this->model_setting_setting->editSetting('customergroupprice', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', $tokenexchange . '&type=module', true));
		}
	}else{
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('customergroupprice', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}
	}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_pluspercent'] = $this->language->get('text_pluspercent');
		$data['text_minuspercent'] = $this->language->get('text_minuspercent');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_importby'] = $this->language->get('entry_importby');
		$data['entry_import'] = $this->language->get('entry_import');
		$data['entry_export'] = $this->language->get('entry_export');
		$data['entry_prefix'] = $this->language->get('entry_prefix');
		$data['entry_value'] = $this->language->get('entry_value');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_rule'] = $this->language->get('entry_rule');
		$data['export_product_id'] = $this->language->get('export_product_id');
		$data['export_model'] = $this->language->get('export_model');
		$data['export_sku'] = $this->language->get('export_sku');
		$data['export_customer_group'] = $this->language->get('export_customer_group');
		$data['export_price'] = $this->language->get('export_price');
		$data['column_product_id'] = $this->language->get('column_product_id');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_sku'] = $this->language->get('column_sku');
		$data['column_customer_group'] = $this->language->get('column_customer_group');
		$data['column_price'] = $this->language->get('column_price');
		$data['entry_product_id'] = $this->language->get('entry_product_id');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_sku'] = $this->language->get('entry_sku');

		$this->load->model('customer/customer_group');
		$data['customer_group_infos'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['error_empty'] = $this->language->get('error_empty');
		
		if (isset($this->session->data['error'])) {
    		$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
 		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
    		$data['error_success'] = $this->session->data['success'];
			unset($this->session->data['success']);
 		}else {
			$data['error_success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenexchange, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', $tokenexchange . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/customergroupprice', $tokenexchange, true)
		);

		$data['action'] = $this->url->link('extension/module/customergroupprice', $tokenexchange, true);

		$data['export'] = $this->url->link('extension/module/customergroupprice/export', $tokenexchange, true);
		$data['import'] = $this->url->link('extension/module/customergroupprice/import', $tokenexchange, true);

		$data['cancel'] = $this->url->link('marketplace/extension', $tokenexchange . '&type=module', true);

		if (isset($this->request->post['customergroupprice_status'])) {
			$data['customergroupprice_status'] = $this->request->post['customergroupprice_status'];
		} else {
			$data['customergroupprice_status'] = $this->config->get('customergroupprice_status');
		}

		if (isset($this->request->post['customergroupprice_importby'])) {
			$data['customergroupprice_importby'] = $this->request->post['customergroupprice_importby'];
		} else {
			$data['customergroupprice_importby'] = $this->config->get('customergroupprice_importby');
		}

		if (isset($this->request->post['customergroupprice_exportfilename'])) {
			$data['customergroupprice_exportfilename'] = $this->request->post['customergroupprice_exportfilename'];
		} else {
			$data['customergroupprice_exportfilename'] = $this->config->get('customergroupprice_exportfilename');
		}

		$customergroupprice_data = $this->config->get('customergroupprice_data');
		if (isset($this->request->post['customergroupprice_data'])) {
			$data['customergroupprice_data'] = $this->request->post['customergroupprice_data'];
		} else if ($customergroupprice_data) {
			$data['customergroupprice_data'] = $this->config->get('customergroupprice_data');
		} else {
			$data['customergroupprice_data'] = array();
		}
/*echo "<pre>";
print_r($data['customergroupprice_data'];die();*/
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/customergroupprice', $data));
	}

	public function export(){
		$this->load->model('catalog/product');
		$this->load->model('catalog/option');
		$this->load->model('customer/customer_group');
		$this->load->model('extension/customergroupprice');

		$data['products'] = array();
	
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Category Product");
		$objPHPExcel->getProperties()->setLastModifiedBy("Category Product");
		$objPHPExcel->getProperties()->setTitle("Office Excel");
		$objPHPExcel->getProperties()->setSubject("Office Excel");
		$objPHPExcel->getProperties()->setDescription("Office Excel");
		$objPHPExcel->setActiveSheetIndex(0);
						$i=1;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, 'Product ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, 'Model');
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, 'SKU');
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, 'Option name');
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, 'Option Value');
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, 'Group Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, 'Group Price');
		//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, 'Special Price');
					  
		$results = $this->model_catalog_product->getProducts();
		foreach($results as $result) {
			$option_data = array();

			$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

			foreach ($product_options as $product_option) {
				$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

				if ($option_info) {
					$product_option_value_data = array();
					$opt_group_data = array();

					foreach ($product_option['product_option_value'] as $product_option_value) {
						$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

						/// product option group Price ////
						$opt_group_info = $this->model_extension_customergroupprice->getProductOptionGroup($product_option_value['product_option_value_id']);
						foreach ($opt_group_info as $optgroup) {
							$opt_group_info = $this->model_customer_customer_group->getCustomerGroup($optgroup['customer_group_id']);
							if ($opt_group_info) {
								$opt_group_data[] = array(
									'option_value'            => $option_value_info['name'],
									'name'                    => $opt_group_info['name'],
									'price'                   => $this->currency->format($optgroup['price'], $this->config->get('config_currency')),
									'price'                   => $optgroup['price'],
								);
							}
						}
						/// product option group Price ////
					}
					$option_data[] = array(
						'opt_group_data' 	=> $opt_group_data,
						'name'              => $option_info['name'],
					);
				}
			}

			/// product special customer group price /////
			/*$special_data = array();
			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);
			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $product_special['price'];
					$group_info = $this->model_customer_customer_group->getCustomerGroup($product_special['customer_group_id']);
					$special_data[] = array(
						'name'                    => $group_info['name'],
						'price'                   => $product_special['price'],
					);
				}
			}

			if(isset($special_data)) {
				foreach($special_data as $special ){
					if(!empty($special['name'])) {
						$i++;
						$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $result['product_id']);
						$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $result['model']);
						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $result['sku']);
						$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,'');
						$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,$special['name']);
						$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,'');
						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,$special['price']);
					}
				}
			}*/
			/// product special customer group price /////

			//// product customer group price ////
			$customergroupprice = $this->model_catalog_product->getCustomerPrice($result['product_id']);
			$group_data = array();
			foreach ($customergroupprice as $key => $cus_price) {
				$group_info = $this->model_customer_customer_group->getCustomerGroup($key);
				if ($group_info) {
					$groupprice = $this->currency->format($cus_price['price'], $this->config->get('config_currency'));
					$group_data[] = array(
						'name'                 => $group_info['name'],
						'groupprice'           => $cus_price['price'],
					);
				}
			}
			if(isset($group_data)) {
				foreach($group_data as $group ){
					if(!empty($group['name'])) {
						$i++;
						$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $result['product_id']);
						$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $result['model']);
						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $result['sku']);
						$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,'');
						$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,$group['name'] );
						$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,$group['groupprice'] );
						//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,'');
					}
				}
			}
			//// product customer group price ////

			if(isset($option_data)) {
				foreach($option_data as $option ){
					foreach($option['opt_group_data'] as $opt_group) {
						if(!empty($opt_group['name'])) {
							$i++;
							$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $result['product_id']);
							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $result['model']);
							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $result['sku']);
							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $option['name']);
							$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,$opt_group['option_value']);
							$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i,$opt_group['name']);
							$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,$opt_group['price']);
						}
					}
				}
			}
		}
		$tmdfilename = $this->config->get('customergroupprice_exportfilename');
		if (!empty($tmdfilename)) {
			$filename = $tmdfilename.'.xls';
		}else{
			$filename = 'TmdExport.xls';
		}
		$objPHPExcel->getActiveSheet()->setTitle('Tmd Customer Group');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($filename );
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		$objWriter->save('php://output');
		unlink($filename);
	}

	public function import(){
		

		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		= 'user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		$this->load->model('catalog/option');
		$this->load->model('extension/customergroupprice');
		$this->load->language('extension/module/customergroupprice');
		//print_r($this->request->post['customergroupprice_importby']);die();
			if (is_uploaded_file($this->request->files['import']['tmp_name'])) {
				$content = file_get_contents($this->request->files['import']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content) {
				////////////////////////// Started Import work  //////////////
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($this->request->files['import']['tmp_name']);
				} catch(Exception $e) {
					die('Error loading file "'.pathinfo($this->path.$files,PATHINFO_BASENAME).'": '.$e->getMessage());
				}
				/*	@ get a file data into $sheetDatas variable */
				$sheetDatas = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				/*	@ $i variable for getting data. in first iteration of loop we get size and color name of product */
				$i=0;
				/*
				@ arranging the data according to our need
				*/
				foreach($sheetDatas as $sheetData){
					if($i!=0) {
						$product_id 	= $sheetData['A'];
						$model  		= $sheetData['B'];
						$sku 			= $sheetData['C'];
						$option_name 	= $sheetData['D'];
						$option_value 	= $sheetData['E'];
						$group_name 	= $sheetData['F'];
						$group_price 	= $sheetData['G'];

						//$special_price 	= $sheetData['H'];
						$customergroupprice_importby = $this->request->post['customergroupprice_importby'];
						//print_r($customergroupprice_importby);die();
						if($customergroupprice_importby == 'model' && !empty($model)){
							$product_id=$this->model_extension_customergroupprice->getproductbymodel($model);
						}else if($customergroupprice_importby == 'sku' && !empty($sku)){
							$product_id=$this->model_extension_customergroupprice->getproductbysku($sku);
						}					
						if(isset($product_id)) {
							/*if(!empty($special_price)) {
								$senspecialdata=array(
									'product_id'=>$product_id,
									'group_name'=>$group_name,
									'special_price'=>$special_price,
								);
								$this->model_extension_customergroupprice->updateCustomerSpecialPrice($senspecialdata);
							}else */	
							if(!empty($option_value)) {
								$senoptdata=array(
									'product_id'=>$product_id,
									'option_name'=>$option_name,
									'option_value'=>$option_value,
									'group_name'=>$group_name,
									'group_price'=>$group_price,
								);
								$this->model_extension_customergroupprice->updateCustomerProOpt($senoptdata);
							}else{
								$sendata=array(
									'product_id'=>$product_id,
									'group_name'=>$group_name,
									'price'=>$group_price,
								);
								$this->model_extension_customergroupprice->updateProduct($sendata);
							}
						}
					}
					$i++;
				}
				$this->session->data['success'] = $this->language->get('error_sucess');
			} else {
				$this->session->data['error'] = $this->language->get('error_empty');
			}
		
		$url = '';

		$this->response->redirect($this->url->link('extension/module/customergroupprice', $tokenexchange.$url, 'SSL'));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/customergroupprice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
