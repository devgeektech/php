<?php
class ControllerCheckoutPaymentAddress extends Controller {
	public function index() {
// Clear Thinking: Ultimate Coupons
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'total_';
				if ($this->config->get($prefix . 'ultimate_coupons_status')) {
					if (version_compare(VERSION, '2.1', '<')) {
						$this->load->language('checkout/coupon');
						$coupon_success = $this->language->get('text_success');
					} elseif (version_compare(VERSION, '2.3', '<')) {
						$this->load->language('total/coupon');
						$coupon_success = $this->language->get('text_success');
					} else {
						$this->load->language('extension/total/coupon');
						$coupon_success = $this->language->get('text_success');
					}
					
					$current_coupon = (isset($this->session->data['coupon'])) ? $this->session->data['coupon'] : '';
					
					if ($this->config->get($prefix . 'ultimate_coupons_coupon_box')) {
						$data['ultimate_coupons_box'] = '
							<div class="coupon-code-box">' . $this->language->get('entry_coupon') . ' &nbsp;
								<input type="text" class="form-control" style="width: 25%; display: inline-block" value="' . $current_coupon . '" />
								<a class="button btn btn-primary" onclick="checkUltimateCoupon($(this))">' . $this->language->get('button_coupon') . '</a>
							</div>
							<br />
						' . "
							<script>
								function checkUltimateCoupon(element) {
									$.ajax({
										url: 'index.php?route=checkout/checkout/checkUltimateCoupon&code=' + $(element).prev().val(),
										beforeSend: function() {
											$('#button-coupon').attr('disabled', 'disabled');
										},
										complete: function() {
											$('#button-coupon').removeAttr('disabled');
										},
										success: function(error) {
											$('.alert').remove();
											if (error) {
												$(element).parent().before('<div class=\"warning alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> " . str_replace("'", "\'", $this->language->get('error_coupon')) . "</div>');
											} else {
												$(element).parent().before('<div class=\"success alert alert-success\"><i class=\"fa fa-check-circle\"></i> " . str_replace("'", "\'", $coupon_success) . "</div>');
											}
										}
									});
								}
							</script>
						";
					}
				}
				// end
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['payment_address']['address_id'])) {
			$data['address_id'] = $this->session->data['payment_address']['address_id'];
		} else {
			$data['address_id'] = $this->customer->getAddressId();
		}

		$this->load->model('account/address');

		$data['addresses'] = $this->model_account_address->getAddresses();

		if (isset($this->session->data['payment_address']['country_id'])) {
			$data['country_id'] = $this->session->data['payment_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['payment_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['payment_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$data['custom_fields'] = array();
		
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') {
				$data['custom_fields'][] = $custom_field;
			}
		}

		if (isset($this->session->data['payment_address']['custom_field'])) {
			$data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];
		} else {
			$data['payment_address_custom_field'] = array();
		}


	            	// Extendons - Checkout Manager
						$this->load->model('extension/checkout_manager/checkout');

						$data['my_custom_input_fields'] = array();
			        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
							$data['my_custom_input_fields'] = $this->model_extension_checkout_manager_checkout->getFields();
						}
					// Extendons - Checkout Manager /- End
		    	
		$this->response->setOutput($this->load->view('checkout/payment_address', $data));
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

		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
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
			$this->load->model('account/address');
							
			if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
				if (empty($this->request->post['address_id'])) {
					$json['error']['warning'] = $this->language->get('error_address');
				} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
				}

				if (!$json) {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['address_id']);

					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
			} else {

	            	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						foreach ($this->request->post as $field_name => $f_value)
						{
							// query to get required and not required fields
							$q = $this->db->query("SELECT field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE field_name ='" . $field_name . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");
							
							// echo $q->num_rows;
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

							if ($field_name == 'address_1' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
									$json['error']['address_1'] = $this->language->get('error_address_1');
								}
							}

							if ($field_name == 'address_2' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['address_2'])) < 3) || (utf8_strlen(trim($this->request->post['address_2'])) > 128)) {
									$json['error']['address_2'] = $this->language->get('error_address_2');
								}
							}

							if ($field_name == 'city' && $q->num_rows == 1) {
								if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
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

						$customPaymentAddress = array();
						if (isset($this->request->post['billing_address']))
						{
							$data = $this->request->post['billing_address'];

							foreach ($data as $field_name2 => $f_value2)
							{
								$q2 = $this->db->query("SELECT field_label, field_name, field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND field_name ='" . $field_name2 . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");

								// store custom created fields data in array, so we are putting it into session below for further use
								if ($f_value2 != 'required') {
									$customPaymentAddress[$field_name2] = $f_value2;
								}

								if ($q2->num_rows == 1) {

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

				if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
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


	            	// Extendons - Checkout Manager
						}
					// Extendons - Checkout Manager /- End
		    	
				// Custom field validation
				$this->load->model('account/custom_field');

				$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
							$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						}
					}
				}

				if (!$json) {
					$address_id = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);

					$this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);

	            	// Extendons - Checkout Manager
						// fields data
						$data = $this->request->post;
						
						// get last id and assign to address_id
						$data['address_id'] = $address_id;

						$this->load->model('extension/checkout_manager/checkout');
			        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
							$this->model_extension_checkout_manager_checkout->addCustomAddress($data);
						}

						// remove our custom address data from session
						unset($this->session->data['payment_address']['update_my_custom_address']);
						
						// store new coming custom fields data in session, to save in custom order table
						$this->session->data['payment_address']['custom_payment_address'] = $customPaymentAddress;
					// Extendons - Checkout Manager /- End
		    	

					// If no default address ID set we use the last address
					if (!$this->customer->getAddressId()) {
						$this->load->model('account/customer');
						
						$this->model_account_customer->editAddressId($this->customer->getId(), $address_id);
					}

					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}