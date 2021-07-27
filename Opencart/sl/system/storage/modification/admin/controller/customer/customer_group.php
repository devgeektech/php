<?php
class ControllerCustomerCustomerGroup extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		$this->getList();
	}

	public function add() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_customer_group->addCustomerGroup($this->request->post);

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

			$this->response->redirect($this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_customer_group->editCustomerGroup($this->request->get['customer_group_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_group_id) {
				$this->model_customer_customer_group->deleteCustomerGroup($customer_group_id);
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

			$this->response->redirect($this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cgd.name';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('customer/customer_group/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('customer/customer_group/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['customer_groups'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$customer_group_total = $this->model_customer_customer_group->getTotalCustomerGroups();

		$results = $this->model_customer_customer_group->getCustomerGroups($filter_data);

		foreach ($results as $result) {
			$data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : null),
				'sort_order'        => $result['sort_order'],
				'edit'              => $this->url->link('customer/customer_group/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_group_id=' . $result['customer_group_id'] . $url, true)
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

		$data['sort_name'] = $this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . '&sort=cgd.name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . '&sort=cg.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_group_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_group_total - $this->config->get('config_limit_admin'))) ? $customer_group_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_group_total, ceil($customer_group_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_group_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['customer_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['customer_group_id'])) {
			$data['action'] = $this->url->link('customer/customer_group/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('customer/customer_group/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_group_id=' . $this->request->get['customer_group_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('customer/customer_group', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['customer_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($this->request->get['customer_group_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['customer_group_description'])) {
			$data['customer_group_description'] = $this->request->post['customer_group_description'];
		} elseif (isset($this->request->get['customer_group_id'])) {
			$data['customer_group_description'] = $this->model_customer_customer_group->getCustomerGroupDescriptions($this->request->get['customer_group_id']);
		} else {
			$data['customer_group_description'] = array();
		}

		if (isset($this->request->post['approval'])) {
			$data['approval'] = $this->request->post['approval'];
		} elseif (!empty($customer_group_info)) {
			$data['approval'] = $customer_group_info['approval'];
		} else {
			$data['approval'] = '';
		}


                if (isset($this->request->post['checkout_fixed_fee'])) {
			$data['checkout_fixed_fee'] = $this->request->post['checkout_fixed_fee'];
		} elseif (!empty($customer_group_info)) {
			$data['checkout_fixed_fee'] = $customer_group_info['checkout_fixed_fee'];
		} else {
			$data['checkout_fixed_fee'] = 0;
		}
                
                if (isset($this->request->post['checkout_fee_message'])) {
			$data['checkout_fee_message'] = $this->request->post['checkout_fee_message'];
		} elseif (!empty($customer_group_info)) {
			$data['checkout_fee_message'] = $customer_group_info['checkout_fee_message'];
		} else {
			$data['checkout_fee_message'] = '';
		}
            

		/* Jade Payment Method Customer Group Starts */
		$this->load->language('customer/jade_customer_group_payment');

		$data['entry_payment_method'] = $this->language->get('entry_payment_method');

		$this->load->model('customer/jade_customer_group_payment');
		$this->model_customer_jade_customer_group_payment->CreateTableCustomerGroupPayment();

		$this->load->model('setting/extension');
		$payments = $this->model_setting_extension->getInstalled('payment');

		$data['installed_payments'] = array();
		foreach($payments as $payment_key => $payment) {
			$this->load->language('extension/payment/' . $payment);

			$data['installed_payments'][] = array(
				'payment_name'       => $this->language->get('heading_title'),
				'payment_code'       => $payment,
			);
		}

		if (isset($this->request->post['customer_group_payment_method'])) {
			$data['customer_group_payment_method'] = $this->request->post['customer_group_payment_method'];
		} elseif (!empty($customer_group_info)) {
			$data['customer_group_payment_method'] = $this->model_customer_jade_customer_group_payment->getCustomerGroupPaymentMethod($customer_group_info['customer_group_id']);
		} else {
			$data['customer_group_payment_method'] = array();
		}
		/* Jade Payment Method Customer Group Ends */
				

    $this->load->model("extension/module/warehouse");
    $this->load->language("extension/module/warehouse_links");
    $data['warehouses'] = $this->model_extension_module_warehouse->getwarehouses();

    if (isset($this->request->post['cg_warehouse'])) {
      $data['cg_warehouse'] = $this->request->post['cg_warehouse'];
    } elseif (!empty($customer_group_info)) {
      $data['cg_warehouse'] = $this->model_extension_module_warehouse->getWarehouseCustomerGroup($customer_group_info['customer_group_id']);
    } else {
      $data['cg_warehouse'] = array();
    }
    
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($customer_group_info)) {
			$data['sort_order'] = $customer_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

$this->load->language('customer/customer_group');
		$this->response->setOutput($this->load->view('customer/customer_group_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customer/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['customer_group_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 50)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'customer/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('customer/customer');

		foreach ($this->request->post['selected'] as $customer_group_id) {
			if ($this->config->get('config_customer_group_id') == $customer_group_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByCustomerGroupId($customer_group_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$customer_total = $this->model_customer_customer->getTotalCustomersByCustomerGroupId($customer_group_id);

			if ($customer_total) {
				$this->error['warning'] = sprintf($this->language->get('error_customer'), $customer_total);
			}
		}

		return !$this->error;
	}
}