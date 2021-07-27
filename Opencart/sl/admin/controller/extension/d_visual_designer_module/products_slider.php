<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleProductsSlider extends Controller
{
    private $codename = 'products_slider';
    private $route = 'extension/d_visual_designer_module/products_slider';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/url');
    }

    public function setting($setting)
    {
        $data['products'] = array();
        if (!empty($setting['products'])) {
            $this->load->model('catalog/product');
            foreach ($setting['products'] as $product_id) {
                
                $product_info = $this->model_catalog_product->getProduct($product_id);

                $name = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                $data['products'][] = array(
                    'id' => $product_info['product_id'],
                    'name' => $name
                );
            }
        }
        $data['categories'] = array();
        if (!empty($setting['categories'])) {
            $this->load->model('catalog/category');
            foreach ($setting['categories'] as $category_id) {
                
                $category_info = $this->model_catalog_category->getCategory($category_id);

                $data['categories'][] = array(
                    'id' => $category_info['category_id'],
                    'name' => html_entity_decode(($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name'], ENT_QUOTES, 'UTF-8')
                );
            }
        }

        return $data;
    }

    public function local()
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_count'] = $this->language->get('entry_count');
        $data['entry_interval'] = $this->language->get('entry_interval');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_mode'] = $this->language->get('entry_mode');
        $data['entry_count_product'] = $this->language->get('entry_count_product');
        $data['entry_products'] = $this->language->get('entry_products');
        $data['entry_categories'] = $this->language->get('entry_categories');
        
        return $data;
    }

    public function options()
    {
        $data['modes'] = array(
            'latest' => $this->language->get('text_latest'),
            'bestseller' => $this->language->get('text_bestseller'),
            'popular' => $this->language->get('text_popular'),
            'special' => $this->language->get('text_special'),
            'custom_products' => $this->language->get('text_custom_products'),
            'custom_categories' => $this->language->get('text_custom_categories')
        );


        $data['autocomplete_products'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/autocompleteProducts');
        $data['autocomplete_categories'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/autocompleteCategories');
        
        return $data;
    }

    public function autocompleteProducts()
    {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_id'])) {
            $this->load->model('catalog/product');

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

            if (!$filter_product_id) {
                $filter_data = array(
                    'filter_name' => $filter_name,
                    'start' => 0,
                    'limit' => $limit
                );

                $results = $this->model_catalog_product->getProducts($filter_data);
            } else {
                $results = array($this->model_catalog_product->getProduct($filter_product_id));
            }

            foreach ($results as $result) {
                $option_data = array();
                $json[] = array(
                    'id' => $result['product_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocompleteCategories()
    {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_id'])) {
            $this->load->model('catalog/category');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_id'])) {
                $filter_category_id = $this->request->get['filter_id'];
            } else {
                $filter_category_id = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            if (!$filter_category_id) {
                $filter_data = array(
                    'filter_name' => $filter_name,
                    'start' => 0,
                    'limit' => $limit
                );
                $results = $this->model_catalog_category->getCategories($filter_data);
            } else {
                $results = array($this->model_catalog_category->getCategory($filter_category_id));
            }
            foreach ($results as $result) {

                $json[] = array(
                    'id' => $result['category_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
