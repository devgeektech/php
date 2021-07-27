<?php

/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModulePricingTableSection extends Controller
{
    private $codename = 'pricing_table_section';
    private $route = 'extension/d_visual_designer_module/pricing_table_section';
    
    public function styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_landing/pricing_table/pricing_table.css';

        return $data;
    }
}
