<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleMessageBox extends Controller
{
    private $codename = 'message_box';
    private $route = 'extension/d_visual_designer_module/message_box';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function index($setting) {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');

        return $data;
    }

    public function setting($setting) {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');

        return $data;
    }

    public function local()
    {
        $data['entry_icon_library'] = $this->language->get('entry_icon_library');
        $data['entry_presets'] = $this->language->get('entry_presets');
        $data['entry_style'] = $this->language->get('entry_style');
        $data['entry_share'] = $this->language->get('entry_share');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_border_color'] = $this->language->get('entry_border_color');
        $data['entry_icon'] = $this->language->get('entry_icon');
        $data['entry_text'] = $this->language->get('entry_text');

        return $data;
    }

    public function options()
    {
        $data['libraries'] = array(
            'fontawesome' => 'Font Awesome',
            'glyphicon' => 'Glyphicons',
            'ionicons' => 'Open Ionic',
            'mapicons' => 'Map Icons',
            'material' => 'Material Design Iconic Font',
            'typeicon' => 'Typeicons',
            'elusive' => 'Elusive Icons',
            'octicon' => 'Octicons',
            'weather' => 'Weather Icons'
        );
        
        $data['styles'] = array(
            '' => $this->language->get('text_standard'),
            'solid' => $this->language->get('text_solid'),
            'solid_icon' => $this->language->get('text_solid_icon'),
            'outline' => $this->language->get('text_outline'),
            '3d' => $this->language->get('text_3d'),
        );
        
        $data['shares'] = array(
            'square' => $this->language->get('text_square'),
            'rounded' => $this->language->get('text_rounded'),
            'round' => $this->language->get('text_round')
        );

        return $data;
    }
}
