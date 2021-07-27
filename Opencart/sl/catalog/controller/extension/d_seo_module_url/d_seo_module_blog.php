<?php
class ControllerExtensionDSEOModuleURLDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_url/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array(); 
		
	/*
	*	Functions for SEO Module URL.
	*/	
	public function custom_page_exception_routes() {	
		$_config = new Config();
		$_config->load($this->config_file);
		$config_setting = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();
				
		return $config_setting['custom_page_exception_routes'];
	}
}