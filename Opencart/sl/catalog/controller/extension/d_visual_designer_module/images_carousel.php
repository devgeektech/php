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

            $thumb = $this->model_tool_image->resize($image, $width, $height);
            
            if (VERSION < '2.2.0.0') {
                $width = $this->config->get('config_image_popup_width');
                $height = $this->config->get('_image_popup_height');
            } elseif (VERSION < '3.0.0.0') {
                $width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
                $height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
            } else {
                $width = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width');
                $height = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height');
            }
            
            $data['images'][$key] = array(
                'thumb' => $thumb,
                'popup' => $this->model_tool_image->resize($image, $width, $height),
                'url' =>  $image
            );
        }
        
        return $data;
    }
}
