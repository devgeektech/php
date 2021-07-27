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
    
    public function options()
    {
        $data['positions'] = array(
            'left' => $this->language->get('text_left'),
            'center' => $this->language->get('text_center'),
            'right' => $this->language->get('text_right')
        );


        $data['modes'] = array(
            'specify_date' => $this->language->get('text_specify_date'),
            'loop' => $this->language->get('text_loop')
            );

        $data['days_week'] = array(
            '' => $this->language->get('text_none'),
            'Monday' => $this->language->get('text_week_monday'),
            'Tuesday' => $this->language->get('text_week_tuesday'),
            'Wednesday' => $this->language->get('text_week_wednesday'),
            'Thursday' => $this->language->get('text_week_thursday'),
            'Friday' => $this->language->get('text_week_friday'),
            'Saturday' => $this->language->get('text_week_saturday'),
            'Sunday' => $this->language->get('text_week_sunday')
            );

        return $data;
    }

    public function local()
    {
        $data['entry_datetime'] = $this->language->get('entry_datetime');
        $data['entry_display_title'] = $this->language->get('entry_display_title');
        $data['entry_display_day'] = $this->language->get('entry_display_day');
        $data['entry_display_hour'] = $this->language->get('entry_display_hour');
        $data['entry_display_second'] = $this->language->get('entry_display_second');
        $data['entry_color_number'] = $this->language->get('entry_color_number');
        $data['entry_color_title'] = $this->language->get('entry_color_title');
        $data['entry_background'] = $this->language->get('entry_background');
        $data['entry_scale'] = $this->language->get('entry_scale');
        $data['entry_position'] = $this->language->get('entry_position');
        $data['entry_display_day'] = $this->language->get('entry_display_day');
        $data['entry_display_hour'] = $this->language->get('entry_display_hour');
        $data['entry_display_second'] = $this->language->get('entry_display_second');
        $data['entry_counter_mode'] = $this->language->get('entry_counter_mode');
        $data['entry_day_week'] = $this->language->get('entry_day_week');
        $data['entry_time'] = $this->language->get('entry_time');

        
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
