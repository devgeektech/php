<?php
class ControllerExtensionDSEOModuleDVisualDesignerLanding extends Controller
{
    private $codename = 'd_visual_designer_landing';
    private $route = 'extension/d_seo_module/d_visual_designer_landing';
    private $config_file = 'd_visual_designer_landing';
    private $error = array();

    public function language_add_language($data)
    {
        $this->load->model($this->route);
        
        $this->{'model_extension_d_seo_module_' . $this->codename}->addLanguage($data);
    }
    
    public function language_delete_language($data)
    {
        $this->load->model($this->route);
        
        $this->{'model_extension_d_seo_module_' . $this->codename}->deleteLanguage($data);
    }

    public function field_config($data)
    {
        $_language = new Language();
        $_language->load($this->route);
        
        $_config = new Config();
        $_config->load($this->config_file);
        $field_setting = ($_config->get($this->codename . '_field_setting')) ? $_config->get($this->codename . '_field_setting') : array();

        foreach ($field_setting['sheet'] as $sheet) {
            if (substr($sheet['name'], 0, strlen('text_')) == 'text_') {
                $field_setting['sheet'][$sheet['code']]['name'] = $_language->get($sheet['name']);
            }
            
            foreach ($sheet['field'] as $field) {
                if (substr($field['name'], 0, strlen('text_')) == 'text_') {
                    $field_setting['sheet'][$sheet['code']]['field'][$field['code']]['name'] = $_language->get($field['name']);
                }
                
                if (substr($field['description'], 0, strlen('help_')) == 'help_') {
                    $field_setting['sheet'][$sheet['code']]['field'][$field['code']]['description'] = $_language->get($field['description']);
                }
            }
        }
        
        return $field_setting;
    }
    
    public function field_elements($filter_data)
    {
        $this->load->model($this->route);

        return $this->{'model_extension_d_seo_module_' . $this->codename}->getFieldElements($filter_data);
    }
    
    public function custom_page_exception_routes()
    {
        $_config = new Config();
        $_config->load($this->config_file);
        $config_setting = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();
            
        return $config_setting['custom_page_exception_routes'];
    }
}
