<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleVideo extends Controller
{
    private $codename = 'video';
    private $route = 'extension/d_visual_designer_module/video';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }
    public function index($setting)
    {
        
        $data['link'] = str_replace("watch?v=", "embed/", $setting['link']);
        
        $data['link'] = str_replace("vimeo.com", "player.vimeo.com/video", $setting['link']);
        
        return $data;
    }
}
