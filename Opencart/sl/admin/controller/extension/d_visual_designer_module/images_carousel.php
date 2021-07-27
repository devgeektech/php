<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleImagesCarousel extends Controller
{
    private $codename = 'images_carousel';
    private $route = 'extension/d_visual_designer_module/images_carousel';
    
    private $setting = '';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function index($setting)
    {
        $this->load->model('tool/image');

        if (isset($setting['images'])) {
            $images_data = $setting['images'];
        } else {
            $images_data = array();
        }

        $data['images'] = array();

        foreach ($images_data as $key => $value) {
            if (isset($value) && is_file(DIR_IMAGE . $value)) {
                $image = $value;
            } else {
                $image = 'no_image.png';
            }

            if ($setting['size'] == 'original' || $setting['size'] == 'responsive') {
                list($width, $height) = getimagesize(DIR_IMAGE . $image);
            } elseif ($data['setting']['size'] == 'small') {
                if (VERSION < '2.2.0.0') {
                    $width = $this->config->get('config_image_category_width');
                    $height = $this->config->get('config_image_category_height');
                } elseif (VERSION < '3.0.0.0') {
                    $width = $this->config->get($this->config->get('config_theme') . '_image_category_width');
                    $height = $this->config->get($this->config->get('config_theme') . '_image_category_height');
                } else {
                    $width = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_category_width');
                    $height = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_category_height');
                }
            } elseif ($setting['size'] == 'medium') {
                $width = 300;
                $height = 94;
            } elseif ($setting['size'] == 'large') {
                $width = 600;
                $height = 188;
            } elseif ($setting['size'] == 'custom') {
                $width = $setting['width'];
                $height = $setting['height'];
            }

            $thumb = $this->model_tool_image->resize($image, $width, $height);

            $data['images'][$key] = array(
                'thumb' => $thumb
            );
        }

        return $data;
    }
    
    public function setting($setting)
    { 
        $this->load->model('tool/image');
        
        if (!empty($setting['images'])) {
            $images_data = $setting['images'];
        } else {
            $images_data = array();
        }
        
        $data['images'] = array();
        foreach ($images_data as $key => $image) {
            if (isset($image) && is_file(DIR_IMAGE . $image)) {
                $thumb = $this->model_tool_image->resize($image, 100, 100);
            } else {
                $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
            }
            $data['images'][$key] = array(
                'thumb' => $thumb,
                'url' =>  $image
            );
        }
        
        return $data;
    }

    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_images'] = $this->language->get('entry_images');
        $data['entry_additional_image'] = $this->language->get('entry_additional_image');
        $data['entry_onclick'] = $this->language->get('entry_onclick');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_speed'] = $this->language->get('entry_speed');
        $data['entry_slides_per_view'] = $this->language->get('entry_slides_per_view');
        $data['entry_auto_play'] = $this->language->get('entry_auto_play');
        $data['entry_hide_pagination_control'] = $this->language->get('entry_hide_pagination_control');
        $data['entry_hide_next_prev_button'] = $this->language->get('entry_hide_next_prev_button');
        $data['entry_stop_on_hover'] = $this->language->get('entry_stop_on_hover');
        $data['entry_lazy_load'] = $this->language->get('entry_lazy_load');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_animate'] = $this->language->get('entry_animate');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_link_target'] = $this->language->get('entry_link_target');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_new_window'] = $this->language->get('text_new_window');
        $data['text_current_window'] = $this->language->get('text_current_window');
        
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_image_add'] = $this->language->get('button_image_add');

        return $data;
    }

    public function options()
    {
        $this->load->model('tool/image');

        $data['sizes'] = array(
            'original' => $this->language->get('text_original'),
            'responsive' => $this->language->get('text_responsive'),
            'small' => $this->language->get('text_small'),
            'medium' => $this->language->get('text_medium'),
            'large' => $this->language->get('text_large'),
            'custom' => $this->language->get('text_custom')
        );
        
        $data['actions'] = array(
            '' => $this->language->get('text_none'),
            'link' => $this->language->get('text_link'),
            'popup' => $this->language->get('text_popup')
        );
        
        $data['animates'] = array(
            '' => $this->language->get('text_none'),
            'fade' => $this->language->get('text_fade'),
            'backSlide' => $this->language->get('text_backSlide'),
            'goDown' => $this->language->get('text_goDown'),
            'fadeUp' => $this->language->get('text_fadeUp')
        );

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);        

        return $data;
    }
}
