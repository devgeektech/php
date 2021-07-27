<?php
/*
*   location: admin/controller
*/

class ControllerExtensionDVisualDesignerModuleProductsSlider extends Controller
{
    private $codename = 'products_slider';
    private $route = 'extension/d_visual_designer_module/products_slider';
    
    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function index($setting)
    {
        switch ($setting['mode']) {
            case 'latest':
            $products = $this->model_extension_d_visual_designer_module_products_slider->getLatestProducts($setting['count_product']);
            break;
            
            case 'special':
            $products = $this->model_extension_d_visual_designer_module_products_slider->getProductSpecials($setting['count_product']);
            break;
            
            case 'popular':
            $products = $this->model_extension_d_visual_designer_module_products_slider->getPopularProducts($setting['count_product']);
            break;
            case 'bestseller':
            $products = $this->model_extension_d_visual_designer_module_products_slider->getBestSellerProducts($setting['count_product']);
            break;
            case 'custom_categories':
            $products = array();
            
            if (!empty($setting['categories'])) {
                $products = $this->model_extension_d_visual_designer_module_products_slider->getCategoryProducts($setting['count_product'], $setting['categories']);
            }
            break;
        case 'custom_products':
            $products = array();
            $this->load->model('catalog/product');
            if (!empty($setting['products'])) {
                foreach ($setting['products'] as $product_id) {
                    $products[] = $this->model_catalog_product->getProduct($product_id);
                }
            }
            break;
            
            default:
            $products = array();
            break;
        }

        if (VERSION < '2.2.0.0') {
            $width = $this->config->get('config_image_thumb_width');
            $height = $this->config->get('config_image_thumb_height');
        } elseif (VERSION < '3.0.0.0') {
            $width = $this->config->get($this->config->get('config_theme') . '_image_thumb_width');
            $height = $this->config->get($this->config->get('config_theme') . '_image_thumb_height');
        } else {
            $width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width');
            $height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height');
        }
        
        $this->load->model('tool/image');
        $data['products'] = array();
        if ($products) {
            foreach ($products as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $width, $height);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $width, $height);
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                    $price_cur = $this->currency->format($price, $this->session->data['currency']);
                    if ($this->currency->has($this->session->data['currency'])) {
                        $price = $price * $this->currency->getValue($this->session->data['currency']);
                        $price = round($price, (int)$this->currency->getDecimalPlace($this->session->data['currency']));
                    }
                } else {
                    $price = false;
                    $price_cur = false;
                }

                if ((float)$result['special']) {
                    $special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
                    $special_cur = $this->currency->format($special, $this->session->data['currency']);
                    if ($this->currency->has($this->session->data['currency'])) {
                        $special = $special * $this->currency->getValue($this->session->data['currency']);
                        $special = round($special, (int)$this->currency->getDecimalPlace($this->session->data['currency']));
                    }
                } else {
                    $special = false;
                    $special_cur = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = (float)$result['special'] ? $result['special'] : $result['price'];
                    $tax_cur = $this->currency->format($tax, $this->session->data['currency']);
                    if ($this->currency->has($this->session->data['currency'])) {
                        $tax = $tax * $this->currency->getValue($this->session->data['currency']);
                        $tax = round($tax, (int)$this->currency->getDecimalPlace($this->session->data['currency']));
                    }
                } else {
                    $tax = false;
                    $tax_cur = false;
                }
                
                if ($this->config->get('config_review_status')) {
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }

                if (VERSION < '2.2.0.0') {
                    $description_limit = $this->config->get('config_product_description_length');
                } elseif (VERSION < '3.0.0.0') {
                    $description_limit = $this->config->get($this->config->get('config_theme').'_product_description_length');
                } else {
                    $description_limit = $this->config->get('theme_'.$this->config->get('config_theme').'_product_description_length');
                }

                $name = html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8');
                $description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $description_limit) . '..';

                $partial_data = array(
                    'product_id' => $result['product_id'],
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    'thumb' => $image,
                    'name' => html_entity_decode($name, ENT_QUOTES, 'UTF-8'),
                    'description' => $description,
                    'rating' => $rating,
                    'price' => $price_cur,
                    'special' => $special_cur,
                    'tax' => $tax_cur
                );

                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'partial' => $this->model_extension_d_opencart_patch_load->view('partial/d_product_thumb', $partial_data),
                    'thumb'       => $image,
                    'name'        => $name,
                    'description' => $description,
                    'manufacturer' => $result['manufacturer'],
                    'price'       => $price,
                    'symbolLeft' => $this->currency->getSymbolLeft($this->session->data['currency']),
                    'symbolRight' => $this->currency->getSymbolRight($this->session->data['currency']),
                    'attribute_groups' => $this->model_extension_d_visual_designer_module_products_slider->getProductAttributes($result['product_id']),
                    'wishlist' => $this->model_extension_d_visual_designer_module_products_slider->hasWishList($result['product_id']),
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }
        }

        return $data;
    }


    public function local($permission)
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_mode'] = $this->language->get('entry_mode');

        $data['button_cart'] = $this->language->get('button_cart');

        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_wishlist'] = $this->language->get('button_wishlist');

        $data['text_tax'] = $this->language->get('text_tax');

        return $data;
    }
}
