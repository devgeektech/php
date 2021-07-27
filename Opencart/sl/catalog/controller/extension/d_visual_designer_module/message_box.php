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

    public function index($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');

        return $data;
    }
}
