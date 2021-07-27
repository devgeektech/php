<?php
class ModelExtensionDVisualDesignerModuleProduct extends Model {
    public function hasWishList($product_id) {
        if (VERSION < '2.1.0.0') {
            if(isset($this->session->data['wishlist']) && in_array($product_id, $this->session->data['wishlist'])) {
                return true;
            } else {
                return false;
            }
        } else {
            if($this->customer->isLogged()) {
                $this->load->model('account/wishlist');
                $results = $this->model_account_wishlist->getWishlist();
                $products = array_filter($results, function($product) use ($product_id) {
                    return $product['product_id'] == $product_id;
                });
                if(!empty($products)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if(isset($this->session->data['wishlist']) && in_array($product_id, $this->session->data['wishlist'])) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}