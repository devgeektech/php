<?php

class ControllerAccountStore extends Controller {

    private $error = array();

    public function index() {

        $this->load->language('account/store');
        $this->load->model('account/customer');
        $this->load->model('account/customer_group');
        $this->load->model('account/address');

        // Login override for admin users
        if (!empty($this->request->get['token'])) {
            $this->customer->logout();
            $this->cart->clear();

            unset($this->session->data['order_id']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);

            $customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
                // Default Addresses
                $this->load->model('account/address');

                if ($this->config->get('config_tax_customer') == 'payment') {
                    $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                if ($this->config->get('config_tax_customer') == 'shipping') {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                $this->response->redirect($this->url->link('account/store', '', true));
            }
        }

        if ($this->customer->isLogged()) {
            $this->document->setTitle($this->language->get('heading_title'));
        } else {
            $this->document->setTitle($this->language->get('heading_title_login'));
        }

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $store_url = $this->config->get('config_ssl');
        } else {
            $store_url = $this->config->get('config_url');
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // Unset guest
            unset($this->session->data['guest']);
// Clear Thinking: MailChimp Integration Pro
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'module_';
				if ($this->config->get($prefix . 'mailchimp_integration_sendcarts') && $this->cart->hasProducts()) {
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->sendCart($this->cart, (int)$this->customer->getId());
				}
				// end

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Wishlist
            if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
                $this->load->model('account/wishlist');

                foreach ($this->session->data['wishlist'] as $key => $product_id) {
                    $this->model_account_wishlist->addWishlist($product_id);

                    unset($this->session->data['wishlist'][$key]);
                }
            }

            // Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
            if (isset($this->request->post['redirect']) && ($this->request->post['redirect'] != $store_url) && $this->request->post['redirect'] != $this->url->link('account/logout', '', true) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
                $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
            } else {
                $this->response->redirect($this->url->link('account/store', '', true));
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/store', '', true)
        );

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } elseif (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('account/store', '', true);
        $data['register'] = $this->url->link('account/register', '', true);
        $data['forgotten'] = $this->url->link('account/forgotten', '', true);

        // Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
        if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
            $data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
            $data['redirect'] = $this->session->data['redirect'];

            unset($this->session->data['redirect']);
        } else {
            $data['redirect'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        if ($customer_info) {
            $data['customer_info'] = $customer_info;
            $address_id = $this->customer->getAddressId();
            $address = $this->model_account_address->getAddress($address_id);
            $data['customer_address'] = $address;
        }

        $data['img_folder_path'] = $store_url . 'image/';
        $data['account'] = $this->url->link('account/store', '', true);

        $this->load->model('catalog/product');
        
        //check if admin is logged in as a customer 
        $data['harvest_seasons'] = array();
        $data['customer_groups'] = array();
        if (!empty($this->session->data['user_id'])) {
            $data['harvest_seasons'] = $this->model_catalog_product->getHarvestList(array('sort' => 'start_date', 'order' => 'DESC'));
            $data['customer_groups'] = $this->model_account_customer_group->getCustomerGroups();
        }
        
        //$data['is_marketplace_available'] = $this->model_catalog_product->checkMarketplaceAvailability();
        $data['harvest_id'] = $harvest_id = $this->customer->harvestId();
        $data['customer_group_id'] = $this->customer->getGroupId();
        
        $data['harvests'] = $this->model_catalog_product->getHarvestDetails($harvest_id);
        $man_product = $this->model_catalog_product->satisfied_mandatory_purchases_all($harvest_id);
        
        $data['is_marketplace_available'] = FALSE;
        $data['is_mandatory_suggested'] = 2; //suggested
        if (empty($man_product['found_in_past_order'])) {
            if (!empty($man_product['mandatory_product_id'])) {
                $data['is_mandatory_suggested'] = 1; //mandatory product
            } else {
                $data['is_marketplace_available'] = TRUE;
            }
        } else {
            $data['is_marketplace_available'] = TRUE;
        }
        

        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (!$this->customer->isLogged()) {
            $this->response->setOutput($this->load->view('account/login', $data));
        } else {
            $this->response->setOutput($this->load->view('account/store', $data));
        }
    }
    
    public function mandatory_shares() {
        $this->load->model('catalog/product');
        $harvest_id = $this->customer->harvestId();
        $man_product = $this->model_catalog_product->satisfied_mandatory_purchases_all($harvest_id);
        if (!empty($man_product['mandatory_product_id'])) {
            $this->response->redirect($this->url->link('product/product', 'product_id=' . $man_product['mandatory_product_id']));
        } else {
            if(isset($man_product['mandatory_products'][0]['product_id'])) { //load default mandatory product
                $this->response->redirect($this->url->link('product/product', 'product_id=' . $man_product['mandatory_products'][0]['product_id']));
            }
        }
    }
    
    protected function validate() {
        // Check how many login attempts have been made.
        $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

        if ($customer_info && !$customer_info['status']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        if (!$this->error) {
            if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($this->request->post['email']);
            } else {
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
            }
        }

        return !$this->error;
    }
    
    public function change_csa() {
        $json = array();
        if (isset($this->request->get['customer_group_id'])) {
            $customer_group_id = $this->request->get['customer_group_id'];
            $this->customer->setCustomerGroupId($customer_group_id);
            $json['success'] = TRUE;
        } else {
            $json['error'] = 'Please Select CSA Again!';
        }
        $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode($json));
    }
    
    public function change_harvest() {
        $json = array();
        if (isset($this->request->get['harvest_id'])) {
            $harvest_id = $this->request->get['harvest_id'];
            $this->customer->setHarvestId($harvest_id);
            
            //this function call will remove Cart items from the logged in customer if they dont belong to current season session.
            $this->load->model('catalog/product');
            $this->model_catalog_product->removeCartItemsNotFromSeason();
            
            $json['success'] = TRUE;
        } else {
            $json['error'] = 'Please Select Harvest Season Again!';
        }
        $this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode($json));
    }

}
