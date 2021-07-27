<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleCountdown extends Controller
{
    private $codename = 'countdown';
    private $route = 'extension/d_visual_designer_module/countdown';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function index($setting)
    {

        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        $data['final_text'] = html_entity_decode(htmlspecialchars_decode($setting['final_text']), ENT_QUOTES, 'UTF-8');

        return $data;
    }

    public function setting($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        $data['final_text'] = html_entity_decode(htmlspecialchars_decode($setting['final_text']), ENT_QUOTES, 'UTF-8');

        return $data;
    }
    
    public function local()
    {
        $data['entry_datetime'] = $this->language->get('entry_datetime');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_final_text'] = $this->language->get('entry_final_text');
        
        $data['text_formatter'] = $this->language->get('text_formatter');
        
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');

        return $data;
    }

    public function scripts(){
        $data = array();
        $data[] = 'view\javascript\d_visual_designer_landing\library\datetimepicker\moment.js';
        $data[] = 'view\javascript\d_visual_designer_landing\library\datetimepicker\bootstrap-datetimepicker.min.js';
        return $data;
    }

    public function styles(){
        $data = array();
        $data[] = 'view\javascript\d_visual_designer_landing\library\datetimepicker\bootstrap-datetimepicker.min.css';
        return $data;
    }
}
