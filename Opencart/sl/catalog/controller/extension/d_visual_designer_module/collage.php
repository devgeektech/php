<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleCollage extends Controller
{
    private $codename = 'collage';
    private $route = 'extension/d_visual_designer_module/collage';

    public function __construct($registry)
    {
        
        parent::__construct($registry);
        $this->load->language($this->route);

        $this->theme = $this->config->get('config_template');
    }
    public function index($setting)
    {
        $this->load->model('tool/image');
            
        $data['images'] = array();
        
        foreach ($setting['images'] as $key => $value) {
            if (isset($value) && is_file(DIR_IMAGE . $value)) {
                $image = $value;
            } else {
                $image = 'no_image.png';
            }
            
            list($width, $height) = getimagesize(DIR_IMAGE . $image);
            
            $thumb = $this->model_tool_image->resize($image, $width, $height);
            
            if (VERSION < '2.2.0.0') {
                $width_popup = $this->config->get('config_image_popup_width');
                $height_popup = $this->config->get('config_image_popup_height');
            } elseif (VERSION < '3.0.0.0') {
                $width_popup = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
                $height_popup = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
            } else {
                $width_popup = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width');
                $height_popup = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height');
            }
            
            $data['images'][$key] = array(
                'thumb' => $thumb,
                'width' => $width,
                'height' => $height,
                'ratio' => round($width/$height, 2),
                'popup' => $this->model_tool_image->resize($image, $width_popup, $height_popup),
                'url' =>  $image
            );
        }

        return $data;
    }

    public function styles($permission) {
        $data = array();
        if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_visual_designer_module/collage.css')) {
            $data[] = 'catalog/view/theme/'.$this->theme.'/stylesheet/d_visual_designer_module/collage.css';
        } else {
            $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/collage.css';
        }
        return $data;
    }
}
