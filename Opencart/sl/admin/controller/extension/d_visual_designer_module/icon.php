<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleIcon extends Controller
{
    private $codename = 'icon';
    private $route = 'extension/d_visual_designer_module/icon';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
    }

    public function local()
    {
        $data['entry_icon_library'] = $this->language->get('entry_icon_library');
        $data['entry_icon'] = $this->language->get('entry_icon');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_background_style'] = $this->language->get('entry_background_style');
        $data['entry_background_color'] = $this->language->get('entry_background_color');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_adaptive_design'] = $this->language->get('entry_adaptive_design');

        $data['column_device'] = $this->language->get('column_device');
        $data['column_align'] = $this->language->get('column_align');
        $data['column_size'] = $this->language->get('column_size');

        $data['text_phone'] = $this->language->get('text_phone');
        $data['text_tablet'] = $this->language->get('text_tablet');
        $data['text_none'] = $this->language->get('text_none');
        
        return $data;
    }

    public function options()
    {
        $data['sizes'] = array(
            'xs'=>'Mini',
            'sm'=>'Small',
            'md'=>'Normal',
            'lg'=>'Large',
            'xl'=>'Extra Large'
            );
        $data['aligns'] = array(
            'left'=>'left',
            'center'=>'center',
            'right'=>'right'
            );
        $data['styles'] = array(
            'rounded'=>'Circle',
            'boxed'=>'Square',
            'rounded-less'=>'Rounded',
            'rounded-outline'=>'Outline Circle',
            'boxed-outline'=>'Outline Square',
            'rounded-less-outline'=>'Outline Rounded'
            );

        return $data;
    }

    public function catalog_styles($permission) {
        $data = array();
        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/icon.css';
        return $data;
    }
    
}
