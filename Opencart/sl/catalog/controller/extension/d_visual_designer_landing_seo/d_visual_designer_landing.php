<?php
class ControllerExtensionDVisualDesignerLandingSEODVisualDesignerLanding extends Controller {
	private $codename = 'd_visual_designer_landing';
	private $route = 'extension/d_visual_designer_landing_seo/d_visual_designer_landing';
	private $config_file = 'd_visual_designer_landing';
	
	public function page_get_page($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		// Setting
		$status = ($this->config->get($this->codename . '_status')) ? $this->config->get($this->codename . '_status') : false;
		$setting = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();
		
		if ($status && $data) {			
			$route = 'vdl_page_id=' . (int)$data['page_id'];
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
			
				if ($meta_info['title']) {
					$data['title'] = $meta_info['title'];
				}

				if ($meta_info['meta_title']) {
					$data['meta_title'] = $meta_info['meta_title'];
				}
			
				if ($meta_info['meta_description']) {
					$data['meta_description'] = $meta_info['meta_description'];
				}
			
				if ($meta_info['meta_keyword']) {
					$data['meta_keyword'] = $meta_info['meta_keyword'];
				}
			}
		}
		
		return $data;
	}
}