<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleNumber extends Controller
{
    private $codename = 'number';
    private $route = 'extension/d_visual_designer_module/number';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function local()
    {
        $data['entry_number'] = $this->language->get('entry_number');
        $data['entry_thousand_separator'] = $this->language->get('entry_thousand_separator');
        $data['entry_duration'] = $this->language->get('entry_duration');
        $data['entry_font_size'] = $this->language->get('entry_font_size');
        $data['entry_font_bold'] = $this->language->get('entry_font_bold');
        $data['entry_color'] = $this->language->get('entry_color');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        return $data;
    }
}
