<?php
/*
*    location: admin/controller
*/

class ControllerExtensionDVisualDesignerModuleCustomHeading extends Controller
{
    private $codename = 'custom_heading';
    private $route = 'extension/d_visual_designer_module/custom_heading';
    
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function index($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        
        return $data;
    }
    public function setting($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        
        return $data;
    }

    public function local()
    {
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_tag'] = $this->language->get('entry_tag');
        $data['entry_font_size'] = $this->language->get('entry_font_size');
        $data['entry_line_height'] = $this->language->get('entry_line_height');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_font_family'] = $this->language->get('entry_font_family');
        
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        return $data;
    }
    
    public function options()
    {
        $data['tags'] = array('h1','h2','h3','h4','h5','h6','p','div');
        
        $data['aligns'] = array(
            'left' => $this->language->get('text_left'),
            'center' => $this->language->get('text_center'),
            'right' => $this->language->get('text_right')
        );

        return $data;
    }

    public function catalog_styles($permission) {
        $data = array();
        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/custom_heading.css';
        return $data;
    }
}
