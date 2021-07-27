<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleSocialicons extends Controller
{
    private $codename = 'social_icons';
    private $route = 'extension/d_visual_designer_module/social_icons';

    public function styles($permission)
    {
        $data = array();
        
        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_extra/social_icons/social_icons.css';

        return $data;
    }
}
