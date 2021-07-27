<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleFeatures extends Controller
{
    private $codename = 'features';
    private $route = 'extension/d_visual_designer_module/features';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function index($setting)
    {
        $this->load->model('tool/image');

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
            if (VERSION>='3.0.0.0') {
                $width = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_category_width');
                $height = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_category_height');
            } elseif (VERSION>='2.2.0.0') {
                $width = $this->config->get($this->config->get('config_theme') . '_image_category_width');
                $height = $this->config->get($this->config->get('config_theme') . '_image_category_height');
            } else {
                $width = $this->config->get('config_image_category_width');
                $height = $this->config->get('config_image_category_height');
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
    public function options()
    {
        $this->load->model('tool/image');
        
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        
        $data['sizes'] = array(
            'original' => $this->language->get('text_original'),
            'responsive' => $this->language->get('text_responsive'),
            'small' => $this->language->get('text_small'),
            'medium' => $this->language->get('text_medium'),
            'large' => $this->language->get('text_large'),
            'custom' => $this->language->get('text_custom')
            );
        
        $data['image_positions'] = array(
            'top' => $this->language->get('text_position_image_top'),
            'left_top' => $this->language->get('text_position_style_image_left_top'),
            'left' => $this->language->get('text_position_style_image_left')
            );

        return $data;
    }

    public function local()
    {
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_alt'] = $this->language->get('entry_alt');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_image_position'] = $this->language->get('entry_image_position');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');

        return $data;
    }

    public function catalog_styles($permission)
    {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_landing/features/features.css';

        return $data;
    }
}
