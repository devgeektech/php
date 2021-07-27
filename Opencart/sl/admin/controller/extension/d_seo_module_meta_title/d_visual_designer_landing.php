<?php
class ControllerExtensionDSEOModuleMetaTitleDVisualDesignerLanding extends Controller {
	private $codename = 'd_visual_designer_landing';
	private $route = 'extension/d_seo_module_meta_title/d_visual_designer_landing';
	private $config_file = 'd_visual_designer_landing';
	private $error = array();
	
	/*
	*	Functions for SEO Module Meta Title.
	*/	
	public function edit_meta_element($meta_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_meta_title_' . $this->codename}->editMetaElement($meta_element_data);
	}
	
	public function meta_elements() {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_meta_title_' . $this->codename}->getMetaElements();
	}
		
	public function store_meta_elements_links($store_meta_elements) {	
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		foreach ($store_meta_elements as $store_id => $meta_elements) {
			foreach ($meta_elements as $meta_element_key => $meta_element) {
				if (strpos($meta_element['route'], 'vdl_page_id') === 0) {
					$route_arr = explode("vdl_page_id=", $meta_element['route']);
				
					if (isset($route_arr[1])) {
						$page_id = $route_arr[1];
						$store_meta_elements[$store_id][$meta_element_key]['link'] = $this->url->link('extension/module/d_visual_designer_landing/edit', $url_token . '&page_id=' . $page_id, true);
					}
				}
			}
		}
		
		return $store_meta_elements;
	}
}
