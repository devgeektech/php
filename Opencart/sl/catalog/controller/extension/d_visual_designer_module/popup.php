<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModulePopup extends Controller {
    private $codename = 'popup';
    private $route = 'extension/d_visual_designer_module/popup';

    public function styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_landing/popup/popup.css';

        return $data;
    }
}