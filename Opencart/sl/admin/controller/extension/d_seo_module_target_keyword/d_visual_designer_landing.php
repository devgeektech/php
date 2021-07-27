<?php
class ControllerExtensionDSEOModuleTargetKeywordDVisualDesignerLanding extends Controller {
	private $codename = 'd_visual_designer_landing';
	private $route = 'extension/d_seo_module_target_keyword/d_visual_designer_landing';
	private $config_file = 'd_visual_designer_landing';
	private $error = array();
	
	/*
	*	Functions for SEO Module Target Keyword.
	*/	
	public function edit_target_element($target_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_target_keyword_' . $this->codename}->editTargetElement($target_element_data);
	}
	
	public function target_elements() {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_target_keyword_' . $this->codename}->getTargetElements();
	}
	
	public function store_target_elements_links($store_target_elements) {	
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		foreach ($store_target_elements as $store_id => $target_elements) {
			foreach ($target_elements as $target_element_key => $target_element) {
				if (strpos($target_element['route'], 'vdl_page_id') === 0) {
					$route_arr = explode("vdl_page_id=", $target_element['route']);
				
					if (isset($route_arr[1])) {
						$page_id = $route_arr[1];
						$store_target_elements[$store_id][$target_element_key]['link'] = $this->url->link('extension/module/d_visual_designer_landing/edit', $url_token . '&page_id=' . $page_id, true);
					}
				}
			}
		}
		
		return $store_target_elements;
	}
}
