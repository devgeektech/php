<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleSectionAccordion extends Controller
{
    private $codename = 'section_accordion';
    private $route = 'extension/d_visual_designer_module/section_accordion';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function local()
    {
        $data['entry_section_id'] = $this->language->get('entry_section_id');
        $data['entry_title'] = $this->language->get('entry_title');
        
        $data['button_add'] = $this->language->get('button_add');

        return $data;
    }
}
