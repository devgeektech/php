<?php
class ControllerAccountRegister extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->load->language('account/register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$customer_id = $this->model_account_customer->addCustomer($this->request->post);

            // Registration Fields
            $this->load->model('account/address');
			if (!isset($this->request->post['country_id'])) {
				$this->request->post['country_id'] = 223; // Country - US
			}
			$this->request->post['default'] = 1;
			$this->model_account_address->addAddress($customer_id, $this->request->post);
            // -- Registration Fields
            

			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

			$this->customer->login($this->request->post['email'], $this->request->post['password']);

			unset($this->session->data['guest']);
// Clear Thinking: MailChimp Integration Pro
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'module_';
				if ($this->config->get($prefix . 'mailchimp_integration_sendcarts') && $this->cart->hasProducts()) {
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->sendCart($this->cart, (int)$this->customer->getId());
				}
				// end

			
            $this->response->redirect($this->url->link('account/store', '', TRUE));
            
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('account/register', '', true)
		);
		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', true));

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


	        	// Extendons - Checkout Manager
					// extendons - error
					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						$get_fields = $this->db->query("SELECT field_name FROM " . DB_PREFIX . "extendons_checkout_fields ");

						if ($get_fields->num_rows > 1) {
							foreach ($get_fields->rows as $key => $value) 
							{
								$name = $value['field_name'];

								if (isset($this->error[$name])) {
									$data['error']['error_'.$name] = $this->error[$name];
								} else {
									$data['error']['error_'.$name] = '';
								}
							}
						}
					} else {
				// Extendons - Checkout Manager /- End
		    	
		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}


	        	// Extendons - Checkout Manager
					}
				// Extendons - Checkout Manager /- End
		    	

            // Registration Fields
            $this->load->model('localisation/zone');
		    $data['zones'] = $this->model_localisation_zone->getZonesByCountryId(223);
            // -- Registration Fields
            
		
            // Registration Fields
            if (isset($this->request->get['csa'])) {
                $data['action'] = $this->url->link('account/register&csa=' . $this->request->get['csa'], '', true);
            } else {
                $data['action'] = $this->url->link('account/register', '', true);
            }
            // -- Registration Fields
            


            // Registration Fields
            if (isset($this->error['address_1'])) {
                $data['error_address_1'] = $this->error['address_1'];
            } else {
                $data['error_address_1'] = '';
            }

            if (isset($this->error['city'])) {
                $data['error_city'] = $this->error['city'];
            } else {
                $data['error_city'] = '';
            }

            if (isset($this->error['postcode'])) {
                $data['error_postcode'] = $this->error['postcode'];
            } else {
                $data['error_postcode'] = '';
            }

            if (isset($this->error['zone'])) {
                $data['error_zone'] = $this->error['zone'];
            } else {
                $data['error_zone'] = '';
            }

            if (isset($this->error['telephone'])) {
                $data['error_telephone'] = $this->error['telephone'];
            } else {
                $data['error_telephone'] = '';
            }
            // -- Registration Fields
            
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

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}


                
		if (isset($this->request->post['receive_text'])) {
			$data['receive_text'] = $this->request->post['receive_text'];
		} else {
			$data['receive_text'] = '';
		}
                                
            // Registration Fields
            if (isset($this->request->get['csa'])) {
                $csa_name = ucwords(preg_replace('/[_]+/', ' ', strtolower(trim($this->request->get['csa']))));
                $this->load->model('csa/csa');
                $csa = $this->model_csa_csa->getCSAByName($csa_name);
                if ($csa) {
                    $data['customer_group_id'] = $csa['customer_group_id'];
                    $this->load->model('account/customer_group');
                    $customer_group = $this->model_account_customer_group->getCustomerGroup($csa['customer_group_id']);
                    $data['customer_groups'] = [];
                    $data['customer_groups'][] = $customer_group;
                    $data['csa'] = $csa;
                    $csa_website = '';
                    if(!empty($csa['website'])) {
                        $csa_website = preg_replace('/^(?!https?:\/\/)/', 'http://', html_entity_decode($csa['website'], ENT_QUOTES));;
                    }
                    $data['csa_website'] = $csa_website;
                    $data['csa_name'] = $csa['csaname'];
		    $data['csa_pickup_address'] = html_entity_decode($csa['pickup_address']);
                    $data['csa_operating_hours'] = html_entity_decode($csa['operating_hours']);
                }
            }
                
            // -- Registration Fields
            
		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}


            // Registration Fields
            if (isset($this->request->post['address_1'])) {
                $data['address_1'] = $this->request->post['address_1'];
            } else {
                $data['address_1'] = '';
            }

            if (isset($this->request->post['address_2'])) {
                $data['address_2'] = $this->request->post['address_2'];
            } else {
                $data['address_2'] = '';
            }

            if (isset($this->request->post['postcode'])) {
                $data['postcode'] = $this->request->post['postcode'];
            } else {
                $data['postcode'] = '';
            }

            if (isset($this->request->post['city'])) {
                $data['city'] = $this->request->post['city'];
            } else {
                $data['city'] = '';
            }

            if (isset($this->request->post['zone_id'])) {
                $data['zone_id'] = (int)$this->request->post['zone_id'];
            }  else {
                $data['zone_id'] = '';
            }
            // -- Registration Fields
        
		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}

		// Custom Fields
		$data['custom_fields'] = array();
		
		$this->load->model('account/custom_field');
		
		$custom_fields = $this->model_account_custom_field->getCustomFields();
		
		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				$data['custom_fields'][] = $custom_field;
			}
		}
		
		if (isset($this->request->post['custom_field']['account'])) {
			$data['register_custom_field'] = $this->request->post['custom_field']['account'];
		} else {
			$data['register_custom_field'] = array();
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		if (isset($this->request->post['newsletter'])) {
			$data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$data['newsletter'] = '';
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
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

		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = false;
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


	        	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');

					$data['my_custom_input_fields'] = array();
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						$data['my_custom_input_fields'] = $this->model_extension_checkout_manager_checkout->getFields();
					}
				// Extendons - Checkout Manager /- End
		    	
		$this->response->setOutput($this->load->view('account/register', $data));
	}

	private function validate() {

	        	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');

		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						foreach ($this->request->post as $field_name => $f_value)
						{
							// query to get required and not required fields
							$q = $this->db->query("SELECT field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE field_name ='" . $field_name . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");

							if ($field_name == 'firstname' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
									$this->error['firstname'] = $this->language->get('error_firstname');
								}
							}

							if ($field_name == 'lastname' && $q->num_rows == 1) {
								if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
									$this->error['lastname'] = $this->language->get('error_lastname');
								}
							}

							if ($field_name == 'email' && $q->num_rows == 1) {
								if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
									$this->error['email'] = $this->language->get('error_email');
								}

								if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
									$this->error['warning'] = $this->language->get('error_exists');
								}
							}

							if ($field_name == 'telephone' && $q->num_rows == 1) {
								if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
									$this->error['telephone'] = $this->language->get('error_telephone');
								}
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
							if ($custom_field['location'] == 'account') {
								if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
									$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
								} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
									$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
								}
							}
						}

						if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
							$this->error['password'] = $this->language->get('error_password');
						}


            // Registration Fields
            if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
                $this->error['address_1'] = $this->language->get('error_address_1');
            }

            if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
                $this->error['city'] = $this->language->get('error_city');
            }

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry(223); // Country - US

            if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
                $this->error['postcode'] = $this->language->get('error_postcode');
            }

            if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
                $this->error['zone'] = $this->language->get('error_zone');
            }
            // -- Registration Fields
            
						if ($this->request->post['confirm'] != $this->request->post['password']) {
							$this->error['confirm'] = $this->language->get('error_confirm');
						}

						// if is my custom address fields
						$customPaymentAddress = array();
						if (isset($this->request->post['billing_address']))
						{
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
										$this->error[$field_name2[0]] = $label." field is required!";
									}
								}
							}
						}
					} else {
				// Extendons - Checkout Manager /- End
		    	
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
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
			if ($custom_field['location'] == 'account') {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}
		}

		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
			$this->error['password'] = $this->language->get('error_password');
		}


            // Registration Fields
            if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
                $this->error['address_1'] = $this->language->get('error_address_1');
            }

            if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
                $this->error['city'] = $this->language->get('error_city');
            }

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry(223); // Country - US

            if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
                $this->error['postcode'] = $this->language->get('error_postcode');
            }

            if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
                $this->error['zone'] = $this->language->get('error_zone');
            }
            // -- Registration Fields
            
		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}


	        	// Extendons - Checkout Manager
					}
				// Extendons - Checkout Manager /- End
		    	
		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		// Agree to terms
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
		
		return !$this->error;
	}

	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}