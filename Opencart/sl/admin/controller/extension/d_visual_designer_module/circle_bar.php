<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleCircleBar extends Controller
{
    private $codename = 'circle_bar';
    private $route = 'extension/d_visual_designer_module/circle_bar';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_value'] = $this->language->get('entry_value');
        $data['entry_units'] = $this->language->get('entry_units');
        $data['entry_label_value'] = $this->language->get('entry_label_value');
        $data['entry_bar_color'] = $this->language->get('entry_bar_color');
        $data['entry_track_color'] = $this->language->get('entry_track_color');
        $data['entry_line_width'] = $this->language->get('entry_line_width');
        $data['entry_size'] = $this->language->get('entry_size');
        
        return $data;
    }
}
