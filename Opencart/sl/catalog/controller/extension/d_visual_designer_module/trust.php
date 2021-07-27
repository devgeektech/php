<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTrust extends Controller
{
    private $codename = 'trust';
    private $route = 'extension/d_visual_designer_module/trust';

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
}
