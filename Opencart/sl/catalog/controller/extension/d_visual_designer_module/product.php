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
        $this->load->model($this->route);
        $this->load->model('extension/d_opencart_patch/load');
    }

    public function index($setting)
    {
        $data = array();
        if (!empty($setting['product_id'])) {
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

            $this->load->model('catalog/product');
            $this->load->model('tool/image');
            $product_info = $this->model_catalog_product->getProduct($setting['product_id']);

            if ($product_info['image']) {
                $image = $this->model_tool_image->resize($product_info['image'], $width, $height);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $width, $height);
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                $price_cur = $this->currency->format($price, $this->session->data['currency']);
                if ($this->currency->has($this->session->data['currency'])) {
                    $price = $price * $this->currency->getValue($this->session->data['currency']);
                    $price = round($price, (int)$this->currency->getDecimalPlace($this->session->data['currency']));
                }
            } else {
                $price = false;
                $price_cur = false;
            }

            if ((float)$product_info['special']) {
                $special = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
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
                $tax = (float)$product_info['special'] ? $product_info['special'] : $product_info['price'];
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
                $rating = $product_info['rating'];
            } else {
                $rating = false;
            }

            if (VERSION < '2.2.0.0') {
                $description_limit = $this->config->get('config_product_description_length');
            } elseif (VERSION < '3.0.0.0') {
                $description_limit = $this->config->get($this->config->get('config_theme') . '_product_description_length');
            } else {
                $description_limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length');
            }

            $description = utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $description_limit) . '..';

            $partial_data = array(
                'product_id' => $product_info['product_id'],
                'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                'thumb' => $image,
                'name' => html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8'),
                'description' => $description,
                'rating' => $rating,
                'price' => $price_cur,
                'special' => $special_cur,
                'tax' => $tax_cur
            );

            $data['product'] = array(
                'product_id' => $product_info['product_id'],
                'partial' => $this->model_extension_d_opencart_patch_load->view('partial/d_product_thumb', $partial_data),
                'thumb' => $image,
                'name' => html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8'),
                'description' => $description,
                'manufacturer' => $product_info['manufacturer'],
                'symbolLeft' => $this->currency->getSymbolLeft($this->session->data['currency']),
                'symbolRight' => $this->currency->getSymbolRight($this->session->data['currency']),
                'attribute_groups' => $this->model_catalog_product->getProductAttributes($product_info['product_id']),
                'price' => $price,
                'special' => $special,
                'tax' => $tax,
                'rating' => $rating,
                'wishlist' => $this->model_extension_d_visual_designer_module_product->hasWishList($product_info['product_id']),
                'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
            );
        }

        return $data;
    }

    public function styles($permission)
    {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_module/product.css';

        return $data;
    }
}
