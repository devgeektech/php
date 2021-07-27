<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleTwitterWidget extends Controller
{
    private $codename = 'twitter_widget';
    private $route = 'extension/d_visual_designer_module/twitter_widget';

    public function scripts($permission)
    {
        $data = array();

        $data[] = '//platform.twitter.com/widgets.js';

        return $data;
    }
}
