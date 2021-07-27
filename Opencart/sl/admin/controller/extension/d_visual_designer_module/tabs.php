<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTabs extends Controller
{
    private $codename = 'tabs';
    private $route = 'extension/d_visual_designer_module/tabs';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function local()
    {
        $data['entry_active_section'] = $this->language->get('entry_active_section');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['button_add'] = $this->language->get('button_add');

        return $data;
    }

    public function options()
    {
        $data['aligns'] = array(
            'left' => $this->language->get('text_left'),
            'center' => $this->language->get('text_center'),
            'right' => $this->language->get('text_right'),
        );

        return $data;
    }
}
