<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleModule extends Controller
{
    private $codename = 'module';
    private $route = 'extension/d_visual_designer_module/module';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/extension');
    }

    public function local()
    {
        $data['entry_module'] = $this->language->get('entry_module');

        return $data;
    }

    public function options()
    {
        $extensions = $this->model_extension_d_opencart_patch_extension->getInstalled('module');
        $data['extensions'] = array();
        foreach ($extensions as $code) {
            if(file_exists(DIR_APPLICATION.'controller/extension/module/'.$code.'.php')){
                $this->load->language('extension/module/' . $code);
            } else {
                $this->load->language('module/' . $code);
            }

            $module_data = array();

            $modules = $this->model_extension_d_opencart_patch_module->getModulesByCode($code);

            foreach ($modules as $module) {
                $module_data[] = array(
                    'name' => html_entity_decode(strip_tags($this->language->get('heading_title') . ' &gt; ' . $module['name']), ENT_QUOTES, 'UTF-8'),
                    'code' => $code . '.' .  $module['module_id']
                    );
            }

            if ($this->config->has($code . '_status') || $module_data) {
                $data['extensions'][] = array(
                    'name'   => html_entity_decode(strip_tags($this->language->get('heading_title')), ENT_QUOTES, 'UTF-8'),
                    'code'   => $code,
                    'module' => $module_data
                    );
            }
        }

        return $data;
    }
}
