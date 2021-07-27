<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleGMaps extends Controller
{
    private $codename = 'gmaps';
    private $route = 'extension/d_visual_designer_module/gmaps';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_height'] = $this->language->get('entry_height');

        return $data;
    }
}
