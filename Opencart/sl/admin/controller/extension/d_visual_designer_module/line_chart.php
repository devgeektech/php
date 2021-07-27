<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleLineChart extends Controller
{
    private $codename = 'line_chart';
    private $route = 'extension/d_visual_designer_module/line_chart';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_visual_designer/designer');
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_units'] = $this->language->get('entry_units');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_label'] = $this->language->get('entry_label');
        $data['entry_value'] = $this->language->get('entry_value');
        $data['entry_values'] = $this->language->get('entry_values');
        $data['entry_modes'] = $this->language->get('entry_modes');
        $data['entry_gap'] = $this->language->get('entry_gap');
        $data['entry_animate'] = $this->language->get('entry_animate');
        $data['entry_x_values'] = $this->language->get('entry_x_values');
        $data['entry_display_legend'] = $this->language->get('entry_display_legend');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');

        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_add'] = $this->language->get('button_add');

        return $data;
    }

    public function options()
    {
        $data['modes'] = array(
            'bar' => $this->language->get('text_bar'),
            'line' => $this->language->get('text_line')
        );
        
        $data['animates'] = array(
            'easeoutbounce' => $this->language->get('text_easeOutBounce'),
            'easeoutelastic' => $this->language->get('text_easeOutElastic'),
            'easeoutback' => $this->language->get('text_easeOutBack'),
            'easeinoutcubic' => $this->language->get('text_easeInOutCubic'),
            'easeinoutquint' => $this->language->get('text_easeinOutQuint'),
            'easeinoutquart' => $this->language->get('text_easeInOutQuart'),
            'easeinquad' => $this->language->get('text_easeinQuad'),
            'easeoutsine' => $this->language->get('text_easeOutSine'),
        );

        return $data;
    }
}
