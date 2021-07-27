<?php
class ControllerExtensionEventDVisualDesignerModule extends Controller {

    public $codename = 'd_visual_designer_module';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->module_name = (VERSION >= '3.0.0.0'?'module_': '') .$this->codename;
        $this->load->model('extension/d_visual_designer/designer');
    }

    public function view_after(&$route, &$data, &$output){

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_d_opencart_patch_module->getModule($this->request->get['module_id']);
            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            foreach ($languages as $language) {
                $content = $this->model_extension_d_visual_designer_designer->getContent('d_visual_designer_module', $this->request->get['module_id'], 'description['.$language['language_id'].'][description]');
                if(!empty($module_info['description'][$language['language_id']]['description']) && empty($content)){
                    $this->model_extension_d_visual_designer_designer->saveContent($module_info['description'][$language['language_id']]['description'], $this->codename, $this->request->get['module_id'], 'description['.$language['language_id'].'][description]');
                }
            }
        }

        $designer_data = array(
            'config' => 'd_visual_designer_module',
            'output' => &$output,
            'id' => !empty($this->request->get['module_id'])?$this->request->get['module_id']:false
        );

        $vd_content = $this->load->controller('extension/d_visual_designer/designer', $designer_data);

        $html_dom = new d_simple_html_dom();
        $html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $html_dom->find('body', 0)->innertext .= $vd_content;

        $output = (string)$html_dom;
    }
    public function save_success_after(&$route, &$data)
    {
        if(isset($this->request->post['vd_content'])){
            $this->session->data['vd_content'] = $this->request->post['vd_content'];
            unset($this->request->post['vd_content']);
        } else {
            $this->load->model('localisation/language');
            $this->load->model('extension/d_visual_designer/designer');

            $languages = $this->model_localisation_language->getLanguages();

            foreach ($languages as $language) {
                $field_name = rawurlencode("description[".$language['language_id']."][description]");

                $setting = json_decode(html_entity_decode($this->session->data['vd_content'][$field_name], ENT_QUOTES, 'UTF-8'), true);

                $content = $this->model_extension_d_visual_designer_designer->parseSetting($setting);

                $this->model_extension_d_visual_designer_designer->saveContent($content, $this->codename, $this->request->get['module_id'], rawurldecode($field_name));
            }
            unset($this->session->data['vd_content']);
        }
    }
}
