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
        $data['text'] = html_entity_decode($setting['text'], ENT_QUOTES, 'UTF-8');
        $data['final_text'] = html_entity_decode($setting['final_text'], ENT_QUOTES, 'UTF-8');

        return $data;
    }

    public function scripts($permission){
        $data[] = 'catalog/view/javascript/d_visual_designer_landing/library/jquery.countdown.min.js';
        if($permission) {
            $data[] = 'catalog/view\javascript\d_visual_designer_landing\library\datetimepicker\moment.js';
            $data[] = 'catalog/view\javascript\d_visual_designer_landing\library\datetimepicker\bootstrap-datetimepicker.min.js';
        }
        return $data;
    }

    public function styles($permission){
        $data = array();
        if($permission) {
            $data[] = 'catalog/view\javascript\d_visual_designer_landing\library\datetimepicker\bootstrap-datetimepicker.min.css';
        }
        return $data;
    }
}
