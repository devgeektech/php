<?php
/*
 *	location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTwitterWidget extends Controller
{
    private $codename = 'twitter_widget';
    private $route = 'extension/d_visual_designer_module/twitter_widget';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function options()
    {
        $data['themes'] = array(
            'light' => $this->language->get('text_theme_light'),
            'dark' => $this->language->get('text_theme_dark')
        );
        return $data;
    }

    public function local()
    {
        $data['entry_href'] = $this->language->get('entry_href');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_color_link'] = $this->language->get('entry_color_link');
        $data['entry_theme'] = $this->language->get('entry_theme');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        return $data;
    }
}
