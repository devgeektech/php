<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTour extends Controller
{
    private $codename = 'tour';
    private $route = 'extension/d_visual_designer_module/tour';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function local()
    {
        $data['entry_active_section'] = $this->language->get('entry_active_section');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_title'] = $this->language->get('entry_title');

        return $data;
    }

    public function options()
    {
        $data['aligns'] = array(
            'left' => $this->language->get('text_left'),
            'center' => $this->language->get('text_center'),
            'right' => $this->language->get('text_right'),
        );

        return $data;
    }
}
