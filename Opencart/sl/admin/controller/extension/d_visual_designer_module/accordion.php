<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleAccordion extends Controller
{
    private $codename = 'accordion';
    private $route = 'extension/d_visual_designer_module/accordion';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
    }

    public function local()
    {
        $data = array();

        $data['button_add'] = $this->language->get('button_add');

        $data['entry_active_section'] = $this->language->get('entry_active_section');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_title'] = $this->language->get('entry_title');


        return $data;
    }
}
