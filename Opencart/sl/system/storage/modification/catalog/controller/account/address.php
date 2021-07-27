<?php
class ControllerAccountAddress extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/address');

		$this->getList();
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/address');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
            	// Extendons - Checkout Manager
					$address_id = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);
				
					if (isset($this->request->post['billing_address'])) {
						$data = $this->request->post;
						$data['address_id'] = $address_id;

						$this->load->model('extension/checkout_manager/checkout');
						$this->model_extension_checkout_manager_checkout->addCustomAddress($data);
					}
				// Extendons - Checkout Manager /- End
		    	
			
			$this->session->data['success'] = $this->language->get('text_add');

			$this->response->redirect($this->url->link('account/address', '', true));
		}

		$this->getForm();
	}

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/address');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);

            	// Extendons - Checkout Manager
					if (isset($this->request->post['billing_address'])) 
					{
						$this->load->model('extension/checkout_manager/checkout');
						$this->model_extension_checkout_manager_checkout->editCustomAddress($this->request->get['address_id'], $this->request->post);
					}
				// Extendons - Checkout Manager /- End
		    	

			// Default Shipping Address
			if (isset($this->session->data['shipping_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address']['address_id'])) {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Address
			if (isset($this->session->data['payment_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address']['address_id'])) {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->data['success'] = $this->language->get('text_edit');

			$this->response->redirect($this->url->link('account/address', '', true));
		}

		$this->getForm();
	}

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/address');

		if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->model_account_address->deleteAddress($this->request->get['address_id']);

			// Default Shipping Address
			if (isset($this->session->data['shipping_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address']['address_id'])) {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Address
			if (isset($this->session->data['payment_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address']['address_id'])) {
				unset($this->session->data['payment_address']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->data['success'] = $this->language->get('text_delete');

			$this->response->redirect($this->url->link('account/address', '', true));
		}

		$this->getList();
	}

	protected function getList() {
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/address', '', true)
		);

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

		$data['addresses'] = array();

		$results = $this->model_account_address->getAddresses();

		
	        	// Extendons - Checkout Manager
	        		foreach ($results as $result) {
						if (isset($result['my_custom_address']) && !empty($result['my_custom_address']))
						{
							unset($result['my_custom_address']['db_data_id']);
							unset($result['my_custom_address']['zone_code']);
							unset($result['my_custom_address']['iso_code_2']);
							unset($result['my_custom_address']['iso_code_3']);

			    			$find = array();
			    			$replace = array();
			    			$format = '';

			    			// if you have given custom address format or you can use default
			    			if ($result['my_custom_address']['address_format']) {
								$format .= $result['my_custom_address']['address_format'];
							} else {
								$format .= '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
							}

						    foreach ($result['my_custom_address'] as $key1 => $value1) {
						    	// echo"<pre>"; print_r($value1);
					        	if (!is_array($value1)) {
					        		if ($key1 != 'address_format') {
					        			array_push($find, "{" . $key1."}"); // push keys into find array
					        			$replace[$key1] = ucwords($value1); // assign values to each relavent keys
					        		}
					        	} else {
					        		foreach ($value1 as $key2 => $value2) 
					        		{
					        			// push custom created keys into find[] array
					        			array_push($find, "{" . $value2['meta_key']."}");
					        			
					        			// add custom created keys into format var
						        		$fields = array('firstname', 'lastname', 'company', 'address_1', 'address_2', 'city', 'postcode', 'zone', 'country');
					        			if (!in_array($value2['meta_key'], $fields)) {
					        				$format .= "\n{" . $value2['meta_key'] ."}\n";
					        			}
						        		
					        			// if value has serialized data
					        			// $value3 = @unserialize($value2['meta_value']);

					        			if (is_array($value2['meta_value'])) {
					        				// assign values to each relavent keys
					        				$replace[$value2['meta_key']] = ucwords(implode(', ', $value2['meta_value']));
					        			} else {
					        				// assign values to each relavent keys
					        				$replace[$value2['meta_key']] = ucwords($value2['meta_value']); 
					        			}
					        		} // end foreach
					        	}
					        }
							
			    			$data['addresses'][] = array(
								'address_id' => $result['address_id'],
								'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
								'update'     => $this->url->link('account/address/edit', 'address_id=' . $result['address_id'], true),
								'delete'     => $this->url->link('account/address/delete', 'address_id=' . $result['address_id'], true)
							);

						} else {

							if ($result['address_format']) {
								$format = $result['address_format'];
							} else {
								$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
							}

							$find = array(
								'{firstname}',
								'{lastname}',
								'{company}',
								'{address_1}',
								'{address_2}',
								'{city}',
								'{postcode}',
								'{zone}',
								'{zone_code}',
								'{country}'
							);

							$replace = array(
								'firstname' => $result['firstname'],
								'lastname'  => $result['lastname'],
								'company'   => $result['company'],
								'address_1' => $result['address_1'],
								'address_2' => $result['address_2'],
								'city'      => $result['city'],
								'postcode'  => $result['postcode'],
								'zone'      => $result['zone'],
								'zone_code' => $result['zone_code'],
								'country'   => $result['country']
							);

							$data['addresses'][] = array(
								'address_id' => $result['address_id'],
								'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
								'update'     => $this->url->link('account/address/edit', 'address_id=' . $result['address_id'], true),
								'delete'     => $this->url->link('account/address/delete', 'address_id=' . $result['address_id'], true)
							);
						} // endif
					} // endforeach
				// Extendons - Checkout Manager /- End
		    	

		$data['add'] = $this->url->link('account/address/add', '', true);
		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/address_list', $data));
	}

	protected function getForm() {
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/address', '', true)
		);

		if (!isset($this->request->get['address_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_address_add'),
				'href' => $this->url->link('account/address/add', '', true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_address_edit'),
				'href' => $this->url->link('account/address/edit', 'address_id=' . $this->request->get['address_id'], true)
			);
		}

		$data['text_address'] = !isset($this->request->get['address_id']) ? $this->language->get('text_address_add') : $this->language->get('text_address_edit');


            	// Extendons - Checkout Manager
					// extendons - error
					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						$get_fields = $this->db->query("SELECT field_name FROM " . DB_PREFIX . "extendons_checkout_fields ");

						if ($get_fields->num_rows > 1) {
							
							foreach ($get_fields->rows as $key => $value) {

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

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}


            	// Extendons - Checkout Manager
					}
				// Extendons - Checkout Manager /- End
		    	
		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}
		
		if (!isset($this->request->get['address_id'])) {
			$data['action'] = $this->url->link('account/address/add', '', true);
		} else {
			$data['action'] = $this->url->link('account/address/edit', 'address_id=' . $this->request->get['address_id'], true);
		}

		if (isset($this->request->get['address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$address_info = $this->model_account_address->getAddress($this->request->get['address_id']);

            	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
		            	if (isset($address_info['update_my_custom_address']) && !empty($address_info['update_my_custom_address']))
						{
							$data['update_my_custom_address'] = $address_info['update_my_custom_address'];

						} else {
							// first signup registered address
							$data['registered_address_before_module'] = $address_info;

							$getAllFieldsID = $this->db->query("SELECT db_field_id FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 ORDER BY field_sort_order");

					        foreach ($getAllFieldsID->rows as $value) {

					            $field_id = $value['db_field_id'];

					            // get single field using has id
					            $getSingleFieldByID = $this->db->query("SELECT * FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND db_field_id = " . $field_id);
					            
					            $field_to_show = @unserialize($getSingleFieldByID->row['field_to_show']);
					            $field_visibility = @unserialize($getSingleFieldByID->row['field_visibility']);

					            if ($field_to_show == true) {
					                $getSingleFieldByID->row['field_to_show'] = $field_to_show;
					            }
					            if ($field_visibility == true) {
					                $getSingleFieldByID->row['field_visibility'] = $field_visibility;
					            }

					            // get field option
					            $getSingleFieldByID_options = $this->db->query("SELECT * FROM " . DB_PREFIX . "extendons_checkout_fields_options WHERE db_field_id = " . $field_id);
					            
					            $getSingleFieldByID->row['field_options'] = array();

					            // if options exists
					            if ($getSingleFieldByID_options->num_rows) {
					                $getSingleFieldByID->row['field_options'] = $getSingleFieldByID_options->rows;
					            }

					            // make it multidimensional
					            $data['update_my_custom_address'][] = $getSingleFieldByID->row;
					        }

						}
					}
				// Extendons - Checkout Manager /- End
		    	
		}


            	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
						// Code removed
					} else {
				// Extendons - Checkout Manager /- End
		    	
		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($address_info)) {
			$data['firstname'] = $address_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($address_info)) {
			$data['lastname'] = $address_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['company'])) {
			$data['company'] = $this->request->post['company'];
		} elseif (!empty($address_info)) {
			$data['company'] = $address_info['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->request->post['address_1'])) {
			$data['address_1'] = $this->request->post['address_1'];
		} elseif (!empty($address_info)) {
			$data['address_1'] = $address_info['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
			$data['address_2'] = $this->request->post['address_2'];
		} elseif (!empty($address_info)) {
			$data['address_2'] = $address_info['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} elseif (!empty($address_info)) {
			$data['postcode'] = $address_info['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (!empty($address_info)) {
			$data['city'] = $address_info['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = (int)$this->request->post['country_id'];
		}  elseif (!empty($address_info)) {
			$data['country_id'] = $address_info['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = (int)$this->request->post['zone_id'];
		}  elseif (!empty($address_info)) {
			$data['zone_id'] = $address_info['zone_id'];
		} else {
			$data['zone_id'] = '';
		}


            	// Extendons - Checkout Manager
					}
				// Extendons - Checkout Manager /- End
		    	
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom fields
		$data['custom_fields'] = array();
		
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') {
				$data['custom_fields'][] = $custom_field;
			}
		}
		
		if (isset($this->request->post['custom_field']['address'])) {
			$data['address_custom_field'] = $this->request->post['custom_field']['address'];
		} elseif (isset($address_info)) {
			$data['address_custom_field'] = $address_info['custom_field'];
		} else {
			$data['address_custom_field'] = array();
		}

		if (isset($this->request->post['default'])) {
			$data['default'] = $this->request->post['default'];
		} elseif (isset($this->request->get['address_id'])) {
			$data['default'] = $this->customer->getAddressId() == $this->request->get['address_id'];
		} else {
			$data['default'] = false;
		}

		$data['back'] = $this->url->link('account/address', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


            	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
	            		if ($this->request->server['REQUEST_METHOD'] == 'POST')
						{
							$getAllFieldsID = $this->db->query("SELECT db_field_id FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 ORDER BY field_sort_order");

							foreach ($getAllFieldsID->rows as $key => $value) 
							{
								$field_id = $value['db_field_id'];

								// get single field using its id
								$getSingleFieldByID = $this->db->query("SELECT * FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND db_field_id = " . $field_id);
					            
					            $field_to_show = @unserialize($getSingleFieldByID->row['field_to_show']);
					            $field_visibility = @unserialize($getSingleFieldByID->row['field_visibility']);

					            if ($field_to_show == true) {
					                $getSingleFieldByID->row['field_to_show'] = $field_to_show;
					            }
					            if ($field_visibility == true) {
					                $getSingleFieldByID->row['field_visibility'] = $field_visibility;
					            }

					            // get field option
					            $getSingleFieldByID_options = $this->db->query("SELECT * FROM " . DB_PREFIX . "extendons_checkout_fields_options WHERE db_field_id = " . $field_id);
					            
					            $getSingleFieldByID->row['field_options'] = array();

					            // if options exists
					            if ($getSingleFieldByID_options->num_rows) {
					                $getSingleFieldByID->row['field_options'] = $getSingleFieldByID_options->rows;
					            }
								
								$data['error_in_input_fields'][] = $getSingleFieldByID->row;
							}

							// entered data with errors
							if (isset($this->request->post)) {
								foreach ($this->request->post as $key => $value) {
									if ($key == 'billing_address') {
										foreach ($this->request->post['billing_address'] as $key => $value) {
											$data['input_keys'][$key] = $value;
										}
									}
									$data['input_keys'][$key] = $value;
								}
							}
						}

						if (!isset($this->request->get['address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST'))
						{
							$this->load->model('extension/checkout_manager/checkout');

							$data['my_input_fields'] = $this->model_extension_checkout_manager_checkout->getFields();

							if (!isset($data['my_input_fields'])) {
								$data['my_input_fields'] = array();
							}
						}
					}
				// Extendons - Checkout Manager /- End
		    	
		$this->response->setOutput($this->load->view('account/address_form', $data));
	}

	protected function validateForm() {

            	// Extendons - Checkout Manager
					$this->load->model('extension/checkout_manager/checkout');
		        	if ($this->model_extension_checkout_manager_checkout->isModuleInstalled('checkout_manager')) {
	            		foreach ($this->request->post as $field_name => $value) {
							if (is_array($value))
							{
								$billing_address = $value;

								foreach ($billing_address as $field_name2 => $value2)
								{
									$check_if_exist = $this->db->query("SELECT field_visibility FROM " . DB_PREFIX . "extendons_checkout_fields  WHERE `field_name` = '" . $field_name2 . "' AND `field_visibility` <> '0' ");
									
									if ($check_if_exist->num_rows) {
										unset($billing_address[$field_name2]);
									}
								}

								$this->request->post[$field_name] = $billing_address;

							} else {
								
								$check_if_exist = $this->db->query("SELECT field_visibility FROM " . DB_PREFIX . "extendons_checkout_fields  WHERE `field_name` = '" . $field_name . "' AND `field_visibility` <> '0' ");

								if ($check_if_exist->num_rows) {
									unset($this->request->post[$field_name]);
								}
							}
						}

						foreach ($this->request->post as $key => $value)
						{
							// query to get required fields
							$q = $this->db->query("SELECT field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE field_name ='" . $key . "' AND field_condition = 'required' AND field_visibility = '0' LIMIT 1");

							if ($key == 'postcode' && $q->num_rows == 1) {
								if (isset($this->request->post['country_id'])) 
								{
									$this->load->model('localisation/country');
									$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

									if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
										$this->error['postcode'] = $this->language->get('error_postcode');
									}
								} elseif (!isset($this->request->post['country_id'])) {
									if (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10) {
										$this->error['postcode'] = $this->language->get('error_postcode');
									}
								}

							} elseif ($key == 'country_id' && $q->num_rows == 1) {
								if ($this->request->post['country_id'] == '' || !is_numeric($this->request->post['country_id'])) {
									$this->error['country'] = $this->language->get('error_country');
								}

							} elseif ($key == 'zone_id' && $q->num_rows == 1) {
								if (isset($this->request->post['country_id'])) {
									if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
										$this->error['zone'] = $this->language->get('error_zone');
									}
								}

							} else {
								if ($key && $q->num_rows == 1) {
									if ((utf8_strlen(trim($value)) < 1) || (utf8_strlen(trim($value)) > 32)) {
										$this->error[$key] = $this->language->get('error_'.$key);
									}
								}
							}
						}

						if (isset($this->request->post['billing_address']))
						{
							$data = $this->request->post['billing_address'];

							foreach ($data as $key => $value) 
							{
								// echo $key;
								$q = $this->db->query("SELECT field_label, field_name, field_condition FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND field_name ='" . $key . "' AND field_visibility = '0' LIMIT 1");

								$condition = $q->row['field_condition'];
								$label = $q->row['field_label'];
								$name = $q->row['field_name'];

								if ($value != 'required') { 
									if ($condition == 'required') {
										if ($value == '') {
											$this->error[$name] = $label. ' is required!';
										}

										if (is_array($value) && empty($value)) {
											$this->error[$name] = $label. ' is required!';
										} else {
											if (empty($value)) {
												$this->error[$name] = $label. ' is required!';
											}
										}
									}
								} else {
									$this->error[$name] = $label. ' is required!';
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

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			$this->error['address_1'] = $this->language->get('error_address_1');
		}

		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			$this->error['city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '' || !is_numeric($this->request->post['country_id'])) {
			$this->error['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
			$this->error['zone'] = $this->language->get('error_zone');
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
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if ($this->model_account_address->getTotalAddresses() == 1) {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		if ($this->customer->getAddressId() == $this->request->get['address_id']) {
			$this->error['warning'] = $this->language->get('error_default');
		}

		return !$this->error;
	}
}