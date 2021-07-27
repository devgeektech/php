<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleFacebookLike extends Controller
{
    private $codename = 'facebook_like';
    private $route = 'extension/d_visual_designer_module/facebook_like';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    public function local()
    {
        $data['entry_type'] = $this->language->get('entry_type');

        return $data;
    }

    public function options()
    {
        $data['types'] = array(
            'standard' => $this->language->get('text_standard'),
            'box_count' => $this->language->get('text_box_count'),
            'button_count' => $this->language->get('text_button_count'),
        );

        return $data;
    }
}
