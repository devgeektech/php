<?php

/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleButton extends Controller
{
    private $codename = 'button';
    private $route = 'extension/d_visual_designer_module/button';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
    }

    public function index($setting)
    {
        switch ($setting['action']) {
            case 'link':
                $data['action'] = "location.href='" . $setting['link'] . "'";
                break;
            case 'buy':
                $data['action'] = "cart.add('" . $setting['product_id'] . "', '" . $setting['quantity'] . "')";
                break;
            default:
                $data['action'] = 'return false;';
        }
        if (!empty($setting['font_family'])) {
            $data['font_href'] = 'https://fonts.googleapis.com/css?family=' . $setting['font_family'] . ':';

            if (!empty($setting['bold'])) {
                $data['font_href'] .= '700';
            }
            if (!empty($setting['italic'])) {
                $data['font_href'] .= 'i';
            }
            $data['font_href'] .= '&subset=cyrillic,cyrillic-ext,greek,greek-ext,hebrew,latin-ext,vietnamese';
        }

        return $data;
    }

    public function styles($permission) {
        $data = array();
        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/button.css';
        return $data;
    }
}
