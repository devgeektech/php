<?php
class ControllerAjaxModuleAjaxmodule extends Controller {
	public function index() {
		$json = array();
		
		$this->load->model('setting/setting');
		$module_status = $this->model_setting_setting->getSettingValue('module_ajaxmodule_status');
		
		if(!empty($module_status) && $module_status > 0) {
			
			if (isset($this->request->get['module'])) {
				$module_name = $this->request->get['module'];
			} else {
				$module_name = '';
			}
			if (isset($this->request->get['code'])) {
				$module_code = $this->request->get['code'];
			} else {
				$module_code = '';
			}
			if (isset($this->request->get['view'])) {
				$module_view = $this->request->get['view'];
			} else {
				$module_view = '';
			}
			
			$modnames = [];
			if (strpos($module_name, ',') !== false) {
				$modnames = explode(",", $module_name);
			} else {
				$modnames[] =  $module_name;
			}
			
			$modcodes = [];
			if (strpos($module_code, ',') !== false) {
				$modcodes = explode(",", $module_code);
			} else {
				$modcodes[] =  $module_code;
			}
			
			$modviews = [];
			if (strpos($module_view, ',') !== false) {
				$modviews = explode(",", $module_view);
			} else {
				$modviews[] =  $module_view;
			}
			
			//echo '<pre>';print_r($modnames);print_r($modcodes);print_r($modviews);die();
			$mview = 0;
			$json = [];
			if(!empty($modnames)) { 
				
				$this->load->model('ajaxmodule/ajaxmodule');
				
				foreach($modnames as $key => $name) {
					
					$mname = trim($name);
					
					if(isset($modcodes[$key]))
						$mcode = trim($modcodes[$key]);
					else
						$mcode = '';

					if(isset($modviews[$key]))
						$mview = trim($modviews[$key]);
					else
						$mview = 0;
					
					$module_setting = $this->model_ajaxmodule_ajaxmodule->getModule($mname, $mcode);
					//echo '<pre>';print_r($module_setting);
					if(isset($module_setting['code']) && isset($module_setting['setting_info'])) { 
					
						if($module_setting['setting_info']['status'] == 1) {

							$result = $this->load->controller('extension/module/' . $module_setting['code'], $module_setting['setting_info']);
							
							if($result !== null) {
								$json[] = array(
									"mdata" => $result,
									"mview" => $mview
								);
							} else {
							$json[] = array(
								"mdata" => 'No Result Found!',
								"mview" => $mview
							);
						}
						} else {
							$json[] = array(
								"mdata" => 'No Result Found!',
								"mview" => $mview
							);
						}
						
					} else {
						$json[] = array(
								"mdata" => 'No Result Found!',
								"mview" => $mview
							);
					}
					
				} //die();
			
			}  else {
				$json[] = array(
					"mdata" => 'No Result Found!',
					"mview" => $mview
				);
			}
		} else {
			$json[] = array(
				"mdata" => 'Something wrong with data!',
				"mview" => $mview
			);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
