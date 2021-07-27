<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTestimonial extends Controller
{
    private $codename = 'testimonial';
    private $route = 'extension/d_visual_designer_module/testimonial';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function index($setting)
    {
        $data['comment'] = html_entity_decode(htmlspecialchars_decode($setting['comment']), ENT_QUOTES, 'UTF-8');

        $this->load->model('tool/image');

        if (isset($setting['image']) && is_file(DIR_IMAGE . $setting['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($setting['image'], 96, 96);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 96, 96);
        }

        return $data;
    }
    
    public function setting($setting)
    {
        $data['comment'] = html_entity_decode(htmlspecialchars_decode($setting['comment']), ENT_QUOTES, 'UTF-8');
        
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

        return $data;
    }

    public function local()
    {
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_comment'] = $this->language->get('entry_comment');

        return $data;
    }

    public function catalog_styles($permission)
    {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_landing/testimonial/testimonial.css';

        return $data;
    }
}
