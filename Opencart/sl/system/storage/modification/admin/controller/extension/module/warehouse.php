<?php
class ControllerExtensionModuleWarehouse extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/warehouse');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/warehouse');
		$this->model_extension_module_warehouse->createTable();
		$this->getList();
	}

	public function add() {
		$this->load->language('extension/module/warehouse');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/warehouse');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_warehouse->addwarehouse($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url, TRUE));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/module/warehouse');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/warehouse');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_warehouse->editwarehouse($this->request->get['warehouse_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url, TRUE));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/module/warehouse');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/warehouse');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $warehouse_id) {
				$this->model_extension_module_warehouse->deletewarehouse($warehouse_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url, TRUE));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'l.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['links'] = $this->links();

		$data['breadcrumbs'] =   array();

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], TRUE)
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title'),
			'href' =>  $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url, TRUE)
		);

		$data['add'] = $this->url->link('extension/module/warehouse/add', 'user_token=' . $this->session->data['user_token'] . $url, TRUE);
		$data['delete'] = $this->url->link('extension/module/warehouse/delete', 'user_token=' . $this->session->data['user_token'] . $url, TRUE);

		$data['location'] = $this->url->link('extension/module/warehouseimport', 'user_token=' . $this->session->data['user_token'] . $url, TRUE);

		$this->document->addScript('view/javascript/jquery/warehouse.js');

		$data['warehouses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$warehouse_total = $this->model_extension_module_warehouse->getTotalwarehouses();

		$results = $this->model_extension_module_warehouse->getwarehouses($filter_data);
		$this->load->model("tool/image");
		foreach ($results as $result) {

			if (is_file(DIR_IMAGE . $result['contactperson_image'])) {
				$image = $this->model_tool_image->resize($result['contactperson_image'], 80, 80);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 80, 80);
			}

			$data['warehouses'][] =   array(
				'warehouse_id' => $result['warehouse_id'],
				'name'        => $result['name'],
				'sort_order'        => $result['sort_order'],
				'contactperson_name'        => $result['contactperson_name'],
				'contactperson_image'        => $image,
				'contactperson_mobile'        => $result['contactperson_mobile'],
				'contactperson_phone'        => $result['contactperson_phone'],
				'geolocation'        => html_entity_decode($result['geolocation']),
				'zonename'        => $this->model_extension_module_warehouse->getZoneName($result['zone_id']),
				'edit'        => $this->url->link('extension/module/warehouse/edit', 'user_token=' . $this->session->data['user_token'] . '&warehouse_id=' . $result['warehouse_id'] . $url, TRUE)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, TRUE);
		$data['sort_address'] = $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . '&sort=address' . $url, TRUE);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $warehouse_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', TRUE);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($warehouse_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($warehouse_total - $this->config->get('config_limit_admin'))) ? $warehouse_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $warehouse_total, ceil($warehouse_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


   $data['oc_licensing_home'] = 'https://www.cartbinder.com/store/'; $data['extension_id'] = 33319; $admin_support_email = 'support@cartbinder.com'; $data['license_purchase_thanks'] = sprintf($this->language->get('license_purchase_thanks'), $admin_support_email); if(isset($this->request->get['emailmal'])){ $data['emailmal'] = true; } if(isset($this->request->get['regerror'])){ if($this->request->get['regerror']=='emailmal'){ $this->error['warning'] = $this->language->get('regerror_email'); }elseif($this->request->get['regerror']=='orderidmal'){ $this->error['warning'] = $this->language->get('regerror_orderid'); }elseif($this->request->get['regerror']=='noreferer'){ $this->error['warning'] = $this->language->get('regerror_noreferer'); }elseif($this->request->get['regerror']=='localhost'){ $this->error['warning'] = $this->language->get('regerror_localhost'); }elseif($this->request->get['regerror']=='licensedupe'){ $this->error['warning'] = $this->language->get('regerror_licensedupe'); } } $domainssl = explode("//", HTTPS_SERVER); $domainnonssl = explode("//", HTTP_SERVER); $domain = ($domainssl[1] != '' ? $domainssl[1] : $domainnonssl[1]); $data['domain'] = $domain; $data['licensed'] = @file_get_contents($data['oc_licensing_home'] . 'licensed.php?domain=' . $domain . '&extension=' . $data['extension_id']); if(!$data['licensed'] || $data['licensed'] == ''){ if(extension_loaded('curl')) { $post_data = array('domain' => $domain, 'extension' => $data['extension_id']); $curl = curl_init(); curl_setopt($curl, CURLOPT_HEADER, false); curl_setopt($curl, CURLINFO_HEADER_OUT, true); curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17'); $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true; if ($follow_allowed) { curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); } curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 9); curl_setopt($curl, CURLOPT_TIMEOUT, 60); curl_setopt($curl, CURLOPT_AUTOREFERER, true); curl_setopt($curl, CURLOPT_VERBOSE, 1); curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); curl_setopt($curl, CURLOPT_FORBID_REUSE, false); curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); curl_setopt($curl, CURLOPT_URL, $data['oc_licensing_home'] . 'licensed.php'); curl_setopt($curl, CURLOPT_POST, true); curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data)); $data['licensed'] = curl_exec($curl); curl_close($curl); }else{ $data['licensed'] = 'curl'; } } $data['licensed_md5'] = md5($data['licensed']); $data['entry_free_support'] = $this->language->get('entry_free_support'); $order_details = @file_get_contents($data['oc_licensing_home'] . 'order_details.php?domain=' . $domain . '&extension=' . $data['extension_id']); $order_data = json_decode($order_details, true); if(!is_array($order_data) || $order_data == ''){ if(extension_loaded('curl')) { $post_data2 = array('domain' => $domain, 'extension' => $data['extension_id']); $curl2 = curl_init(); curl_setopt($curl2, CURLOPT_HEADER, false); curl_setopt($curl2, CURLINFO_HEADER_OUT, true); curl_setopt($curl2, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17'); $follow_allowed2 = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true; if ($follow_allowed2) { curl_setopt($curl2, CURLOPT_FOLLOWLOCATION, 1); } curl_setopt($curl2, CURLOPT_CONNECTTIMEOUT, 9); curl_setopt($curl2, CURLOPT_TIMEOUT, 60); curl_setopt($curl2, CURLOPT_AUTOREFERER, true); curl_setopt($curl2, CURLOPT_VERBOSE, 1); curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, false); curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false); curl_setopt($curl2, CURLOPT_FORBID_REUSE, false); curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true); curl_setopt($curl2, CURLOPT_URL, $data['oc_licensing_home'] . 'order_details.php'); curl_setopt($curl2, CURLOPT_POST, true); curl_setopt($curl2, CURLOPT_POSTFIELDS, http_build_query($post_data2)); $order_data = json_decode(curl_exec($curl2), true); curl_close($curl2); }else{ $order_data['status'] = 'disabled'; } } if(isset($order_data['status']) && $order_data['status'] == 'enabled'){ $isSecure = false; if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) { $isSecure = true; } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') { $isSecure = true; } $data['support_status'] = 'enabled'; $data['support_order_id'] = $order_data['order_id']; $data['support_extension_name'] = $order_data['extension_name']; $data['support_domain'] = $order_data['domain']; $data['support_username'] = $order_data['username']; $data['support_email'] = $order_data['email']; $data['support_registered_date'] = strftime('%Y-%m-%d', $order_data['registered_date']); $data['support_order_date'] = strftime('%Y-%m-%d', ($order_data['order_date'] + 31536000)); if((time() - $order_data['order_date']) > 31536000){ $data['text_free_support_remaining'] = sprintf($this->language->get('text_free_support_expired'), 1, ($isSecure ? 1 : 0), urlencode($domain) , $data['extension_id'] , $this->session->data['token']); }else{ $data['text_free_support_remaining'] = sprintf($this->language->get('text_free_support_remaining'), 366 - ceil((time() - $order_data['order_date']) / 86400)); } }else{ $data['support_status'] = 'disabled'; $data['text_free_support_remaining'] = sprintf($this->language->get('text_free_support_remaining'), 'unknown'); }
        
		$this->response->setOutput($this->load->view('extension/module/warehouse_list', $data));
	}

	protected function getForm() {
		$this->load->model('extension/module/warehouse');
		$data['links'] = $this->links();

		$data['text_form'] = !isset($this->request->get['warehouse_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['user_token'] = $this->session->data['user_token'];
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], TRUE)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url, TRUE)
		);

		if (!isset($this->request->get['warehouse_id'])) {
			$data['action'] = $this->url->link('extension/module/warehouse/add', 'user_token=' . $this->session->data['user_token'] . $url, TRUE);
		} else {
			$data['action'] = $this->url->link('extension/module/warehouse/edit', 'user_token=' . $this->session->data['user_token'] .  '&warehouse_id=' . $this->request->get['warehouse_id'] . $url, TRUE);
		}

		$data['cancel'] = $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url, TRUE);

		if (isset($this->request->get['warehouse_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$warehouse_info = $this->model_extension_module_warehouse->getwarehouse($this->request->get['warehouse_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('setting/store');

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($warehouse_info)) {
			$data['name'] = $warehouse_info['name'];
		} else {
			$data['name'] =   '';
		}

		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (!empty($warehouse_info)) {
			$data['zone_id'] = $warehouse_info['zone_id'];
		} else {
			$data['zone_id'] =   '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($warehouse_info)) {
			$data['country_id'] = $warehouse_info['country_id'];
		} else {
			$data['country_id'] =   '';
		}

		if (isset($this->request->post['states'])) {
			
			// -- warehouse stock deduction rule
			$states = (is_array($this->request->post['states'])) ? $this->request->post['states'] : array();
			// -- end warehouse stock deduction rule
			
		} elseif (!empty($warehouse_info) && !empty($warehouse_info['zoneids'])) {
			$states = explode(",", $warehouse_info['zoneids']);
		} else {
			$states =  array();
		}

		$data['states'] = array();
		$this->load->model('localisation/zone');

		foreach ($states as $key => $value) {
			$zonedetails = $this->model_localisation_zone->getZone($value);
			if($zonedetails) {
				$data['states'][] = array(
					'zone_id' => $value,
					'name'       => strip_tags(html_entity_decode($zonedetails['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($warehouse_info)) {
			$data['image'] = $warehouse_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($warehouse_info) && is_file(DIR_IMAGE . $warehouse_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($warehouse_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (!empty($warehouse_info)) {
			$data['comment'] = $warehouse_info['comment'];
		} else {
			$data['comment'] = '';
		}


		if (isset($this->request->post['geolocation'])) {
			$data['geolocation'] = $this->request->post['geolocation'];
		} elseif (!empty($warehouse_info)) {
			$data['geolocation'] = $warehouse_info['geolocation'];
		} else {
			$data['geolocation'] =   '';
		}

		if (isset($this->request->post['contactperson_name'])) {
			$data['contactperson_name'] = $this->request->post['contactperson_name'];
		} elseif (!empty($warehouse_info)) {
			$data['contactperson_name'] = $warehouse_info['contactperson_name'];
		} else {
			$data['contactperson_name'] =   '';
		}

		if (isset($this->request->post['contactperson_image'])) {
			$data['contactperson_image'] = $this->request->post['contactperson_image'];
		} elseif (!empty($warehouse_info)) {
			$data['contactperson_image'] = $warehouse_info['contactperson_image'];
		} else {
			$data['contactperson_image'] =   '';
		}

		if (isset($this->request->post['contactperson_image']) && is_file(DIR_IMAGE . $this->request->post['contactperson_image'])) {
			$data['contactperson_thumb'] = $this->model_tool_image->resize($this->request->post['contactperson_image'], 100, 100);
		} elseif (!empty($warehouse_info) && is_file(DIR_IMAGE . $warehouse_info['contactperson_image'])) {
			$data['contactperson_thumb'] = $this->model_tool_image->resize($warehouse_info['contactperson_image'], 100, 100);
		} else {
			$data['contactperson_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['contactperson_mobile'])) {
			$data['contactperson_mobile'] = $this->request->post['contactperson_mobile'];
		} elseif (!empty($warehouse_info)) {
			$data['contactperson_mobile'] = $warehouse_info['contactperson_mobile'];
		} else {
			$data['contactperson_mobile'] =   '';
		}

		if (isset($this->request->post['contactperson_mobile'])) {
			$data['contactperson_mobile'] = $this->request->post['contactperson_mobile'];
		} elseif (!empty($warehouse_info)) {
			$data['contactperson_mobile'] = $warehouse_info['contactperson_mobile'];
		} else {
			$data['contactperson_mobile'] =   '';
		}

		if (isset($this->request->post['contactperson_phone'])) {
			$data['contactperson_phone'] = $this->request->post['contactperson_phone'];
		} elseif (!empty($warehouse_info)) {
			$data['contactperson_phone'] = $warehouse_info['contactperson_phone'];
		} else {
			$data['contactperson_phone'] =   '';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($warehouse_info)) {
			$data['sort_order'] = $warehouse_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/warehouse_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/warehouse')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 50)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {

		if (!$this->user->hasPermission('modify', 'extension/module/warehouse')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/module/warehouse');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_module_warehouse->getwarehouses($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'warehouse_id' => $result['warehouse_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function links() {
		$this->load->language("extension/module/warehouse");
		$links[0]['href']		= $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'], TRUE);	
		$links[0]['text']		= $this->language->get("text_menu_warehouses");
		$links[0]['type']		= "warning";
		$links[0]['font']		= "industry";
		$links[1]['href']		= $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'], TRUE);	
		$links[1]['text']		= $this->language->get("text_menu_transactions");
		$links[1]['type']		= "info";
		$links[1]['font']		= "exchange";
		$links[2]['href']		= $this->url->link('extension/module/warehouse/producteditview', 'user_token=' . $this->session->data['user_token'], TRUE);	
		$links[2]['text']		= $this->language->get("text_menu_productassignment");
		$links[2]['type']		= "primary";
		$links[2]['font']		= "rocket";
		$links[3]['href']		= $this->url->link('extension/module/warehouse/settings', 'user_token=' . $this->session->data['user_token'], TRUE);	
		$links[3]['text']		= $this->language->get("text_menu_settings");
		$links[3]['type']		= "success";
		$links[3]['font']		= "cogs";
		$links[4]['href']		= $this->url->link('extension/module/warehouse/getWarehouseImportForm', 'user_token=' . $this->session->data['user_token'], TRUE);	
		$links[4]['text']		= $this->language->get("text_menu_import");
		$links[4]['type']		= "default";
		$links[4]['font']		= "bolt";
		return $links;
	}

	public function orderStock() {
		$this->load->language("extension/module/warehouse");
		
		$this->load->model('sale/order');
		
		// Getting all warehouse

		$this->load->model('extension/module/warehouse');
		$data['warehouses'] = $this->model_extension_module_warehouse->getwarehouses();
		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$data['order_id'] = $this->request->get['order_id'];
			$data['user_token'] = $this->session->data['user_token'];

			// Uploaded files
			$this->load->model('tool/upload');
			$this->load->model('tool/image');

			$data['products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
			
			foreach ($products as $product) {

				//Getting current product stock in warehouse
				$stock_available_warehouse = $this->model_extension_module_warehouse->getGroupsById($product['product_id']);

				//Getting added product stock
				$stock_added_warehouse = $this->model_extension_module_warehouse->getWarehouseTransactionById($order_id,$product['order_product_id'],$product['product_id']);

				$data['products'][] = array(
					'key' => $product['order_product_id'].'_0',
					'order_product_id' => $product['order_product_id'],
					'order_option_id'  => 0,
					'product_option_value_id'  => 0,
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'stock_available_warehouse'    => $stock_available_warehouse,
					'stock_added_warehouse'    => $stock_added_warehouse,
					'model'    		   => $product['model'],
					'quantity'		   => $product['quantity'],
					'subtract'		   => $this->model_extension_module_warehouse->checkProductSubtract($product['product_id']),
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'], true)
				);

				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
				
				foreach ($options as $option) {
					
					$stock_available_warehouse = $this->model_extension_module_warehouse->getOptionsGroupsById($option['product_option_value_id']);
					
					//Getting added product stock
					$stock_added_warehouse = $this->model_extension_module_warehouse->getWarehouseTransactionById($order_id,$product['order_product_id'],$product['product_id'],$option['order_option_id'],$option['product_option_value_id']);

					if ($option['type'] != 'file') {
						$name = $option['name'] .": ".$option['value'];
					} else {
						$name = "";
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
						if ($upload_info) {
							$name = $option['name'] .": ".$upload_info['value'];
						}
					}

					$data['products'][] = array(
						'key' => $product['order_product_id'].'_'.$option['order_option_id'],
						'order_product_id' => $product['order_product_id'],
						'product_id'       => $product['product_id'],
						'order_option_id'  => $option['order_option_id'],
						'product_option_value_id'  => $option['product_option_value_id'],
						'name'    	 	   => $product['name']." > ".$name,
						'stock_available_warehouse'    => $stock_available_warehouse,
						'stock_added_warehouse'    => $stock_added_warehouse,
						'model'    		   => $product['model'],
						'quantity'		   => $product['quantity'],
						'subtract'		   => $this->model_extension_module_warehouse->checkProductOptionSubtract($option['product_option_value_id']),
						'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
						'href'     		   => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'], true)
					);
				}
			}
		}
		if (isset($this->request->get['listpage'])) {
			$json['html'] = $this->load->view('extension/module/warehouse_order', $data);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} else {
			return $this->load->view('extension/module/warehouse_order', $data);
		}
	}

	public function save() {
		$json = array();
		$this->load->language('extension/module/warehouse');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/warehouse');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateDelete()) {
			$this->model_extension_module_warehouse->saveOrderStockWarehouse($this->request->post);
			$json['success'] = "Saved Successfully";
		} else {
			$json['error'] = "Sorry !! No Permission to edit";
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function transaction() {

		$this->load->language("extension/module/warehouse");

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_warehouse'])) {
			$filter_warehouse = $this->request->get['filter_warehouse'];
		} else {
			$filter_warehouse = null;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_warehouse'])) {
			$url .= '&filter_warehouse=' . $this->request->get['filter_warehouse'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['links'] = $this->links();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title'),
			'href' =>  $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . $url, TRUE)
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title_transaction'),
			'href' =>  $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . $url, TRUE)
		);

		$data['transactions'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_order_id'	  => $filter_order_id,
			'filter_quantity' => $filter_quantity,
			'filter_warehouse'   => $filter_warehouse,
			'filter_date'    => $filter_date,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('extension/module/warehouse');
		$transaction_total = $this->model_extension_module_warehouse->getTotalTransactions($filter_data);

		$results = $this->model_extension_module_warehouse->getTransactions($filter_data);
		
		foreach ($results as $result) {

			$data['transactions'][] = array(
				'warehouse_transaction_id' => $result['warehouse_transaction_id'],
				'warehouse'       => $result['warehouse'],
				'name'       => $result['name'],
				'order_id'       => $result['order_id'],
				'order_href'       => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'], TRUE),
				'qty'      => $result['qty'],
				'date_added'      => $result['date_added'],
			);
		}
		$this->document->setTitle($this->language->get('heading_title_transaction'));
		$data['heading_title'] = $this->language->get('heading_title_transaction');


		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_warehouse'])) {
			$url .= '&filter_warehouse=' . $this->request->get['filter_warehouse'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . '&sort=wt.name' . $url, true);
		$data['sort_warehouse'] = $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . '&sort=w.warehouse' . $url, true);
		$data['sort_order_id'] = $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . '&sort=wt.order_id' . $url, true);
		$data['sort_quantity'] = $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . '&sort=wt.quantity' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . '&sort=wt.date_added' . $url, true);
		$data['sort_order'] = $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_warehouse'])) {
			$url .= '&filter_warehouse=' . $this->request->get['filter_warehouse'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/warehouse/transaction', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($transaction_total - $this->config->get('config_limit_admin'))) ? $transaction_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $transaction_total, ceil($transaction_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_warehouse'] = $filter_warehouse;
		$data['filter_date'] = $filter_date;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/warehouse_transaction', $data));
	}

	public function producteditview() {
		
		$this->load->language('extension/module/warehouse');
		$this->load->model('extension/module/warehouse');

		$this->load->model('catalog/product');
		$this->load->model('localisation/stock_status');
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title_producteditview'));
		
		$data['links'] = $this->links();
		$data['heading_title'] = $this->language->get('heading_title_producteditview');

		$data['user_token'] = $this->session->data['user_token'];
		$data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], TRUE);

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_sku'])) {
			$filter_sku = $this->request->get['filter_sku'];
		} else {
			$filter_sku = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}


		if (isset($this->request->get['filter_harvest_id'])) {
			$filter_harvest_id = $this->request->get['filter_harvest_id'];
                        $filter_zero_harvest_id = TRUE;
		} else {
                        $this->load->model('csa/harvests');
                        $result_current_harvest = $this->model_csa_harvests->getCurrentActiveHarvest();
			$filter_harvest_id = $result_current_harvest['harvest_id'];
                        $filter_zero_harvest_id = TRUE;
		}
                
                if (isset($this->request->get['filter_product_type'])) {
			$filter_product_type = $this->request->get['filter_product_type'];
		} else {
			$filter_product_type = '';
		}
            
		if (isset($this->request->get['filter_options'])) {
			$filter_options = $this->request->get['filter_options'];
		} else {
			$filter_options = 1;
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
		} else {
			$filter_manufacturer_id = null;
		}

		if (isset($this->request->get['filter_subtract'])) {
			$filter_subtract = $this->request->get['filter_subtract'];
		} else {
			$filter_subtract = 1;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		
		$filter_data = array(
			'filter_name'	  => $filter_name,

		'filter_harvest_id' => $filter_harvest_id,
                'filter_product_type' => $filter_product_type,
                'filter_zero_harvest_id' => $filter_zero_harvest_id,
            
			'filter_model'	  => $filter_model,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'filter_sku'   	  => $filter_sku,
			'filter_subtract'   	  => $filter_subtract,
			'filter_category_id' => $filter_category_id,
			'filter_manufacturer_id'   => $filter_manufacturer_id,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);	


  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], TRUE),     		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title'),
			'href' =>  $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'], TRUE)
		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_producteditview'),
			'href'      => $this->url->link('extension/module/warehouse/producteditview', 'user_token=' . $this->session->data['user_token'], TRUE),
      		'separator' => ' :: '
   		);
   		

		$data['warehouses'] = $this->model_extension_module_warehouse->getwarehouses();

		$data['results'] = $this->model_extension_module_warehouse->getProductEditView($filter_data);
		$total =  $this->model_extension_module_warehouse->getProductEditViewTotal($filter_data);

		$this->load->model('csa/harvests');
		$data['harvests'] = $this->model_csa_harvests->getHarvestList(array('sort' => 'start_date', 'order' => 'DESC'));
            
		$product_option_value_data = array();
		foreach ($data['results'] as $key => $value) {
			
			if (is_file(DIR_IMAGE . $value['image'])) {
				$data['results'][$key]['image'] = $this->model_tool_image->resize($value['image'], 40, 40);
			} else {
				$data['results'][$key]['image'] = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$data['results'][$key]['href'] =  $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $value['product_id'], TRUE);

			$data['results'][$key]['product_warehouse'] = $this->model_extension_module_warehouse->getGroupsById($value['id']);

			if (isset($value['id']) && $filter_options) {
				$product_options = $this->model_extension_module_warehouse->getProductOptions($value['id'],$filter_data);
				
				foreach ($product_options as $product_option) {
					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$product_option_warehouse = $this->model_extension_module_warehouse->getOptionsGroupsById($product_option_value['product_option_value_id']);
							$product_option_value_data[$value['id']][] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'warehouse'               => $product_option_warehouse,
								'option_value_id'         => $product_option_value['option_value_id'],
								'quantity'                => $product_option_value['quantity'],
								'price'                   => $product_option_value['price'],
								'product_id'              => $value['id'],
								'name'                    => $product_option['name']." > ".$product_option_value['optionname']
							);
						}
					}
				}
			} 
		}

		$data['product_option_value_data'] = $product_option_value_data;
	
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();	
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$data['filter_name'] = $filter_name;

		$data['filter_harvest_id'] = $filter_harvest_id;
                $data['filter_product_type'] = $filter_product_type;
            
		$data['filter_model'] = $filter_model;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_status'] = $filter_status;
		$data['filter_options'] = $filter_options;
		$data['filter_sku'] = $filter_sku;
		$data['filter_subtract'] = $filter_subtract;
		$data['filter_category_id'] = $filter_category_id;
		$data['filter_manufacturer_id'] = $filter_manufacturer_id;
		$data['total'] = $total;
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['filter_harvest_id'])) {
			$url .= '&filter_harvest_id=' . urlencode(html_entity_decode($this->request->get['filter_harvest_id'], ENT_QUOTES, 'UTF-8'));
		}
                if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . urlencode(html_entity_decode($this->request->get['filter_product_type'], ENT_QUOTES, 'UTF-8'));
		}
            
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_subtract'])) {
			$url .= '&filter_subtract=' . $this->request->get['filter_subtract'];
		}

		if (isset($this->request->get['filter_options'])) {
			$url .= '&filter_options=' . $this->request->get['filter_options'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}


		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('extension/module/warehouse/producteditview', 'user_token=' . $this->session->data['user_token'] . '&sort=p.name' . $url, TRUE);
		$data['sort_quantity'] = $this->url->link('extension/module/warehouse/producteditview', 'user_token=' . $this->session->data['user_token'] . '&sort=pt.quantity' . $url, TRUE);
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(0);

		$data['manufacturers'] = $this->model_extension_module_warehouse->getManufacturers();

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['filter_harvest_id'])) {
			$url .= '&filter_harvest_id=' . urlencode(html_entity_decode($this->request->get['filter_harvest_id'], ENT_QUOTES, 'UTF-8'));
		}
                if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . urlencode(html_entity_decode($this->request->get['filter_product_type'], ENT_QUOTES, 'UTF-8'));
		}
            
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_options'])) {
			$url .= '&filter_options=' . $this->request->get['filter_options'];
		}

		if (isset($this->request->get['filter_subtract'])) {
			$url .= '&filter_subtract=' . $this->request->get['filter_subtract'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/warehouse/producteditview', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', TRUE);

		$data['pagination'] = $pagination->render();

		$data['results1'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));


		$this->response->setOutput($this->load->view('extension/module/warehouse_producteditview', $data));	

	}

	public function savequantity() {

		$this->load->language("extension/module/warehouse");

		$warehouses = $json = array();
		if(isset($this->request->post['warehouse'])) {
			$warehouses = $this->request->post['warehouse'];
		} else {
			$url = $this->url->link("extension/module/warehouse",'user_token='.$this->session->data['warehouse'],TRUE);
			$json['error'] = sprintf($this->language->get("error_no_warehouse"),$url);
		}

		$product_id = $product_option_value_id = 0;

		if(isset($this->request->get['trclass'])) {
			$trclass = $this->request->get['trclass'];
			$trclassexploded = explode("_", $trclass);
			if(isset($trclassexploded[0])) {
				$product_id = $trclassexploded[0];
			}
			if(isset($trclassexploded[1])) {
				$product_option_value_id = $trclassexploded[1];
			}
		}

		if(!$json) {
			
			$this->load->model("extension/module/warehouse");
			if($product_option_value_id) {
	    		$this->model_extension_module_warehouse->saveProductOptionWarehouse($warehouses,$product_id,$product_option_value_id);
			} else {
				$this->model_extension_module_warehouse->saveProductWarehouse($warehouses,$product_id);
			}
			if(isset($this->request->get['sumvalue']) && $this->request->get['sumvalue']) {
				$qty = 0;
				foreach ($warehouses as $key => $value) {
					$qty += $value['qty'];
				}
				if($product_option_value_id) {
					$this->model_extension_module_warehouse->updateOptionQty($product_id,$product_option_value_id,$qty);
				} else {
					$this->model_extension_module_warehouse->updateQty($product_id,$qty);
				}
			}
			
		}
	    $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function settings() {
		
		$this->load->language('extension/module/warehouse');

		$this->document->setTitle($this->language->get('heading_title_settings'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSettings()) {
			$this->model_setting_setting->editSetting('module_warehouse', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/warehouse/settings', 'user_token=' . $this->session->data['user_token'], true));
		}
		$data['links'] = $this->links();

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title'),
			'href' =>  $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'], TRUE)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_settings'),
			'href' => $this->url->link('extension/module/warehouse/settings', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/warehouse/settings', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		$data['text_automaticreduce'] = sprintf($this->language->get('text_automaticreduce'),HTTP_CATALOG);

		if (isset($this->request->post['module_warehouse_automaticstockreduce'])) {
			$data['module_warehouse_automaticstockreduce'] = $this->request->post['module_warehouse_automaticstockreduce'];
		} else {
			$data['module_warehouse_automaticstockreduce'] = $this->config->get('module_warehouse_automaticstockreduce');
		}

		if (isset($this->request->post['module_warehouse_negativestock'])) {
			$data['module_warehouse_negativestock'] = $this->request->post['module_warehouse_negativestock'];
		} else {
			$data['module_warehouse_negativestock'] = $this->config->get('module_warehouse_negativestock');
		}

		if (isset($this->request->post['module_warehouse_showininvoice'])) {
			$data['module_warehouse_showininvoice'] = $this->request->post['module_warehouse_showininvoice'];
		} else {
			$data['module_warehouse_showininvoice'] = $this->config->get('module_warehouse_showininvoice');
		}

		if (isset($this->request->post['module_warehouse_stopcheckout'])) {
			$data['module_warehouse_stopcheckout'] = $this->request->post['module_warehouse_stopcheckout'];
		} else {
			$data['module_warehouse_stopcheckout'] = $this->config->get('module_warehouse_stopcheckout');
		}

		if (isset($this->request->post['module_warehouse_reduceafterorderplaced'])) {
			$data['module_warehouse_reduceafterorderplaced'] = $this->request->post['module_warehouse_reduceafterorderplaced'];
		} else {
			$data['module_warehouse_reduceafterorderplaced'] = $this->config->get('module_warehouse_reduceafterorderplaced');
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['module_warehouse_order_status'])) {
			$data['module_warehouse_order_status'] = $this->request->post['module_warehouse_order_status'];
		} else {
			$data['module_warehouse_order_status'] = $this->config->get('module_warehouse_order_status');
		}
		// echo "<pre>";
		// print_r($data);
		// exit;
		$data['sort_order']	 = array('sort_order'=>$this->language->get('text_sortorder'),'state'=>$this->language->get('text_state'));

		if (isset($this->request->post['module_warehouse_sortorder'])) {
			$data['module_warehouse_sortorder'] = $this->request->post['module_warehouse_sortorder'];
		} else {
			$data['module_warehouse_sortorder'] = $this->config->get('module_warehouse_sortorder');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/warehouse_setting', $data));
	}

	private function validateSettings() {

		if (!$this->user->hasPermission('modify', 'extension/module/warehouse')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function getWarehouseImportForm() {

      	$this->load->language('extension/module/warehouse');

        $this->document->setTitle($this->language->get('heading_title_import')); 
        
        $this->load->model('extension/module/warehouse');

        $data['warehouses'] = $this->model_extension_module_warehouse->getwarehouses();

        $data['links'] = $this->links();
      
        $data['exportct'] = $this->url->link('extension/module/warehouse/exportwarehouses','user_token=' . $this->session->data['user_token']);


			// warehouse_export_customization
			// get All harvests
			$this->load->model('csa/harvests');
			$data['harvests'] = $this->model_csa_harvests->getHarvestList(array('filter_status' => 1));
			//-- warehouse_export_customization
			
        $data['exportreference'] = $this->url->link('extension/module/warehouse/exportreference','user_token=' . $this->session->data['user_token']);

        $data['importct'] = $this->url->link('extension/module/warehouse/importwarehouses','user_token=' . $this->session->data['user_token']);

        if (isset($this->error['warning'])) {
          $data['error_warning'] = $this->error['warning'];
        } else {
          $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
        'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], TRUE),
        'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title_import'),
        'href'      => $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'], TRUE),
            'separator' => ' :: '
        );

        if (isset($this->session->data['success'])) {
          $data['success'] = $this->session->data['success']; 
          unset($this->session->data['success']);
        } else {
          $data['success'] = '';
        }

        if (isset($this->session->data['error'])) {
          $data['error'] = $this->session->data['error']; 
          unset($this->session->data['error']);
        } else {
          $data['error'] = '';
        }

        $data['cancel'] = $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] , TRUE);

        $data['user_token'] = $this->session->data['user_token'];
                        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/warehouse_import', $data));
    }

    public function exportreference() {
       $fields = array();
       $sample_data = array();
       $this->load->model("extension/module/warehouse");
       array_push($fields,'product_id','names','warehouse_id','qty');
       $i = 0;
       $sample_data[$i]['product_id'] = "12";
       $sample_data[$i]['names'] = "Apple Cinema";
       $sample_data[$i]['optionname'] = "";
       $sample_data[$i]['optionid'] = "";
       $sample_data[$i]['warehouse_id'] = "1";
       $sample_data[$i]['qty'] = "13";
       $i = 1;
       $sample_data[$i]['product_id'] = "12";
       $sample_data[$i]['names'] = "Apple Cinema";
       $sample_data[$i]['optionname'] = "Select - Blue";
       $sample_data[$i]['optionid'] = "23";
       $sample_data[$i]['warehouse_id'] = "1";
       $sample_data[$i]['qty'] = "3";
       $this->load->library('exportcsv');
       $csv = new ExportCSV();
       $csv->fields = $fields;
       $csv->result = $sample_data;
       $csv->process();
       $csv->download('warehouse.csv');
    }

    public function exportwarehouses() {

    	if ($this->validateSettings()) {
	    	if(isset($this->request->post['warehouse_id'])) {
	          $warehouse_id = $this->request->post['warehouse_id'];
	       	} else {
	       		$this->response->redirect($this->url->link('extension/module/warehouse','user_token=' . $this->session->data['user_token']));
	       	}
	    } else {
	    	$this->response->redirect($this->url->link('extension/module/warehouse','user_token=' . $this->session->data['user_token']));
	    }   	

       $fields = array();
       $results = array();
       $this->load->model("extension/module/warehouse");
       
			// warehouse_export_customization
			$harvest_id = $this->request->post['harvest_id'];
			$product_type = $this->request->post['product_type'];
			$this->load->model('catalog/product');
			if ($warehouse_id == 'all') {
				$filters = array(
					'filter_product_type' => $product_type,
					'filter_harvest_id' => $harvest_id,
					'sort' => 'pt.product_id',
					'order' => 'ASC'
				);

				$warehouses = $this->model_extension_module_warehouse->getwarehouses();
				array_push($fields, 'product_id', 'productname', 'optionid', 'optionname', 'sku', 'price', 'customer_group_warehouse', 'customer_group_csa', 'customer_group_price', 'warehouse_id', 'warehousename', 'mandatory', 'qty');
				$results = $this->model_extension_module_warehouse->getProductsForCsv($filters);
				$i = 0;
				$finalresult = array();
				foreach($results as $key => $value) {
					foreach ($warehouses as $warehouse) {
						$warehouse_id = $warehouse['warehouse_id'];
						$warehouse = $this->model_extension_module_warehouse->getwarehouse($warehouse_id);
						$customer_group = $this->model_extension_module_warehouse->getCustomerGroupWarehouse($warehouse_id);
						$customer_group_id = $customer_group[0];
						$csa = $this->model_extension_module_warehouse->getCustomerGroupCsa($warehouse_id);
						$customer_group_price = $this->model_catalog_product->getCustomerGroupPrice($customer_group_id, $value['id']);
						
						$mandatory = $value['product_type'] == 3 ? 1 : 0;
						$finalresult[$i]['product_id'] = $value['id'];
						$finalresult[$i]['productname'] = str_replace(",", "", $value['productname']);
						$finalresult[$i]['sku'] = $value['sku'];
						$finalresult[$i]['optionname'] = "";
						$finalresult[$i]['optionid'] = "";
						$finalresult[$i]['warehouse_id'] = $warehouse_id;
						$finalresult[$i]['warehousename'] = $warehouse['name'];
						$finalresult[$i]['price'] = $value['price'];
						$finalresult[$i]['mandatory'] = $mandatory;
						$finalresult[$i]['customer_group_warehouse'] = $customer_group_id;
						$finalresult[$i]['customer_group_csa'] = !empty($csa) ? $csa['customer_group_id'] : 0;
						$finalresult[$i]['customer_group_price'] = !empty($customer_group_price) ? $customer_group_price['price'] : 0;
						$finalresult[$i]['qty'] = $this->model_extension_module_warehouse->getQtyByWarehouseProducrId($value['id'],$warehouse_id);
						$product_options = $this->model_extension_module_warehouse->getProductOptions($value['id']);
					
						foreach ($product_options as $product_option) {
			
							if (isset($product_option['product_option_value'])) {
								foreach ($product_option['product_option_value'] as $product_option_value) {
									++$i;
									$customer_group_price = $this->model_catalog_product->getCustomerGroupOptions($value['id'], $customer_group_id, $product_option_value['product_option_value_id']);
									$finalresult[$i]['product_id'] = $value['id'];
									$finalresult[$i]['productname'] = str_replace(",", "", $value['productname']);
									$finalresult[$i]['sku'] = $value['sku'];
									$finalresult[$i]['optionname'] = $product_option['name']." > ".$product_option_value['optionname'];
									$finalresult[$i]['optionid'] = $product_option_value['product_option_value_id'];
									$finalresult[$i]['warehouse_id'] = $warehouse_id;
									$finalresult[$i]['warehousename'] = $warehouse['name'];
									$finalresult[$i]['price'] = $value['price'];
									$finalresult[$i]['mandatory'] = $mandatory;
									$finalresult[$i]['customer_group_warehouse'] = $customer_group_id;
									$finalresult[$i]['customer_group_csa'] = !empty($csa) ? $csa['customer_group_id'] : 0;
									$finalresult[$i]['customer_group_price'] = $customer_group_price;
									$finalresult[$i]['qty'] = $this->model_extension_module_warehouse->getQtyByWarehouseProducrIdOptionID($value['id'],$product_option_value['product_option_value_id'],$warehouse_id);
								}
							}
						}
						++$i;
					}
				}
			} else {
				array_push($fields, 'product_id', 'productname', 'optionid', 'optionname', 'sku', 'price', 'customer_group_warehouse', 'customer_group_csa', 'customer_group_price', 'warehouse_id', 'warehousename', 'mandatory', 'qty');
				$warehouse = $this->model_extension_module_warehouse->getwarehouse($warehouse_id);
				$customer_group = $this->model_extension_module_warehouse->getCustomerGroupWarehouse($warehouse_id);
				$customer_group_id = $customer_group[0];
				$csa = $this->model_extension_module_warehouse->getCustomerGroupCsa($warehouse_id);
			// -- warehouse_export_customization
			
       $results = $this->model_extension_module_warehouse->getProductsForCsv(array());
       $i = 0;
       $finalresult = array();
       foreach ($results as $key => $value) {
       	    $finalresult[$i]['product_id'] = $value['id'];
       		$finalresult[$i]['productname'] = str_replace(",", "", $value['productname']);
       		$finalresult[$i]['optionname'] = "";

			// warehouse_export_customization
			$customer_group_price = $this->model_catalog_product->getCustomerGroupPrice($customer_group_id, $value['id']);
			// -- warehouse_export_customization
			
       		$finalresult[$i]['optionid'] = "";

			// warehouse_export_customization
			$finalresult[$i]['sku'] = $value['sku'];
			$finalresult[$i]['warehousename'] = $warehouse['name'];
			$finalresult[$i]['price'] = $value['price'];
			$mandatory = $value['product_type'] == 3 ? 1 : 0;
			$finalresult[$i]['mandatory'] = $mandatory;
			$finalresult[$i]['customer_group_warehouse'] = $customer_group_id;
			$finalresult[$i]['customer_group_price'] = $customer_group_price;
			$finalresult[$i]['customer_group_csa'] = !empty($csa) ? $csa['customer_group_id'] : 0;
			// -- warehouse_export_customization
			
       		$finalresult[$i]['warehouse_id'] = $warehouse_id;
			$finalresult[$i]['qty'] = $this->model_extension_module_warehouse->getQtyByWarehouseProducrId($value['id'],$warehouse_id);
			$product_options = $this->model_extension_module_warehouse->getProductOptions($value['id']);
			
			foreach ($product_options as $product_option) {

				if (isset($product_option['product_option_value'])) {
					foreach ($product_option['product_option_value'] as $product_option_value) {
						++$i;
						$finalresult[$i]['product_id'] = $value['id'];
						$finalresult[$i]['productname'] = str_replace(",", "", $value['productname']);
			       		$finalresult[$i]['optionname'] = str_replace(',', '', $product_option['name']) ." > ".str_replace(',', '', $product_option_value['optionname']);

			// warehouse_export_customization
			$customer_group_price = $this->model_catalog_product->getCustomerGroupOptions($value['id'], $customer_group_id, $product_option_value['product_option_value_id']);
			// -- warehouse_export_customization
			
			       		$finalresult[$i]['optionid'] = $product_option_value['product_option_value_id'];

			// warehouse_export_customization
			$finalresult[$i]['sku'] = $value['sku'];
			$finalresult[$i]['warehousename'] = $warehouse['name'];
			$finalresult[$i]['price'] = $value['price'];
			$mandatory = $value['product_type'] == 3 ? 1 : 0;
			$finalresult[$i]['mandatory'] = $mandatory;
			$finalresult[$i]['customer_group_warehouse'] = $customer_group_id;
			$finalresult[$i]['customer_group_price'] = $customer_group_price;
			$finalresult[$i]['customer_group_csa'] = !empty($csa) ? $csa['customer_group_id'] : 0;
			// -- warehouse_export_customization
			
			       		$finalresult[$i]['warehouse_id'] = $warehouse_id;
						$finalresult[$i]['qty'] = $this->model_extension_module_warehouse->getQtyByWarehouseProducrIdOptionID($value['id'],$product_option_value['product_option_value_id'],$warehouse_id);
					}
				}
			}
			++$i;
		}
       

			// warehouse_export_customization
			} // else end // -- warehouse_export_customization
       $csv = new ExportCSV();
       $csv->fields = $fields;
       $csv->result = $finalresult;
       $csv->process();
       $csv->download('warehouse.csv');

    }

    public function importwarehouses() {
         ini_set("auto_detect_line_endings", true);   
          ini_set("memory_limit", "512M");
          ini_set("max_execution_time", 180);
          set_time_limit(0);

          if(isset($this->request->post['sum_it'])) {
	          $sum_it = $this->request->post['sum_it'];
	       } else {
	       	  $sum_it = 0;
	       }

	       if(isset($this->request->post['main_qty'])) {
	          $main_qty = $this->request->post['main_qty'];
	       } else {
	       	  $main_qty = 0;
	       }

          if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateSettings()) {
           $data = array();
           if (is_uploaded_file($this->request->files['download']['tmp_name'])) {
                $filename = $this->request->files['download']['name'] . '.' . md5(rand());

                move_uploaded_file($this->request->files['download']['tmp_name'], DIR_DOWNLOAD . $filename);

                if (file_exists(DIR_DOWNLOAD . $filename)) {
                  $this->load->model('extension/module/warehouse');
                  if (($file = file(DIR_DOWNLOAD . $filename)) !== FALSE) {
                   
                    $complete_data = array();
                    $columns = array();
                    $row = 1;
                    foreach($file as $line) {
                       if($row == 1){
                          //$line = str_replace('"', '', $line);
                          $line = str_replace("'", '', $line);
                          $columns = explode(',', $line);
                        //  $response = $this->validatecsv($columns);

                          if(0) {
                            $this->response->redirect($this->url->link('extension/module/warehouse','user_token=' . $this->session->data['user_token']));
                          }

                        } else {
                        
                          $case =  array('TRUE' => 1, 'FALSE' => 0);
                          $line = str_replace('"', '', $line);
                           $line = str_replace("'", '', $line);
                          $datarow = explode(',', $line);
                          
                          foreach($datarow as $key=>$val){
                             $val = trim($val);
                             $datarow[strtolower(trim($columns[$key]))] = isset($case[strtoupper($val)])?$case[strtoupper($val)]:$val;
                             unset($datarow[$key]);
                          }
                          array_push($complete_data,$datarow);
                        }
                        $row++;
                    }  
                     $chunks = array_chunk($complete_data, 1000);
                    
                     foreach($chunks as $chunk){
                       foreach($chunk as $details){
                          $this->model_extension_module_warehouse->bulkAddWarehouse($details,$sum_it,$main_qty);
                        }
                      }
                      $this->session->data['success'] = "Warehouse stock are uploaded successfully";
                  }
                  unlink(DIR_DOWNLOAD . $filename);
                }
          }
        }
        
        $this->response->redirect($this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'] , TRUE));
    }


			// -- warehouse stock deduction rule
			public function stock_deduction_rules() {
				$this->load->language("extension/module/warehouse");

				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'], true)
				);

				$data['breadcrumbs'][] =   array(
					'text' =>  $this->language->get('heading_title_stock'),
					'href' =>  $this->url->link('extension/module/warehouse/stock_deduction_rules', 'user_token=' . $this->session->data['user_token'], TRUE)
				);

				$this->document->setTitle($this->language->get('heading_title_stock'));
				$data['heading_title'] = $this->language->get('heading_title_stock');
				
				$data['back'] = $this->url->link('extension/module/warehouse', 'user_token=' . $this->session->data['user_token'], true);

				$data['user_token'] = $this->session->data['user_token'];

				if (isset($this->error['warning'])) {
					$data['error_warning'] = $this->error['warning'];
				} else {
					$data['error_warning'] = '';
				}

				if (isset($this->session->data['success'])) {
					$data['success'] = $this->session->data['success'];
					unset($this->session->data['success']);
				} else {
					$data['success'] = '';
				}

				$this->load->model('extension/module/warehouse/rules');
				
				if ($this->request->server['REQUEST_METHOD'] == 'POST') {
					$i = 1;
					$this->model_extension_module_warehouse_rules->deleteRules();
					while(true) {
						if ( isset($this->request->post["rule_set_name_${i}"]) ) {
							$rule_name = $this->request->post["rule_set_name_${i}"];
							$product_type = $this->request->post["product_type_${i}"];
							$csas = $this->request->post["csas_${i}"] ?? array();
							$rule_type = $this->request->post["rule_type_${i}"];

							if (empty($csas) && $rule_type == '') {
								$i++;
								continue;
							}

							$warehouse_id = ($rule_type == 'primary') ? 0 : $this->request->post["warehouse_${i}"];
							
							$priority = $this->request->post["priority_${i}"];
							$rule_data = array(
								'name' => $rule_name,
								'product_type' => $product_type,
								'csas' => $csas,
								'warehouse_id' => $warehouse_id,
								'rule_type' => $rule_type,
								'priority' => $priority,
							);
							// insert rule in table
							$this->model_extension_module_warehouse_rules->addRule($rule_data);
							$data['success'] = 'Stock Deduction Rules saved successfully';
							$i++;
						} else {
							break;
						}
					}
				}

				// fetch csa
				$this->load->model('csa/csa');
				$data['csas'] = $this->model_csa_csa->getCSAList();
				$this->load->model('extension/module/warehouse');
				$data['warehouses'] = $this->model_extension_module_warehouse->getwarehouses();
				$data['rules'] = array();
				$rules = $this->model_extension_module_warehouse_rules->getRules();

				foreach($rules as $rule) {
					$csas = $this->model_extension_module_warehouse_rules->getRuleCsas($rule['rule_id']);
					$data['rules'][] = array(
						'rule_id' => $rule['rule_id'],
						'name' => $rule['name'],
						'product_type' => $rule['product_type'],
						'warehouse_id' => $rule['warehouse_id'],
						'csas' => array_map(function($csa) { return $csa['csa_id']; }, $csas),
						'rule_type' => $rule['rule_type'],
						'priority' => $rule['priority'],
					);
				}
				
				$data['log'] = '';
				if (file_exists(DIR_LOGS . 'warehouse_stock_rules.log')) {
					$data['log'] = file_get_contents(DIR_LOGS . 'warehouse_stock_rules.log', 'r');
				}
				
				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');

				$this->response->setOutput($this->load->view('extension/module/warehouse_stock_deduction_rules', $data));
			}

			public function refreshLog() {
				$log_content = '';
				if (file_exists(DIR_LOGS . 'warehouse_stock_rules.log')) {
					$log_content = file_get_contents(DIR_LOGS . 'warehouse_stock_rules.log', 'r');
				}
				echo $log_content;
			}

			public function clearLog() {
				$logHandle = fopen(DIR_LOGS . 'warehouse_stock_rules.log', 'w');
				fclose($logHandle);
				return true;
			}
			// -- end warehouse stock deduction rule
			
    public function getState() {
         $json = array();

		if (isset($this->request->get['filter_name'])) {

			$this->load->model('extension/module/warehouse');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_country_id'])) {
				$filter_country_id = $this->request->get['filter_country_id'];
			} else {
				$filter_country_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_country_id'  => $filter_country_id,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_extension_module_warehouse->getStates($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'zone_id' => $result['zone_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
}