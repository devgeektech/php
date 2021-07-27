<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleVideo extends Controller
{
    private $codename = 'video';
    private $route = 'extension/d_visual_designer_module/video';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }

    public function index($setting)
    {

        $data['link'] = str_replace("watch?v=", "embed/", $setting['link']);

        $data['link'] = str_replace("vimeo.com", "player.vimeo.com/video", $setting['link']);

        return $data;
    }
    
    public function local()
    {
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_ratio'] = $this->language->get('entry_ratio');

        $data['text_link'] = $this->language->get('text_link');

        return $data;
    }

    public function options()
    {
        $data['ratios'] = array(
            '169' => '16:9',
            '43' => '4:3',
            '235' => '2.35:1'
        );

        return $data;
    }
}
