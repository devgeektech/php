<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTextSeparater extends Controller
{
    private $codename = 'text_separater';
    private $route = 'extension/d_visual_designer_module/text_separater';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/text_separater.css';

        return $data;
    }
}
