<?php
class ControllerExtensionDSEOModuleAdviserDVisualDesignerLanding extends Controller {
	private $codename = 'd_visual_designer_landing';
	private $route = 'extension/d_seo_module_adviser/d_visual_designer_landing';
	private $config_file = 'd_visual_designer_landing';
	private $error = array(); 
		
	/*
	*	Functions for SEO Module Adviser.
	*/
	public function adviser_elements($route) {			
        $this->load->model($this->route);
        
		return $this->{'model_extension_d_seo_module_adviser_' . $this->codename}->getAdviserElements($route);
	}
}