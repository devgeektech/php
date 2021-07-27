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
    public function setting($setting)
    {
        $this->load->model('tool/image');
        
        $data['images'] = array();
        foreach ($setting['images'] as $key => $image) {
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

    public function local($permission)
    {
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_images'] = $this->language->get('entry_images');
        $data['entry_additional_image'] = $this->language->get('entry_additional_image');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_link_target'] = $this->language->get('entry_link_target');
        $data['entry_onclick'] = $this->language->get('entry_onclick');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_scheme'] = $this->language->get('entry_scheme');
        $data['entry_border_width'] = $this->language->get('entry_border_width');

        $data['text_new_window'] = $this->language->get('text_new_window');
        $data['text_current_window'] = $this->language->get('text_current_window');

        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_image_add'] = $this->language->get('button_image_add');

        return $data;
    }

    public function options($permission)
    {
        $data = array();

        $this->load->model('tool/image');

        $data['actions'] = array(
            '' => $this->language->get('text_none'),
            'link' => $this->language->get('text_link'),
            'popup' => $this->language->get('text_popup')
        );

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        return $data;
    }
}
