<?php
class ControllerExtensionEventDVisualDesignerLanding extends Controller
{
    public $codename = 'd_visual_designer_landing';
    private $setting_module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('extension/d_visual_designer/designer');
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/load');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));

        $this->load->model('setting/setting');
        $this->setting_module = $this->model_setting_setting->getSetting('d_visual_designer');

        if (!empty($this->setting_module['d_visual_designer_setting'])) {
            $this->setting_module = $this->setting_module['d_visual_designer_setting'];
        } else {
            $this->setting_module = $this->config->get('d_visual_designer_setting');
        }
    }

    public function view_variation_after(&$route, &$data, &$output)
    {

        $designer_data = array(
            'config' => 'd_visual_designer_landing',
            'output' => &$output,
            'id' => !empty($this->request->get['variation_id'])?$this->request->get['variation_id']:false
        );

        $vd_content = $this->load->controller('extension/d_visual_designer/designer', $designer_data);

        $html_dom = new d_simple_html_dom();
        $html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="description['.$language['language_id'].'][description"]', 0)->class .=' d_visual_designer';
        }

        $html_dom->find('body', 0)->innertext .= $vd_content;

        $output = (string)$html_dom;
    }

    public function model_addVariation_before(&$route, &$data, &$output)
    {
        if ($this->setting_module['save_text']) {
            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();
    
            foreach ($languages as $language) {
                $field_name = rawurlencode("description[".$language['language_id']."][description]");
                if (!empty($data[0]['vd_content'][$field_name])) {
                    $setting = json_decode(html_entity_decode($data[1]['vd_content'][$field_name], ENT_QUOTES, 'UTF-8'), true);
                    $data[1]['description'][$language['language_id']]['description'] = $this->model_extension_d_visual_designer_designer->getText($setting);
                }
            }
        }
    }
    public function model_addVariation_after(&$route, &$data, &$output)
    {
        foreach ($data[1]['vd_content'] as $field_name => $setting_json) {
            $setting = json_decode(html_entity_decode($setting_json, ENT_QUOTES, 'UTF-8'), true);
            $content = $this->model_extension_d_visual_designer_designer->parseSetting($setting);
            $this->model_extension_d_visual_designer_designer->saveContent($content, 'd_visual_designer_landing', $output, rawurldecode($field_name));
        }
    }
    public function model_editVariation_before(&$route, &$data, &$output)
    {
        if ($this->setting_module['save_text']) {
            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            foreach ($languages as $language) {
                $field_name = rawurlencode("description[".$language['language_id']."][description]");
                if (!empty($data[1]['vd_content'][$field_name])) {
                    $setting = json_decode(html_entity_decode($data[1]['vd_content'][$field_name], ENT_QUOTES, 'UTF-8'), true);
                    $data[1]['description'][$language['language_id']]['description'] = $this->model_extension_d_visual_designer_designer->getText($setting);
                }
            }
        }
    }
    public function model_editVariation_after(&$route, &$data, &$output)
    {

        foreach ($data[1]['vd_content'] as $field_name => $setting_json) {
            $setting = json_decode(html_entity_decode($setting_json, ENT_QUOTES, 'UTF-8'), true);
            $content = $this->model_extension_d_visual_designer_designer->parseSetting($setting);
            $this->model_extension_d_visual_designer_designer->saveContent($content, 'd_visual_designer_landing', $data[0], rawurldecode($field_name));
        }
    }

    public function column_left_after(&$route, &$data, &$output)
    {
        $this->load->language('extension/module/'.$this->codename);

        $html_dom = new d_simple_html_dom();
        $html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $data['link'] = $this->model_extension_d_opencart_patch_url->link('extension/module/'.$this->codename);

        $data['text_landing'] = $this->language->get('text_menu_title');

        $html = $this->model_extension_d_opencart_patch_load->view('extension/'.$this->codename.'/menu', $data);

        if (VERSION>='2.3.0.0') {
            if ($html_dom->find('ul#menu > li#menu-catalog > ul')) {
                $html_dom->find('ul#menu > li#menu-catalog > ul', 0)->innertext .= $html;
            }
        } else {
            if ($html_dom->find('ul#menu > li#catalog > ul')) {
                $html_dom->find('ul#menu > li#catalog > ul', 0)->innertext .= $html;
            }
        }
        

        $output = (string)$html_dom;
    }
}
