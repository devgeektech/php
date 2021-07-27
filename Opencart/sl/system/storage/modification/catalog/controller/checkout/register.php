<?php
class ControllerCheckoutRegister extends Controller {
	public function index() {
		$this->load->language('checkout/checkout');
		
		$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));

		$data['customer_groups'] = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups  as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		$data['customer_group_id'] = $this->config->get('config_customer_group_id');

		if (isset($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha'] = '';
		}

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		$data['shipping_required'] = $this->cart->hasShipping();
		

				// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');

					$data['my_custom_input_fields'] = array();
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						$data['my_custom_input_fields'] = $this->model_extension_checkout_manager_checkout->getFields();
					}
				// Extendons - Checkout Manager /- End
		    	
		$this->response->setOutput($this->load->view('checkout/register', $data));
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

		// Validate if customer is already logged out.
		if ($this->customer->isLogged()) {
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

		if (!$json) {
			$this->load->model('account/customer');

	            // Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');

		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						foreach ($this->request->post as $field_name => $f_value)
						{
							// query to get required and not required fields
							$q = $this->db->query("SELECT field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE field_name ='" . $field_name . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");

							if ($field_name == 'firstname' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
									$json['error']['firstname'] = $this->language->get('error_firstname');
								}
							}

							if ($field_name == 'lastname' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
									$json['error']['lastname'] = $this->language->get('error_lastname');
								}
							}

							if ($field_name == 'email' && $q->num_rows == 1) {
								if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
									$json['error']['email'] = $this->language->get('error_email');
								}
							}

							if ($field_name == 'telephone' && $q->num_rows == 1) {
								if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
									$json['error']['telephone'] = $this->language->get('error_telephone');
								}
							}

							if ($field_name == 'address_1' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
									$json['error']['address_1'] = $this->language->get('error_address_1');
								}
							}

							if ($field_name == 'city' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
									$json['error']['city'] = $this->language->get('error_city');
								}
							}

							if ($field_name == 'postcode' && $q->num_rows == 1) {
								if (isset($this->request->post['country_id'])) {
									$this->load->model('localisation/country');
									$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

									if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
										$json['error']['postcode'] = $this->language->get('error_postcode');
									}
								} elseif (!isset($this->request->post['country_id'])) {
									// if country field is dissabled
									if (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10) {
										$json['error']['postcode'] = $this->language->get('error_postcode');
									}
								}
							}

							if ($field_name == 'country_id' && $q->num_rows == 1) {
								if ($this->request->post['country_id'] == '') {
									$json['error']['country'] = $this->language->get('error_country');
								}
							}

							if ($field_name == 'zone_id' && $q->num_rows == 1) {
								if (isset($this->request->post['country_id'])) {
									if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
										$json['error']['zone'] = $this->language->get('error_zone');
									}
								}
							}
						}

						if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
							$json['error']['password'] = $this->language->get('error_password');
						}

						if ($this->request->post['confirm'] != $this->request->post['password']) {
							$json['error']['confirm'] = $this->language->get('error_confirm');
						}

						// if is custom address
						$customPaymentAddress = array();
						if (isset($this->request->post['billing_address'])) {
							$data = $this->request->post['billing_address'];

							foreach ($data as $field_name2 => $f_value2)
							{
								$q2 = $this->db->query("SELECT field_label, field_name, field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND field_name = '" . $field_name2 . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");

								// store custom created fields data in array, so we are putting it into session below for further use
								if ($f_value2 != 'required') {
									$customPaymentAddress[$field_name2] = $f_value2;
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
					} else {
				// Extendons - Checkout Manager /- End
		    	

			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$json['error']['email'] = $this->language->get('error_email');
			}

			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}

			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}

			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}

			if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['confirm'] != $this->request->post['password']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}


	            // Extendons - Checkout Manager
					}
				// Extendons - Checkout Manager /- End
		    	
			if ($this->config->get('config_account_id')) {
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

				if ($information_info && !isset($this->request->post['agree'])) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}

			// Customer Group
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}

			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error']['captcha'] = $captcha;
				}
			}
		}


        if($this->config->get('module_warehouse_stopcheckout')) {
          if (!empty($this->request->post['shipping_address'])) {
            $this->load->model('extension/module/warehouse');
            $this->load->language('extension/module/warehouse');
            $stock = $this->model_extension_module_warehouse->checkStock($this->request->post['zone_id']);
            if($stock) {
              $arraymessage[] = $this->language->get('text_stock_error');
              foreach ($stock as $key => $value) {
                $arraymessage[] = sprintf($this->language->get('text_stock_array'),$value['name'],$value['qty']);
              }
              $arraymessage[] = $this->language->get('text_updateqty');
              $json['error']['warning'] = implode("<br>", $arraymessage);
            }
          }  
        }
        
		if (!$json) {
			$customer_id = $this->model_account_customer->addCustomer($this->request->post);

			// Default Payment Address
			$this->load->model('account/address');
				
			$address_id = $this->model_account_address->addAddress($customer_id, $this->request->post);

	        	// Extendons - Checkout Manager
					$data = $this->request->post;
						
					// get last id and assign to address_id
					$data['address_id'] = $address_id;

					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						$this->model_extension_checkout_manager_checkout->addCustomAddress($data);
					}
				// Extendons - Checkout Manager /- End
		    	
			
			// Set the address as default
			$this->model_account_customer->editAddressId($customer_id, $address_id);
			
			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

			$this->session->data['account'] = 'register';

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group_info && !$customer_group_info['approval']) {
				$this->customer->login($this->request->post['email'], $this->request->post['password']);

				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());

	        	// Extendons - Checkout Manager
	        		// remove our custom address data from session
					unset($this->session->data['payment_address']['update_my_custom_address']);
					
					// store new coming custom fields data in session, to save in custom order table
					$this->session->data['payment_address']['custom_payment_address'] = $customPaymentAddress;
				// Extendons - Checkout Manager /- End
		    	

				if (!empty($this->request->post['shipping_address'])) {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());

	        	// Extendons - Checkout Manager
	        		// remove our custom address data from session
					unset($this->session->data['shipping_address']['update_my_custom_address']);
					
					$this->session->data['shipping_address']['custom_shipping_address'] = $customPaymentAddress;
				// Extendons - Checkout Manager /- End
		    	
				}
			} else {
				$json['redirect'] = $this->url->link('account/success');
			}

			unset($this->session->data['guest']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
