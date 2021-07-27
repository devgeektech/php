<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleStarRating extends Controller
{
    private $codename = 'star_rating';
    private $route = 'extension/d_visual_designer_module/star_rating';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function options()
    {
        $data['sizes'] = array(
            '1' => $this->language->get('text_size_1'),
            '2' => $this->language->get('text_size_2'),
            '3' => $this->language->get('text_size_3'),
            '4' => $this->language->get('text_size_4'),
            '5' => $this->language->get('text_size_5')
            );

        $data['ratings'] = array('1','2','3','4','5');

        return $data;
    }

    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_rating'] = $this->language->get('entry_rating');
        $data['entry_size'] = $this->language->get('entry_size');

        return $data;
    }

}
