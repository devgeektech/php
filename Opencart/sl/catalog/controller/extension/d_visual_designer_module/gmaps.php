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
    }
    
    public function index($setting)
    {
        $data['link'] = html_entity_decode($setting['link'], ENT_QUOTES, 'UTF-8');

        return $data;
    }

}
