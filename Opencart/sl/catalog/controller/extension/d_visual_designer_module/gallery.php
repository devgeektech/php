<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleGallery extends Controller
{
    private $codename = 'gallery';
    private $route = 'extension/d_visual_designer_module/gallery';

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
            
            if ($setting['size'] == 'original') {
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
                $height = $this->config->get('config_image_popup_height');
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

    public function scripts($permission) {
        $data = array();
        $data[] = 'catalog/view/javascript/d_visual_designer_module/library/lightgallery/lightgallery.min.js';
        $data[] = 'catalog/view/javascript/d_visual_designer_module/library/lightslider/js/lightslider.min.js';
        $data[] = 'catalog/view/javascript/d_visual_designer_module/library/justifiedGallery/jquery.justifiedGallery.min.js';
        return $data;
    }
    public function styles($permission) {
        $data = array();
        $data[] = 'catalog/view/javascript/d_visual_designer_module/library/lightgallery/lightgallery.min.css';
        $data[] = 'catalog/view/javascript/d_visual_designer_module/library/justifiedGallery/justifiedGallery.min.css';
        $data[] = 'catalog/view/javascript/d_visual_designer_module/library/lightslider/css/lightslider.min.css';
        if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_visual_designer/gallery/gallery.css')) {
            $data[] = 'catalog/view/theme/'.$this->theme.'/stylesheet/d_visual_designer/gallery/gallery.css';
        } else {
            $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer/gallery/gallery.css';
        }
        return $data;
    }
}
