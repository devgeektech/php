<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModulePopup extends Controller
{
    private $codename = 'popup';
    private $route = 'extension/d_visual_designer_module/popup';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    

    public function options()
    {
        $data['corners'] = array(
            '' => $this->language->get('text_none'),
            '*' => $this->language->get('text_corner_all'),
            'top' => $this->language->get('text_corner_top'),
            'right' => $this->language->get('text_corner_right'),
            'bottom' => $this->language->get('text_corner_bootom'),
            'left' => $this->language->get('text_corner_left'),
            );
        return $data;
    }

    public function local()
    {
        $data['entry_active_corner'] = $this->language->get('entry_active_corner');
        $data['entry_click_trigger'] = $this->language->get('entry_click_trigger');
        $data['entry_selector'] = $this->language->get('entry_selector');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        return $data;
    }
}
