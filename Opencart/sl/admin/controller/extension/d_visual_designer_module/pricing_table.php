<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModulePricingTable extends Controller
{
    private $codename = 'pricing_table';
    private $route = 'extension/d_visual_designer_module/pricing_table';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    public function local()
    {
        $data['entry_color_text'] = $this->language->get('entry_color_text');
        $data['entry_width_between'] = $this->language->get('entry_width_between');

        return $data;
    }
    public function catalog_styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_landing/pricing_table/pricing_table.css';

        return $data;
    }
}
