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
        $this->load->model('extension/d_opencart_patch/url');
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

        return $data;
    }


    public function local()
    {
        $data['entry_icon_library'] = $this->language->get('entry_icon_library');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_action'] = $this->language->get('entry_action');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_new_window'] = $this->language->get('entry_new_window');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_color_text'] = $this->language->get('entry_color_text');
        $data['entry_color_hover'] = $this->language->get('entry_color_hover');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_alignment'] = $this->language->get('entry_alignment');
        $data['entry_full_width'] = $this->language->get('entry_full_width');
        $data['entry_display_icon'] = $this->language->get('entry_display_icon');
        $data['entry_icon'] = $this->language->get('entry_icon');
        $data['entry_border_color'] = $this->language->get('entry_border_color');
        $data['entry_border_width'] = $this->language->get('entry_border_width');
        $data['entry_border_radius'] = $this->language->get('entry_border_radius');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_font_size'] = $this->language->get('entry_font_size');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_letter_spacing'] = $this->language->get('entry_letter_spacing');
        $data['entry_font_family'] = $this->language->get('entry_font_family');
        $data['entry_color_text_hover'] = $this->language->get('entry_color_text_hover');
        $data['entry_border_color_hover'] = $this->language->get('entry_border_color_hover');
        $data['entry_icon_color'] = $this->language->get('entry_icon_color');
        $data['entry_icon_align'] = $this->language->get('entry_icon_align');
        $data['entry_icon_only_hover'] = $this->language->get('entry_icon_only_hover');
        $data['entry_font_family'] = $this->language->get('entry_font_family');
        
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');

        return $data;
    }

    public function options()
    {
        $data = array();

        $data['libraries'] = array(
            'fontawesome' => 'Font Awesome',
            'glyphicon'   => 'Glyphicons',
            'ionicons'    => 'Open Ionic',
            'mapicons'    => 'Map Icons',
            'material'    => 'Material Design Iconic Font',
            'typeicon'    => 'Typeicons',
            'elusive'     => 'Elusive Icons',
            'octicon'     => 'Octicons',
            'weather'     => 'Weather Icons'
            );
            
        $data['actions'] = array(
            ''     => $this->language->get('text_no'),
            'link' => $this->language->get('text_link'),
            'buy'  => $this->language->get('text_buy')
            );
            
        $data['aligns'] = array(
            'left'   => $this->language->get('text_left'),
            'center' => $this->language->get('text_center'),
            'right'  => $this->language->get('text_right')
            );
            
        $data['icon_aligns'] = array(
            'left'   => $this->language->get('text_left'),
            'right'  => $this->language->get('text_right')
            );

        $data['autocomplete'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/autocomplete');
        
        return $data;
    }
    
    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_id'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_id'])) {
                $filter_product_id = $this->request->get['filter_id'];
            } else {
                $filter_product_id = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }
            
            if(!$filter_product_id) {
                $filter_data = array(
                    'filter_name'  => $filter_name,
                    'start'        => 0,
                    'limit'        => $limit
                );
    
                $results = $this->model_catalog_product->getProducts($filter_data);
            } else {
                $results = array($this->model_catalog_product->getProduct($filter_product_id));
            }

            foreach ($results as $result) {
                $option_data = array();
                $json[] = array(
                    'id'   => $result['product_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function catalog_styles($permission) {
        $data = array();
        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/button.css';
        return $data;
    }
}
