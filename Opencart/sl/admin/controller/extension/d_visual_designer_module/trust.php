<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTrust extends Controller
{
    private $codename = 'trust';
    private $route = 'extension/d_visual_designer_module/trust';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model($this->route);
    }

    public function index($setting)
    {
        $this->load->model('tool/image');

        $data['images'] = array();

        foreach ($setting['images'] as $value) {
            if (isset($value) && is_file(DIR_IMAGE . $value['image'])) {
                $image = $value['image'];
            } else {
                $image = 'no_image.png';
            }

            list($width, $height) = getimagesize(DIR_IMAGE . $image);

            $thumb = $this->model_tool_image->resize($image, $width, $height);

            $data['images'][] = array(
                'thumb' => $thumb,
                'link' => $value['link']
            );
        }

        return $data;
    }
    
    public function setting($setting)
    {
        $data['images'] = array('' => $this->language->get('text_none')) + $this->{'model_extension_d_visual_designer_module_'.$this->codename}->getImages();

        return $data;
    }

    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_link'] = $this->language->get('column_link');

        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_remove'] = $this->language->get('button_remove');

        $data['text_none'] = $this->language->get('text_none');

        return $data;
    }
}
