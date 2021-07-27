<?php
/*
*location: admin/controller
*/

class ControllerExtensionDVisualDesignerModuleFeedback extends Controller
{
    private $codename = 'feedback';
    private $route = 'extension/d_visual_designer_module/feedback';
    
    private $error = array();
    
    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    public function local()
    {
        $data['entry_display_name'] = $this->language->get('entry_display_name');
        $data['entry_display_phone'] = $this->language->get('entry_display_phone');
        $data['entry_custom_field'] = $this->language->get('entry_custom_field');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_value'] = $this->language->get('entry_value');
        $data['entry_regex'] = $this->language->get('entry_regex');
        $data['entry_additional_inputs'] = $this->language->get('entry_additional_inputs');
        $data['entry_mode'] = $this->language->get('entry_mode');
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_link'] = $this->language->get('entry_link');
        
        $data['button_add'] = $this->language->get('button_add');
        $data['button_remove'] = $this->language->get('button_remove');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        return $data;
    }

    public function options() {
        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();
        
        foreach ($data['languages'] as $code => $value) {
            $data['languages'][$code]['flag'] = 'language/'.$code.'/'.$code.'.png';
        }

        $data['modes'] = array(
            'download_file' => $this->language->get('entry_download_file'),
            'code' => $this->language->get('entry_mode_code')
        );

        return $data;
    }
}
