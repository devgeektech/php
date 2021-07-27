<?php
class ControllerCheckoutPaymentMethod extends Controller {
	public function index() {
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['payment_address'])) {
			// Totals
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
					
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			// Payment Methods
			$method_data = array();

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('payment');

			$recurring = $this->cart->hasRecurringProducts();

	        	// Extendons - Checkout Manager
		        	if (!isset($this->session->data['payment_address']['country_id']) || (empty($this->session->data['payment_address']['country_id']) || $this->session->data['payment_address']['country_id'] == 0) ) {
						$this->session->data['payment_address']['country_id'] = 0;
						$this->session->data['payment_address']['zone_id'] = 0;
					}
				// Extendons - Checkout Manager /- End
		    	

			foreach ($results as $result) {
				if ($this->config->get('payment_' . $result['code'] . '_status')) {
					$this->load->model('extension/payment/' . $result['code']);

					$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method) {
						if ($recurring) {
							if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['payment_methods'] = $method_data;
		}


                            $this->load->model('catalog/product');
                            $csa_detail = $this->model_catalog_product->getCustomerCSAVolunteer();
                            $data['checkout_volunteer_messages'] = '';
                            if(!empty($csa_detail)){
                                $data['checkout_volunteer_messages'] = html_entity_decode($csa_detail['checkout_volunteer_messages'], ENT_QUOTES, 'UTF-8');
                            }
		    
		if (empty($this->session->data['payment_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$data['payment_methods'] = array();
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}

		$data['scripts'] = $this->document->getScripts();

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->session->data['agree'])) {
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = '';
		}


            	// Extendons - Checkout Manager
					$data['text_select'] = $this->language->get('text_select');
					$data['text_none'] = $this->language->get('text_none');
					
					$this->load->model('extension/checkout_manager/checkout');

					$inputFields = '';
					$data['my_custom_input_fields'] = array();
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						$inputFields = $this->model_extension_checkout_manager_checkout->getFields();
					}

					if (!empty($inputFields)) {
						foreach ($inputFields as $key => $value) {
							if ($value['field_id']) 
							{
								$f_id = explode('input-payment', $value['field_id']);
								// we are changing field id, for the purpose of validation in view, because error text shown on the basis of field id
								$f_id = 'input-payment-method'.$f_id[1];

								$inputFields[$key]['field_id'] = $f_id;
							}
						}
						$data['my_custom_input_fields'] = $inputFields;
					}
				// Extendons - Checkout Manager /- End
		    	
		$this->response->setOutput($this->load->view('checkout/payment_method', $data));
	}

	public function save() {
// Clear Thinking: MailChimp Integration Pro
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'module_';
				if ($this->config->get($prefix . 'mailchimp_integration_sendcarts') && ($this->customer->isLogged() || isset($this->request->post['email']))) {
					$customer_id = ($this->customer->isLogged()) ? (int)$this->customer->getId() : $this->request->post['email'];
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->sendCart($this->cart, $customer_id);
				}
				// end
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		}


            	// Extendons - Checkout Manager
	            	else {
						$customPaymentMehodFieldsData = array();
						if (isset($this->request->post['billing_address']))
						{
							$data = $this->request->post['billing_address'];

							foreach ($data as $field_name2 => $f_value2)
							{
								$q2 = $this->db->query("SELECT field_label, field_name, field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND field_name ='" . $field_name2 . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");

								// store custom created fields data in array, so we are putting it into session below for further use
								if ($f_value2 != 'required') {
									$customPaymentMehodFieldsData[$field_name2] = $f_value2;
								}

								if ($q2->num_rows == 1)
								{
									$label = $q2->row['field_label'];
									if ($f_value2 == '' || $f_value2 == 'required') {
										$field_name2 = explode('_custom', $field_name2);
										$json['error'][$field_name2[0]] = $label." field is required!";
									}
								}
							}
						}
					}
				// Extendons - Checkout Manager /- End
		    	
		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];


            	// Extendons - Checkout Manager
					$this->session->data['custom_payment_method'] = $customPaymentMehodFieldsData;
				// Extendons - Checkout Manager /- End
		    	
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
