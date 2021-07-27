<?php
/*
*    location: admin/controller
*/

class ControllerExtensionDVisualDesignerModuleCustomHeading extends Controller
{
    private $codename = 'custom_heading';
    private $route = 'extension/d_visual_designer_module/custom_heading';
    
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function index($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        
        return $data;
    }
    
    public function styles($permission) {
        $data = array();
        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/custom_heading.css';
        return $data;
    }
}
