<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleSocialIcons extends Controller
{
    private $codename = 'social_icons';
    private $route = 'extension/d_visual_designer_module/social_icons';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function local()
    {
        $data['text_facebook'] = $this->language->get('text_facebook');
        $data['text_twitter'] = $this->language->get('text_twitter');
        $data['text_google_plus'] = $this->language->get('text_google_plus');
        $data['text_vk'] = $this->language->get('text_vk');
        $data['text_instagram'] = $this->language->get('text_instagram');

        $data["text_phone"] = $this->language->get("text_phone");
        $data["text_tablet"] = $this->language->get("text_tablet");
        $data["text_tablet"] = $this->language->get("text_tablet");

        $data['column_device'] = $this->language->get('column_device');
        $data['column_margin_beetwen'] = $this->language->get('column_margin_beetwen');
        $data['column_align'] = $this->language->get('column_align');
    
        $data['entry_icon_library'] = $this->language->get('entry_icon_library');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_icon'] = $this->language->get('entry_icon');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_background'] = $this->language->get('entry_background');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_margin_beetwen'] = $this->language->get('entry_margin_beetwen');

        return $data;
    }

    public function options() {
        $data['types'] = array(
            'circle' => $this->language->get('text_type_circle'),
            'text' => $this->language->get('text_type_text')
        );

        $data['aligns'] = array(
            'left' => $this->language->get('text_left'),
            'center' => $this->language->get('text_center'),
            'right' => $this->language->get('text_right')
            );

        return $data;
    }
}
