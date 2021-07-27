<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModulePinterestLike extends Controller
{
    private $codename = 'pinterest_like';
    private $route = 'extension/d_visual_designer_module/pinterest_like';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    public function local()
    {
        $data['entry_type_button'] = $this->language->get('entry_type_button');

        return $data;
    }

    public function options()
    {
        $data['types'] = array(
            'horizontal' => $this->language->get('text_horizontal'),
            'vertical' => $this->language->get('text_vertical'),
            'none' => $this->language->get('text_none')
        );

        return $data;
    }
}
