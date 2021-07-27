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
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_images'] = $this->language->get('entry_images');
        $data['entry_source'] = $this->language->get('entry_source');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_auto_rotate'] = $this->language->get('entry_auto_rotate');
        $data['entry_additional_image'] = $this->language->get('entry_additional_image');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_link_target'] = $this->language->get('entry_link_target');
        $data['entry_onclick'] = $this->language->get('entry_onclick');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_title'] = $this->language->get('entry_title');
        
        $data['text_new_window'] = $this->language->get('text_new_window');
        $data['text_current_window'] = $this->language->get('text_current_window');
        
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_image_add'] = $this->language->get('button_image_add');
        
        return $data;
    }

    public function options()
    {
        $data['types_gallery'] = array(
            'slider_fade'=> $this->language->get('text_slider_fade'),
            'slider_slide'=> $this->language->get('text_slider_slide'),
            'image_grid'=> $this->language->get('text_image_grid')
        );
        
        $data['sizes'] = array(
            'original' => $this->language->get('text_original'),
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
        
        $data['source'] = array('media_library','external_link');

        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        return $data;
    }

    public function catalog_styles($permission)
    {
        $data = array();
        if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_visual_designer/gallery/gallery.css')) {
            $data[] = 'catalog/view/theme/' . $this->theme . '/stylesheet/d_visual_designer/gallery/gallery.css';
        } else {
            $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer/gallery/gallery.css';
        }
        return $data;
    }
}
