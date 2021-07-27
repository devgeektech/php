<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleCountdownFlip extends Controller
{
    private $codename = 'countdown_flip';
    private $route = 'extension/d_visual_designer_module/countdown_flip';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    public function index($setting)
    {
        $data['time'] = 0;

        if ($setting['counter_mode'] == 'specify_date') {
            if (!empty($setting['datetime'])) {
                $time = strtotime($setting['datetime']) - time();
                if ($time > 0) {
                    $data['time'] = $time;
                }
            }
        } else {
            $str_time = '';
            
            if (!empty($data['setting']['day_week'])) {
                $str_time .= $data['setting']['day_week'].' ';
            }

            if (!empty($setting['time'])) {
                $str_time .= $setting['time'].' ';
            }

            $target_time = strtotime($str_time);

            if ($target_time < time() && empty($setting['day_week'])) {
                $target_time = strtotime('+1 day', $target_time);
            }

            $time = $target_time - time();

            if ($time > 0) {
                $data['time'] = $time;
            }
        }

        if ($setting['display_day']) {
            $data['type_countdown'] = 'DailyCounter';
        } elseif ($setting['display_hour']) {
            $data['type_countdown'] = 'HourlyCounter';
        } else {
            $data['type_countdown'] = 'MinuteCounter';
        }

        return $data;
    }

    public function scripts($permission){
        $data = array();
        $data[] = 'catalog/view/javascript/d_visual_designer_landing/library/flipclock/flipclock.min.js';
        if($permission) {
            $data[] = 'catalog/view\javascript\d_visual_designer_landing\library\datetimepicker\moment.js';
            $data[] = 'catalog/view\javascript\d_visual_designer_landing\library\datetimepicker\bootstrap-datetimepicker.min.js';
        }
        return $data;
    }

    public function styles($permission){
        $data = array();
        $data[] = 'catalog/view/javascript/d_visual_designer_landing/library/flipclock/flipclock.css';
        if($permission) {
            $data[] = 'catalog/view\javascript\d_visual_designer_landing\library\datetimepicker\bootstrap-datetimepicker.min.css';
        }
        return $data;
    }
}
