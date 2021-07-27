<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleEmptySpace extends Controller
{
    private $codename = 'empty_space';
    private $route = 'extension/d_visual_designer_module/empty_space';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }

    public function local()
    {
        $data = array();

        $data['entry_height'] = $this->language->get('entry_height');

        return $data;
    }

    public function options()
    {
        $data = array();

        $data['types'] = array(
            'standard' => $this->language->get('text_standard'),
            'box_count' => $this->language->get('text_box_count'),
            'button_count' => $this->language->get('text_button_count'),
        );

        return $data;
    }
}
