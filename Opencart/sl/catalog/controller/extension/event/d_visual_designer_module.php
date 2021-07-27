<?php
class ControllerExtensionEventDVisualDesignerModule extends Controller
{
    private $codename = 'd_visual_designer_module';

    private $route = 'extension/module/d_visual_designer_module';

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('extension/module/d_visual_designer');
    }

    public function view_before(&$view, &$data)
    {
        $designer_data = array(
            'config' => $this->codename,
            'content' => '',
            'header' => &$data['header'],
            'field_name' => 'description['.(int)$this->config->get('config_language_id').'][description]',
            'id' => $data['module_id']
        );
        $data['description'] = $this->load->controller('extension/d_visual_designer/designer', $designer_data);

        $data['description'] = html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8');
    }
}
