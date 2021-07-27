<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleProduct extends Controller
{
    private $codename = 'product';
    private $route = 'extension/d_visual_designer_module/product';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/url');
    }
    
    public function local()
    {
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_style'] = $this->language->get('entry_style');

        return $data;
    }

    public function options()
    {
        $data['styles'] = array(
            'default' => $this->language->get('text_style_default')
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
}
