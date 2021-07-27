<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionModuleDVisualDesignerModule extends Controller {
    private $codename = 'd_visual_designer_module';
    private $route = 'extension/module/d_visual_designer_module';
    private $extension = '';
    private $store_id = 0;
    private $error = array(); 
    
    public function __construct($registry) {
        parent::__construct($registry);
        
        $this->load->model($this->route);
        $this->load->language($this->route);
        
        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->d_opencart_patch = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_opencart_patch.json'));
        $this->d_twig_manager = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_twig_manager.json'));
        $this->d_visual_designer = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_visual_designer.json'));
        $this->d_admin_style = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_admin_style.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/'.$this->codename.'.json'), true);
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
    }
    
    public function index() {

        if($this->d_twig_manager){
            $this->load->model('extension/module/d_twig_manager');
            $this->model_extension_module_d_twig_manager->installCompatibility();
        }

        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
        }

        if($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getAdminStyle('light');
        }

        $this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');

        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/setting');

        $this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');
        $this->document->addScript('view/javascript/d_alertify/alertify.min.js');
        $this->document->addStyle('view/javascript/d_alertify/css/alertify.min.css');
        $this->document->addStyle('view/javascript/d_alertify/css/themes/bootstrap.min.css');

        $data['version'] = $this->extension['version'];

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['setup'] = false;

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['help_designer_support'] = $this->language->get('help_designer_support');
        $data['text_install_designer_support'] = $this->language->get('text_install_designer_support');

        //welcome
        $data['text_setup'] = $this->language->get('text_setup');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->model_extension_d_opencart_patch_url->getExtensionLink('module')
            );

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title_main'),
                'href' => $this->model_extension_d_opencart_patch_url->link($this->route)
                );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title_main'),
                'href' => $this->model_extension_d_opencart_patch_url->link($this->route, 'module_id=' . $this->request->get['module_id'])
                );
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route.'/save');
        } else {
            $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route.'/save', 'module_id=' . $this->request->get['module_id']);
        }

        $data['cancel'] =$this->model_extension_d_opencart_patch_url->getExtensionLink('module');

        $data['setup_link'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/setup');

        $data['get_cancel'] = $this->model_extension_d_opencart_patch_url->getExtensionAjax('module');

        $data['module_link'] = $this->model_extension_d_opencart_patch_url->link($this->route);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_d_opencart_patch_module->getModule($this->request->get['module_id']);
            $data['module_id'] = $this->request->get['module_id'];
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }


        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        //languages
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();
        foreach ($data['languages'] as $key =>  $language){
            if(VERSION >= '2.2.0.0'){
                $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
            }else{
                $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
            }
        }

        $data['designer_installed'] = false;

        if ($this->d_visual_designer) {
            $this->load->model('extension/d_visual_designer/designer');
            $data['designer_installed'] = $this->model_extension_d_visual_designer_designer->checkConfig($this->codename);
        }
        
        $setting = $this->model_extension_d_opencart_patch_setting->getSetting($this->codename);

        if(!empty($setting[$this->codename.'_install'])){
            $data['setup'] = true;
        }
        
        if (!isset($this->request->get['module_id'])) {
            $data['install_designer_support'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/installDesigner');
        } else {
            $data['install_designer_support'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/installDesigner', 'module_id=' . $this->request->get['module_id']);
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view($this->route, $data));
    }

    public function save()
    {
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/url');

        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->{'model_extension_module_'.$this->codename}->saveSuccess();

            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_d_opencart_patch_module->addModule($this->codename, $this->request->post);
                $this->request->get['module_id'] = $this->db->getLastId();
                $data['current_url'] = $this->model_extension_d_opencart_patch_url->ajax($this->route, 'module_id='.$this->request->get['module_id']);
                $data['action'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/save', 'module_id='.$this->request->get['module_id']);
                $data['module_id'] = $this->request->get['module_id'];
            } 

            $this->{'model_extension_module_'.$this->codename}->saveSuccess();

            $this->request->post['module_id'] = $this->request->get['module_id'];

            $this->model_extension_d_opencart_patch_module->editModule($this->request->get['module_id'], $this->request->post);
        }

        $data['error'] = $this->error;

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $this->response->setOutput(json_encode($data));
    }

    public function setup()
    {
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/setting');
        $this->load->model('extension/d_visual_designer/designer');

        $module_setting = array(
            'name' => $this->language->get('text_first_name'),
            'status' => 1
        );

        $this->model_extension_d_opencart_patch_module->addModule($this->codename, $module_setting);

        $module_id = $this->db->getLastId();
        

        $module_setting['module_id'] = $module_id;

        $this->model_extension_d_opencart_patch_module->editModule($module_id, $module_setting);

        if ($this->d_visual_designer) {
            $this->model_extension_d_visual_designer_designer->installConfig($this->codename);

            //$this->{'model_extension_module_'.$this->codename}->installTemplate($module_id, $this->config->get('config_language_id'), 'apple', 'apple');
        }  
        

        
        $this->model_extension_d_opencart_patch_setting->editSetting($this->codename, array(
            $this->codename.'_install' => array(
                'version' => $this->extension['version']
            )
        ));

        $layouts = $this->{'model_extension_module_'.$this->codename}->getLayoutsByRoute('common/home');

        if (!empty($layouts)) {
            $layout_info = $layouts[0];
            $this->{'model_extension_module_'.$this->codename}->addModuleToLayout($module_id, $layout_info['layout_id'], 'content_bottom', 0);
        }

        $this->session->data['success'] = $this->language->get('success_setup');
        $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route, 'module_id=' . $module_id));
    }

    public function installDesigner()
    {
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_visual_designer/designer');

        if ($this->d_visual_designer) {
            $this->model_extension_d_visual_designer_designer->installConfig($this->codename);
        }

        $this->model_extension_d_opencart_patch_module->addModule($this->codename, array(
            'name' => $this->language->get('text_first_name'),
            'status' => 1
        ));

        $module_id = $this->db->getLastId();

        $this->session->data['success'] = $this->language->get('success_setup');
        $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route, 'module_id=' . $module_id));

    }

    public function install(){
        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);  
        }
        if($this->d_visual_designer){
            $this->load->model('extension/module/d_visual_designer');
            $this->model_extension_module_d_visual_designer->compressRiotTag();
        }
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        // if(!empty($this->error)){
        //     $this->error['warning'] = $this->language->get('error_warning');
        // }

        return !$this->error;
    }
}