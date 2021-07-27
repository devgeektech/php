<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleCode extends Controller
{
    private $codename = 'code';
    private $route = 'extension/d_visual_designer_module/code';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_visual_designer/designer');
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function index($setting)
    {
        $data['code'] = html_entity_decode(htmlspecialchars_decode($setting['code']), ENT_QUOTES, 'UTF-8');
    
        return $data;
    }
    
    public function setting($setting)
    {
        $data['code'] = html_entity_decode(htmlspecialchars_decode($setting['code']), ENT_QUOTES, 'UTF-8');
        
        return $data;
    }

    public function local()
    {
        $data['entry_code'] = $this->language->get('entry_code');

        return $data;
    }
}
