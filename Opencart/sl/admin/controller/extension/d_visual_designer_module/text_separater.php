<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTextSeparater extends Controller
{
    private $codename = 'text_separater';
    private $route = 'extension/d_visual_designer_module/text_separater';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_title_align'] = $this->language->get('entry_title_align');
        $data['entry_color_title'] = $this->language->get('entry_color_title');
        $data['entry_font_family'] = $this->language->get('entry_font_family');
        $data['entry_font_size'] = $this->language->get('entry_font_size');

        $data['entry_border_width'] = $this->language->get('entry_border_width');
        $data['entry_border_style'] = $this->language->get('entry_border_style');
        $data['entry_border_color'] = $this->language->get('entry_border_color');

        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_align'] = $this->language->get('entry_align');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        return $data;
    }

    public function options()
    {
        $data['aligns'] = array(
            'left' => $this->language->get('text_align_left'),
            'center' => $this->language->get('text_align_center'),
            'right' => $this->language->get('text_align_right')
        );
        $data['styles'] = array('dotted','dashed','solid','double','groove','ridge','inset','outset');

        return $data;
    }

    public function catalog_styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/text_separater.css';

        return $data;
    }
}
