<?php
class ControllerExtensionModuleDVisualDesignerModule extends Controller
{
    private $codename = 'd_visual_designer_module';
    
    private $route = 'extension/module/d_visual_designer_module';

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->module_name = (VERSION >= '3.0.0.0'?'module_': '') .$this->codename;
    }
    
    public function index($setting)
    {
        $this->load->model('extension/d_opencart_patch/load');

        $data['module_id'] = $setting['module_id'];

        $data['description'] = '';

        return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
    }
}
