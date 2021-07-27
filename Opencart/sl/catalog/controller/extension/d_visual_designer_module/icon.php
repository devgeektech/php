<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleIcon extends Controller
{
    private $codename = 'icon';
    private $route = 'extension/d_visual_designer_module/icon';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
    }
    
    public function styles($permission) {
        $data = array();
        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/icon.css';
        return $data;
    }
}
