<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTwitterLike extends Controller
{
    private $codename = 'twitter_like';
    private $route = 'extension/d_visual_designer_module/twitter_like';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
}
