<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleSeparater extends Controller
{
    private $codename = 'separater';
    private $route = 'extension/d_visual_designer_module/separater';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    public function local()
    {
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_border_width'] = $this->language->get('entry_border_width');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_style'] = $this->language->get('entry_style');

        return $data;
    }

    public function options()
    {
        $data['aligns'] = array(
            'left' => $this->language->get('text_align_left'),
            'center' => $this->language->get('text_align_center'),
            'right' => $this->language->get('text_align_right'),
        );
        $data['styles'] = array('dotted','dashed','solid','double','groove','ridge','inset','outset');

        return $data;
    }
}
