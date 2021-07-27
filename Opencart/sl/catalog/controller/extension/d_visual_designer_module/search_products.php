<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleSearchProducts extends Controller
{
    private $codename = 'search_products';
    private $route = 'extension/d_visual_designer_module/search_products';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
    }

    public function local($permission)
    {
        $data['text_search'] = $this->language->get('text_search');

        return $data;
    }
}
