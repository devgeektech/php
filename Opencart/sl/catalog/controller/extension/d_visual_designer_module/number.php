<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleNumber extends Controller
{
    private $codename = 'number';
    private $route = 'extension/d_visual_designer_module/number';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    public function scripts($permission) {
        $data = array();
        $data[] = 'catalog/view/javascript/d_visual_designer_module/library/jquery.spincrement.min.js';
        return $data;
    }
}
