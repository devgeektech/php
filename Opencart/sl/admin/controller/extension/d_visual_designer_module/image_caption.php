<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleImageCaption extends Controller
{
    private $codename = 'image_caption';
    private $route = 'extension/d_visual_designer_module/image_caption';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_visual_designer/designer');
        $this->load->model('extension/d_opencart_patch/load');
    }

    public function index($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');

        $this->load->model('tool/image');

        if (isset($setting['image']) && is_file(DIR_IMAGE . $setting['image'])) {
            $image = $setting['image'];
        } else {
            $image = 'no_image.png';
        }

        if ($setting['size'] == 'original' || $setting['size'] == 'responsive') {
            list($width, $height) = getimagesize(DIR_IMAGE . $image);
        } elseif ($setting['size'] == 'small') {
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

        $data['width'] = $width;

        $data['height'] = $height;

        $data['thumb'] = $this->model_tool_image->resize($image, $width, $height);
        return $data;
    }

    public function setting($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        
        $this->load->model('tool/image');
        
        if (isset($setting['image']) && is_file(DIR_IMAGE . $setting['image'])) {
            $image = $setting['image'];
        } else {
            $image = 'no_image.png';
        }
        
        
        $data['thumb'] = $this->model_tool_image->resize($image, 100, 100);
        
        return $data;
    }

    public function local()
    {
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_position_text'] = $this->language->get('entry_position_text');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_animate'] = $this->language->get('entry_animate');
        $data['entry_additional_image'] = $this->language->get('entry_additional_image');
        $data['entry_onclick'] = $this->language->get('entry_onclick');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_link_target'] = $this->language->get('entry_link_target');
        $data['entry_alt'] = $this->language->get('entry_alt');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_display_border'] = $this->language->get('entry_display_border');
        $data['entry_padding_text'] = $this->language->get('entry_padding_text');
        
        $data['text_new_window'] = $this->language->get('text_new_window');
        $data['text_current_window'] = $this->language->get('text_current_window');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        
        
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_image_add'] = $this->language->get('button_image_add');

        return $data;
    }

    public function options()
    {
        $this->load->model('tool/image');

        $data['positions'] = array(
            'top' => $this->language->get('text_prosition_top'),
            'bottom' => $this->language->get('text_prosition_bottom'),
            'left' => $this->language->get('text_prosition_left'),
            'right' => $this->language->get('text_prosition_right')
        );
                
        $data['animates'] = array(
            '' => $this->language->get('text_no'),
            'fadeInDown' => $this->language->get('text_top_to_bottom'),
            'fadeInUp' => $this->language->get('text_bottom_to_top'),
            'fadeInLeft' => $this->language->get('text_left_to_right'),
            'fadeInRight' => $this->language->get('text_right_to_left'),
            'fadeIn' =>  $this->language->get('text_apear')
        );
        
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

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        return $data;
    }

    public function catalog_styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/image_caption.css';

        return $data;
    }
}
