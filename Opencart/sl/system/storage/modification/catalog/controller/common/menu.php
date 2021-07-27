<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}


			$data['is_store_page'] = false;
			if (isset($this->request->get['route'])) {
				$route = $this->request->get['route'];
				$store_routes = array('account/store', 'account/account', 'account/edit', 'account/password', 'account/edit', 'account/order', 'account/order/info', 'account/return', 'account/address', 'account/address/edit', 'account/wishlist', 'account/downloads', 'account/recurring', 'account/recurring/info', 'account/reward', 'account/return', 'account/return/info', 'account/newsletter');
				if ($this->customer->isLogged()) {
					$store_routes[] = 'product/product';
				}
            	$data['current_route'] = $this->request->get['route'];
				if (in_array($route, $store_routes)) {
					$data['is_store_page'] = true;
				}
			}
            if (isset($this->request->get['information_id'])) {
                $data['information_id'] = $this->request->get['information_id'];
            }

            $data['our_farm_link'] = $this->url->link('information/information&information_id=4', '', true);
            $data['our_csa_link'] = $this->url->link('csa/csa', '', true);
            $data['marketplace_link'] = $this->url->link('information/information&information_id=9', '', true);
            $data['faq_link'] = $this->url->link('information/information&information_id=7', '', true);
                
                $data['route'] = '';
                if(!empty($this->request->get['route'])) {
                    $data['route'] = $this->request->get['route'];
                }
                
                if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
                    $url = "https://";   
                else
                    $url = "http://"; 
                
		// Append the host(domain name, ip) to the URL.   
		$url.= $_SERVER['HTTP_HOST'];   
		// Append the requested resource location to the URL   
		$url.= $_SERVER['REQUEST_URI'];    
		   $data['c_url'] = $url;
		   
		$data['login'] = $this->url->link('account/store', '', true);
		$data['home'] = $this->url->link('common/home', '', true);
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['base_url'] = $this->config->get('config_ssl');
		} else {
			$data['base_url'] = $this->config->get('config_url');
		}
		$data['logged'] = $this->customer->isLogged();
		$data['title'] = $this->document->getTitle();
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['account'] = $this->url->link('account/account', '', true);
		$data['name'] = $this->customer->getFirstName();

		$this->load->model('account/customer');
		$user_info = $this->model_account_customer->getCustomer($this->customer->getId());
                if ($user_info) {
                    $data['date_added'] = date("F j, Y", strtotime($user_info['date_added']));
                }                
                
			
		return $this->load->view('common/menu', $data);
	}
}
