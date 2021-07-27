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
        $this->load->model('extension/module/d_visual_designer');
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
        
        if (VERSION < '2.2.0.0') {
            $width = $this->config->get('config_image_popup_width');
            $height = $this->config->get('config_image_popup_height');
        } elseif (VERSION < '3.0.0.0') {
            $width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
            $height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
        } else {
            $width = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width');
            $height = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height');
        }
        
        $data['popup'] = $this->model_tool_image->resize($image, $width, $height);

        return $data;
    }

    public function styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/image_caption.css';

        return $data;
    }
}
