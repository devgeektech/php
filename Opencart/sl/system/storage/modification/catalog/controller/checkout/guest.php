<?php
class ControllerCheckoutGuest extends Controller {
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

		$data['customer_groups'] = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		if (isset($this->session->data['guest']['customer_group_id'])) {
			$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (isset($this->session->data['guest']['firstname'])) {
			$data['firstname'] = $this->session->data['guest']['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->session->data['guest']['lastname'])) {
			$data['lastname'] = $this->session->data['guest']['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->session->data['guest']['email'])) {
			$data['email'] = $this->session->data['guest']['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->session->data['guest']['telephone'])) {
			$data['telephone'] = $this->session->data['guest']['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->session->data['payment_address']['company'])) {
			$data['company'] = $this->session->data['payment_address']['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->session->data['payment_address']['address_1'])) {
			$data['address_1'] = $this->session->data['payment_address']['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->session->data['payment_address']['address_2'])) {
			$data['address_2'] = $this->session->data['payment_address']['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->session->data['payment_address']['postcode'])) {
			$data['postcode'] = $this->session->data['payment_address']['postcode'];
		} elseif (isset($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->session->data['payment_address']['city'])) {
			$data['city'] = $this->session->data['payment_address']['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->session->data['payment_address']['country_id'])) {
			$data['country_id'] = $this->session->data['payment_address']['country_id'];
		} elseif (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['payment_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['payment_address']['zone_id'];
		} elseif (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		if (isset($this->session->data['guest']['custom_field'])) {
			if (isset($this->session->data['guest']['custom_field'])) {
				$guest_custom_field = $this->session->data['guest']['custom_field'];
			} else {
				$guest_custom_field = array();
			}

			if (isset($this->session->data['payment_address']['custom_field'])) {
				$address_custom_field = $this->session->data['payment_address']['custom_field'];
			} else {
				$address_custom_field = array();
			}

			$data['guest_custom_field'] = $guest_custom_field + $address_custom_field;
		} else {
			$data['guest_custom_field'] = array();
		}

		$data['shipping_required'] = $this->cart->hasShipping();

		if (isset($this->session->data['guest']['shipping_address'])) {
			$data['shipping_address'] = $this->session->data['guest']['shipping_address'];
		} else {
			$data['shipping_address'] = true;
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('guest', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha'] = '';
		}
		

	        	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');

					$data['my_custom_input_fields'] = array();
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						$data['my_custom_input_fields'] = $this->model_extension_checkout_manager_checkout->getFields();
					}
				// Extendons - Checkout Manager /- End
		    	
		$this->response->setOutput($this->load->view('checkout/guest', $data));
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
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Check if guest checkout is available.
		if (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		if (!$json) {
			
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

						// if is custom address
						if (isset($this->request->post['billing_address']))
						{
							$data = $this->request->post['billing_address'];

							foreach ($data as $field_name2 => $f_value2)
							{
								$q2 = $this->db->query("SELECT field_label, field_name, field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND field_name = '" . $field_name2 . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");

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
						if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
							$json['error']['firstname'] = $this->language->get('error_firstname');
						}

						if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
							$json['error']['lastname'] = $this->language->get('error_lastname');
						}

						if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
							$json['error']['email'] = $this->language->get('error_email');
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
					}
				// Extendons - Checkout Manager /- End
		    	

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
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('guest', (array)$this->config->get('config_captcha_page'))) {
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
			$this->session->data['account'] = 'guest';

			$this->session->data['guest']['customer_group_id'] = $customer_group_id;
			
	        	// Extendons - Checkout Manager
					if (isset($this->request->post['firstname'])) {
						$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
					}
					if (isset($this->request->post['lastname'])) {
						$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
					}
				// Extendons - Checkout Manager /- End
		    	
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];

			if (isset($this->request->post['custom_field']['account'])) {
				$this->session->data['guest']['custom_field'] = $this->request->post['custom_field']['account'];
			} else {
				$this->session->data['guest']['custom_field'] = array();
			}

			
	        	// Extendons - Checkout Manager
	        		// this foreach will work remain the same as original line of code, to store form fields data in session
					foreach ($this->request->post as $key => $value) {
						if ($key != 'email') {
							if ($key != 'telephone') {
								if ($key != 'billing_address') {
									$this->session->data['payment_address'][$key] = $value;
								}
							}
						}
					}
					// and if billing_address
					if (isset($this->request->post['billing_address'])) {
						foreach ($this->request->post['billing_address'] as $key2 => $value2) {
							$this->session->data['payment_address']['custom_payment_address'][$key2] = $value2;
						}
					}
				// Extendons - Checkout Manager /- End
		    	

			$this->load->model('localisation/country');


	        	// Extendons - Checkout Manager
					if (isset($this->request->post['country_id']) && !empty($this->request->post['country_id'])) {
				// Extendons - Checkout Manager /- End
		    	
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info) {
				$this->session->data['payment_address']['country'] = $country_info['name'];
				$this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['payment_address']['country'] = '';
				$this->session->data['payment_address']['iso_code_2'] = '';
				$this->session->data['payment_address']['iso_code_3'] = '';
				$this->session->data['payment_address']['address_format'] = '';
			}

	        	// Extendons - Checkout Manager
					} else {
						$this->session->data['payment_address']['country'] = '';
						$this->session->data['payment_address']['iso_code_2'] = '';
						$this->session->data['payment_address']['iso_code_3'] = '';
						$this->session->data['payment_address']['address_format'] = '';
					}
				// Extendons - Checkout Manager /- End
		    	

			if (isset($this->request->post['custom_field']['address'])) {
				$this->session->data['payment_address']['custom_field'] = $this->request->post['custom_field']['address'];
			} else {
				$this->session->data['payment_address']['custom_field'] = array();
			}

			$this->load->model('localisation/zone');


	        	// Extendons - Checkout Manager
					if (isset($this->request->post['zone_id']) && !empty($this->request->post['zone_id'])) {
				// Extendons - Checkout Manager /- End
		    	
			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

			if ($zone_info) {
				$this->session->data['payment_address']['zone'] = $zone_info['name'];
				$this->session->data['payment_address']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['payment_address']['zone'] = '';
				$this->session->data['payment_address']['zone_code'] = '';
			}

	        	// Extendons - Checkout Manager
					} else {
						$this->session->data['payment_address']['zone'] = '';
						$this->session->data['payment_address']['zone_code'] = '';
					}
				// Extendons - Checkout Manager /- End
		    	

			if (!empty($this->request->post['shipping_address'])) {
				$this->session->data['guest']['shipping_address'] = $this->request->post['shipping_address'];
			} else {
				$this->session->data['guest']['shipping_address'] = false;
			}

			if ($this->session->data['guest']['shipping_address']) {
				
	        	// Extendons - Checkout Manager
	        		// this foreach will work remain the same as original line of code, to store form fields data in session
					foreach ($this->request->post as $key => $value) {
						if ($key != 'email') {
							if ($key != 'telephone') {
								if ($key != 'billing_address') {
									$this->session->data['shipping_address'][$key] = $value;
								}
							}
						}
					}
					// and if billing_address
					if (isset($this->request->post['billing_address'])) {
						foreach ($this->request->post['billing_address'] as $key2 => $value2) {
							$this->session->data['shipping_address']['custom_shipping_address'][$key2] = $value2;
						}
					}
					// for country info
					if (isset($country_info) && !empty($country_info)) {
				// Extendons - Checkout Manager /- End
		    	

				if ($country_info) {
					$this->session->data['shipping_address']['country'] = $country_info['name'];
					$this->session->data['shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
				} else {
					$this->session->data['shipping_address']['country'] = '';
					$this->session->data['shipping_address']['iso_code_2'] = '';
					$this->session->data['shipping_address']['iso_code_3'] = '';
					$this->session->data['shipping_address']['address_format'] = '';
				}

	        	// Extendons - Checkout Manager
					} else {
						$this->session->data['shipping_address']['country'] = '';
						$this->session->data['shipping_address']['iso_code_2'] = '';
						$this->session->data['shipping_address']['iso_code_3'] = '';
						$this->session->data['shipping_address']['address_format'] = '';
					}
					if (isset($zone_info) && !empty($zone_info)) {
				// Extendons - Checkout Manager /- End
		    	

				if ($zone_info) {
					$this->session->data['shipping_address']['zone'] = $zone_info['name'];
					$this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
				} else {
					$this->session->data['shipping_address']['zone'] = '';
					$this->session->data['shipping_address']['zone_code'] = '';
				}

	        	// Extendons - Checkout Manager
					} else {
						$this->session->data['shipping_address']['zone'] = '';
						$this->session->data['shipping_address']['zone_code'] = '';
					}
				// Extendons - Checkout Manager /- End
		    	

				if (isset($this->request->post['custom_field']['address'])) {
					$this->session->data['shipping_address']['custom_field'] = $this->request->post['custom_field']['address'];
				} else {
					$this->session->data['shipping_address']['custom_field'] = array();
				}
			}

			unset($this->session->data['shipping_method']);
// Clear Thinking: MailChimp Integration
				if (!empty($this->request->post['newsletter'])) {
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->send(array_merge($this->request->post, array('customer_id' => 0)));
				}
				// end
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
