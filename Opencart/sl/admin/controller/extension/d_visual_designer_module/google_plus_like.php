<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleGooglePlusLike extends Controller
{
    private $codename = 'google_plus_like';
    private $route = 'extension/d_visual_designer_module/google_plus_like';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_visual_designer/designer');
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function index($setting)
    {   
        $data['size'] = $this->language->get('text_'.$setting['size']);
        
        return $data;
    }

    public function local()
    {
        $data['entry_size'] = $this->language->get('entry_size');

        return $data;
    }

    public function options()
    {
        $data['sizes'] = array(
            'small' => $this->language->get('text_small'),
            'medium' => $this->language->get('text_medium'),
            'standard' => $this->language->get('text_standard'),
            'tall' => $this->language->get('text_tall'),
        );

        return $data;
    }
}
