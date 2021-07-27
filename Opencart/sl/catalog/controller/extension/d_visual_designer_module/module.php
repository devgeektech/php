<?php
/*
 *  location: admin/controller
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
    }

    public function index($setting)
    {
        $part = explode('.', $setting['code']);
        $data = array();

        $data['text'] = '';

        if (isset($part[0]) && ($this->config->get($part[0] . '_status') || $this->config->get('module_' . $part[0] . '_status'))) {
            if (VERSION >= '2.3.0.0') {
                $data['text'] = $this->load->controller('extension/module/' . $part[0]);
            } else {
                $data['text'] = $this->load->controller('module/' . $part[0]);
            }
        }
        $scripts_before = $this->document->getScripts();
        $styles_before = $this->document->getStyles();
        if (isset($part[1])) {
            $setting_info = $this->model_extension_d_visual_designer_module_module->getModule($part[1]);
            if ($setting_info && $setting_info['status']) {
                if (VERSION >= '2.3.0.0') {
                    $data['text'] = $this->load->controller('extension/module/' . $part[0], $setting_info);
                } else {
                    $data['text'] = $this->load->controller('module/' . $part[0], $setting_info);
                }
            }
        }
        $scripts_after = $this->document->getScripts();
        $styles_after = $this->document->getStyles();
        $data['scripts'] = array_diff_key($scripts_after, $scripts_before);
        $data['styles'] = array_diff_key($styles_after, $styles_before);

        return $data;
    }
}
