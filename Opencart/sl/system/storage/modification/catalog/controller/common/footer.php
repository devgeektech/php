<?php
class ControllerCommonFooter extends Controller {
	public function index() {
$data['mailchimp_integration'] = $this->load->controller('extension/module/mailchimp_integration/popup');
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = array();

				$data['logged'] = $this->customer->isLogged();
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
				$data['contact_link'] = $this->url->link('information/contact', '', true);
				$data['our_csa_link'] = $this->url->link('csa/csa', '', true);
				$data['marketplace_link'] = $this->url->link('information/information&information_id=9', '', true);
				$data['login_link'] = $this->url->link('account/store', '', true);
			

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['copyright'] = '&copy; '.date('Y', time()).' '.$this->config->get('config_name');
		$data['powered'] = $this->language->get('text_powered');
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');
		
		return $this->load->view('common/footer', $data);
	}
}
