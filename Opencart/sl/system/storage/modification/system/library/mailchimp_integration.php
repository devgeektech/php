<?php
//==============================================================================
// MailChimp Integration Pro v303.3
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class Mailchimp_Integration {
	private $type = 'module';
	private $name = 'mailchimp_integration';
	private $settings;
	
	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->session = $registry->get('session');
		$this->tax = $registry->get('tax');
		$this->url = $registry->get('url');
	}
	
	//==============================================================================
	// Utility functions
	//==============================================================================
	public function getLists() {
		$response = $this->curlRequest('GET', 'lists', array('count' => 99));
		return (isset($response['lists'])) ? $response['lists'] : array();
	}
	
	public function getMergeFields($listid) {
		$response = $this->curlRequest('GET', 'lists/' . $listid . '/merge-fields', array('count' => 99));
		return (isset($response['merge_fields'])) ? $response['merge_fields'] : array();
	}
	
	public function getInterestGroups($listid) {
		$response = $this->curlRequest('GET', 'lists/' . $listid . '/interest-categories');
		$interest_categories = (isset($response['categories'])) ? $response['categories'] : array();
		
		foreach ($interest_categories as &$interest_category) {
			$response = $this->curlRequest('GET', 'lists/' . $listid . '/interest-categories/' . $interest_category['id'] . '/interests', array('count' => 99));
			$interest_category['interests'] = (isset($response['interests'])) ? $response['interests'] : array();
		}
		
		return $interest_categories;
	}
	
	public function getMemberInfo($listid, $email) {
		$response = $this->curlRequest('GET', 'lists/' . $listid . '/members/' . md5(strtolower($email)));
		return (empty($response['error'])) ? $response : array();
	}
	
	public function createWebhooks($lists) {
		$settings = $this->getSettings();
		
		$catalog_url = ($this->config->get('config_ssl') || $this->config->get('config_secure')) ? str_replace('http:', 'https:', HTTP_CATALOG) : HTTP_CATALOG;
		$url = $catalog_url . 'index.php?route=extension/' . $this->type . '/' . $this->name . '/webhook&key=' . md5($this->config->get('config_encryption'));
		
		$webhooks = (!empty($settings['webhooks'])) ? explode(';', $settings['webhooks']) : array();
		if (empty($webhooks)) return;
		
		foreach ($lists as $list) {
			$response = $this->curlRequest('GET', 'lists/' . $list['id'] . '/webhooks');
			
			$mc_webhooks = array();
			if (empty($response['error'])) {
				foreach ($response['webhooks'] as $mc_webhook) {
					if ($mc_webhook['url'] == $url) {
						$this->curlRequest('DELETE', 'lists/' . $list['id'] . '/webhooks/' . $mc_webhook['id'], array());
					}
				}
			}
			
			$curl_data = array(
				'url'		=> $url,
				'events'	=> array(
					'subscribe'		=> in_array('subscribe', $webhooks),
					'unsubscribe'	=> in_array('unsubscribe', $webhooks),
					'profile'		=> in_array('profile', $webhooks),
					'upemail'		=> in_array('profile', $webhooks),
					'cleaned'		=> in_array('cleaned', $webhooks),
					'campaign'		=> false,
				),
				'sources'	=> array(
					'user'		=> true,
					'admin'		=> true,
					'api'		=> true,
				),
			);
			
			$response = $this->curlRequest('POST', 'lists/' . $list['id'] . '/webhooks', $curl_data);
		}
	}
	
	public function createStores($lists) {
		$settings = $this->getSettings();
		
		foreach ($lists as $list) {
			$store_ids = array();
			foreach ($settings as $key => $value) {
				$explode = explode('-', $key);
				if ($explode[0] == 'store' && $value == $list['id']) {
					$store_ids[] = $explode[1];
				}
			}
			
			if (empty($store_ids)) continue;
			
			foreach ($store_ids as $store_id) {
				$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = " . (int)$store_id);
				
				$store = array();
				foreach ($store_query->rows as $row) {
					$store[$row['key']] = $row['value'];
				}
				if (empty($store['config_url'])) {
					$store['config_url'] = HTTP_CATALOG;
				}
				
				$country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$store['config_country_id'])->row;
				$zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$store['config_zone_id'])->row;
				
				$curl_data = array(
					'id'				=> $list['id'] . '-' . $store_id,
					'list_id'			=> $list['id'],
					'name'				=> $store['config_name'],
					'platform'			=> 'OpenCart',
					'domain'			=> $store['config_url'],
					'email_address'		=> $store['config_email'],
					'currency_code'		=> strtoupper($store['config_currency']),
					'primary_locale'	=> $store['config_language'],
					'phone'				=> $store['config_telephone'],
					'address'			=> array(
						'country'			=> $country['name'],
						'country_code'		=> $country['iso_code_2'],
						'province'			=> (!empty($zone['name'])) ? $zone['name'] : '(none)',
						'province_code'		=> (!empty($zone['code'])) ? $zone['code'] : '',
					),
				);
				
				$response = $this->curlRequest('POST', 'ecommerce/stores', $curl_data);
				
				if (!empty($response['error']) && strpos($response['error'], 'store with the domain')) {
					$stores_response = $this->curlRequest('GET', 'ecommerce/stores');
					foreach ($stores_response['stores'] as $sr) {
						if ($sr['domain'] == $store['config_url']) {
							$this->curlRequest('PATCH', 'ecommerce/stores/' . $sr['id'], array('domain' => ''));
							$this->curlRequest('POST', 'ecommerce/stores', $curl_data);
						}
					}
				}
				
				$this->curlRequest('PATCH', 'ecommerce/stores/' . $curl_data['id'], $curl_data);
			}
		}
	}
	
	public function determineList($customer, $address) {
		$settings = $this->getSettings();
		
		if (!empty($settings['mapping'])) {
			foreach ($settings['mapping'] as $mapping) {
				if (empty($mapping['list']) || empty($mapping['rule'])) continue;
				
				// Build rules list
				$rules = array('list' => $mapping['list']);
				foreach ($mapping['rule'] as $rule) {
					if (empty($rule['type'])) continue;
					$rules[$rule['type']][$rule['comparison']][] = $rule['value'];
				}
				
				if (isset($address['country_id']) && isset($address['zone_id']) && isset($address['city'])) {
					// Find geo zones
					$geo_zones = array();
					$geo_zones_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = " . (int)$address['country_id'] . " AND (zone_id = 0 OR zone_id = " . (int)$address['zone_id'] . ")");
					if ($geo_zones_query->num_rows) {
						foreach ($geo_zones_query->rows as $geo_zone) {
							$geo_zones[] = $geo_zone['geo_zone_id'];
						}
					} else {
						$geo_zones = array(0);
					}
					
					// Location Criteria
					if (isset($rules['city'])) {
						$this->commaMerge($rules['city']);
					}
					
					if ($this->ruleViolation($rules, 'city', strtolower($address['city'])) ||
						$this->ruleViolation($rules, 'geo_zone', $geo_zones)
					) {
						continue;
					}
					
					if (isset($rules['postcode']) && isset($address['postcode'])) {
						$this->commaMerge($rules['postcode']);
						foreach ($rules['postcode'] as $comparison => $postcodes) {
							$in_range = $this->inRange($address['postcode'], $postcodes, 'postcode ' . $comparison, $mapping['list']);
							if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
								continue 2;
							}
						}
					}
				}
				
				// Order Criteria
				if ($this->ruleViolation($rules, 'currency', $this->config->get('config_currency')) ||
					$this->ruleViolation($rules, 'customer_group', $customer['customer_group_id']) ||
					$this->ruleViolation($rules, 'language', !empty($this->session->data['language']) ? $this->session->data['language'] : $this->config->get('config_language')) ||
					$this->ruleViolation($rules, 'store', isset($customer['store_id']) ? $customer['store_id'] : $this->config->get('config_store_id'))
				) {
					continue;
				}
				
				return $mapping['list'];
			}
		}
		
		return $settings['listid'];
	}
	
	//==============================================================================
	// send()
	//==============================================================================
	public function send($data) {
		$settings = $this->getSettings();
		
		if (empty($settings['status'])) {
			$this->logMessage('Error: Extension is disabled');
			return;
		} elseif (empty($settings['apikey'])) {
			$this->logMessage('Error: API Key is not filled in');
			return;
		} elseif (empty($settings['listid'])) {
			$this->logMessage('Error: Default list is not set');
			return;
		}
		
		unset($this->session->data['mailchimp_lists']);
		unset($this->session->data['mailchimp_subscribed_lists']);
		
		// Get customer information
		if (!empty($data['customer_id'])) {
			if (!empty($data['newsletter']) && !empty($settings['subscribed_group'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['subscribed_group'] . " WHERE customer_id = " . (int)$data['customer_id']);
			} elseif (empty($data['newsletter']) && !empty($settings['unsubscribed_group'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['unsubscribed_group'] . " WHERE customer_id = " . (int)$data['customer_id']);
			}
			
			$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$data['customer_id'])->row;
			if (isset($data['customer_newsletter'])) {
				$customer['newsletter'] = $data['customer_newsletter'];
			}
			
			if (!empty($data['custom_field'])) {
				$customer['custom_field'] = $data['custom_field'];
			} elseif (!empty($customer['custom_field'])) {
				$customer['custom_field'] = (version_compare(VERSION, '2.1', '<')) ? unserialize($customer['custom_field']) : json_decode($customer['custom_field'], true);
			} else {
				$customer['custom_field'] = array();
			}
		} else {
			$customer = array(
				'customer_id'		=> 0,
				'customer_group_id'	=> (isset($data['customer_group_id'])) ? $data['customer_group_id'] : 0,
				'email'				=> (isset($data['email'])) ? $data['email'] : '',
				'firstname'			=> '',
				'lastname'			=> '',
				'address_id'		=> '',
				'telephone'			=> '',
				'newsletter'		=> 0,
				'custom_field'		=> (isset($data['custom_field'])) ? $data['custom_field'] : array(),
			);
		}
		
		// Set customer group name
		$customer_group = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = " . (int)$customer['customer_group_id'])->row;
		$customer['customer_group_name'] = (!empty($customer_group['name'])) ? $customer_group['name'] : '';
		
		// Get address information

            // MailChimp Integration Customization
            // get CSA
            if (!empty($customer_group)) {
                $customer_csa = $this->db->query("SELECT * FROM " . DB_PREFIX . "csa WHERE customer_group_id = " . (int)$customer['customer_group_id'])->row;
                $customer['customer_csa_name'] = (!empty($customer_csa['csaname'])) ? $customer_csa['csaname'] : '';
            }
            // check if customer has order this harvest year
			$harvest = $this->db->query("SELECT harvest_id FROM " . DB_PREFIX . "harvests where status = 1")->row;
			$customer['has_order'] = '';
			if ($harvest) {
				$harvest_id = $harvest['harvest_id'];
				$order = $this->db->query("SELECT * FROM " . DB_PREFIX . "order where customer_id = '" . $data['customer_id'] . "' and harvest_id = '" . $harvest_id . "'")->row;
				if (!empty($order)) {
					$customer['has_order'] = 'Yes';
				}
			}
            // -- end MailChimp Integration Customization
            
		if (!empty($data['addresses'])) {
			$data['address'] = $data['addresses'];
		}
		if (!empty($data['address'])) {
			foreach ($data['address'] as $address_data) {
				$address = $address_data;
				if (!empty($address['default'])) break;
			}
		} else {
			$address = $data;
		}
		unset($data['address']);
		
		if (!empty($address['country_id'])) {
			$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$address['country_id']);
			$address['country_name'] = (!empty($country_query->row['name'])) ? $country_query->row['name'] : '';
			$address['iso_code_2'] = (!empty($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
		}
		if (!empty($address['zone_id'])) {
			$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$address['zone_id']);
			$address['zone_name'] = (!empty($zone_query->row['name'])) ? html_entity_decode($zone_query->row['name'], ENT_QUOTES, 'UTF-8') : '';
		}
		
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
		$default_address = ($address_query->num_rows) ? $address_query->row : array(
			'address_1'		=> '',
			'address_2'		=> '',
			'city'			=> '',
			'postcode'		=> '',
			'zone_id'		=> '',
			'country_id'	=> '',
		);
		
		if (!empty($address['custom_field'])) {
			$customer['custom_field'] += $address['custom_field'];
		} elseif (!empty($default_address['custom_field'])) {
			$address_custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($default_address['custom_field']) : json_decode($default_address['custom_field'], true);
			$customer['custom_field'] += $address_custom_fields; // needs + to avoid losing indexes
		}
		
		$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$default_address['country_id']);
		$default_address['country_name'] = (!empty($country_query->row['name'])) ? $country_query->row['name'] : '';
		$default_address['iso_code_2'] = (!empty($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
		
		$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$default_address['zone_id']);
		$default_address['zone_name'] = (!empty($zone_query->row['name'])) ? html_entity_decode($zone_query->row['name'], ENT_QUOTES, 'UTF-8') : '';
		
		$customer['address'] = array(
			'addr1'		=> (isset($address['address_1']))	? $address['address_1']		: $default_address['address_1'],
			'addr2'		=> (isset($address['address_2']))	? $address['address_2']		: $default_address['address_2'],
			'city'		=> (isset($address['city']))		? $address['city']			: $default_address['city'],
			'state'		=> (isset($address['zone_name']))	? $address['zone_name']		: $default_address['zone_name'],
			'zip'		=> (isset($address['postcode']))	? $address['postcode']		: $default_address['postcode'],
			'country'	=> (isset($address['iso_code_2']))	? $address['iso_code_2']	: $default_address['iso_code_2'],
		);
		
		$data['country_name'] = (isset($address['country_name'])) ? $address['country_name'] : $default_address['country_name'];
		$data['zone_name'] = (isset($address['zone_name'])) ? $address['zone_name'] : $default_address['zone_name'];
		
		// Set list_id
		if (empty($data['list'])) {
			$list_ids = array($this->determineList($customer, $address));
		} else {
			$list_ids = (is_array($data['list'])) ? $data['list'] : array($data['list']);
		}
		
		// Loop through lists, and Subscribe or Unsubscribe
		$errors = array();
		$first_loop = true;
		
		foreach ($list_ids as $list_id) {
			if (empty($data['newsletter'])) {
				$curl_request = 'PATCH';
				$curl_api = 'lists/' . $list_id . '/members/' . md5(strtolower($customer['email']));
				$curl_data = array(
					'status'	=> 'unsubscribed',
				);
			} else {
				// Unsubscribe customer from other lists first
				if (!empty($data['list']) && $first_loop) {
					foreach ($this->getLists() as $list) {
						if (in_array($list['id'], $list_ids)) continue;
						
						$curl_request = 'PATCH';
						$curl_api = 'lists/' . $list['id'] . '/members/' . md5(strtolower($customer['email']));
						$curl_data = array('status' => 'unsubscribed');
						$response = $this->curlRequest($curl_request, $curl_api, $curl_data);
					}
					
					$first_loop = false;
				}
				
				// Set up merge data
				$merge_array = array();
				
				// Language merge tag
				$language_id = (!empty($customer['language_id'])) ? $customer['language_id'] : 0;
				$language_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = " . (int)$language_id);
				
				if ($language_query->num_rows) {
					$merge_array['MC_LANGUAGE'] = $language_query->row['code'];
				} elseif (!empty($this->session->data['language'])) {
					$merge_array['MC_LANGUAGE'] = $this->session->data['language'];
				} elseif (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
					$language_region = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5));
					if ($language_region == 'fr-ca' || $language_region == 'pt-pt' || $language_region == 'es-es') {
						$merge_array['MC_LANGUAGE']	= substr($language_region, 0, 2) . '_' . strtoupper(substr($language_region, -2));
					} else {
						$merge_array['MC_LANGUAGE']	= substr($language_region, 0, 2);
					}
				} else {
					$merge_array['MC_LANGUAGE']	= $this->config->get('config_language');
				}
				
				// Other merge fields
				foreach ($this->getMergeFields($list_id) as $merge) {
					if ($merge['tag'] == 'EMAIL') continue;
					
					$merge_setting_value = (!empty($settings[$list_id . '_' . $merge['tag']])) ? $settings[$list_id . '_' . $merge['tag']] : '';

            // MailChimp Integration Customization
            if ($merge['tag'] === 'CSA' || $merge['tag'] === 'ORDERS') {
                if ($merge['tag'] === 'CSA') {
                    $merge_array[$merge['tag']] = $customer['customer_csa_name'];
                } else {
                    $merge_array[$merge['tag']] = $customer['has_order'];
                }
            } else // --- end MailChimp Integration Customization
            
					
					if (empty($merge_setting_value)) {
						if (!$merge['required']) continue;
						$merge_array[$merge['tag']] = ($merge['type'] == 'zip') ? '00000' : '(none)';
					} else {
						$merge_setting_split = explode(':', $merge_setting_value);
						$table = $merge_setting_split[0];
						$column = ($merge_setting_split[1] == 'address_id') ? 'address' : $merge_setting_split[1];
						
						if ($table == 'custom_field') {
							if (!empty($customer['custom_field'][$column])) {
								$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_id = " . (int)$column . " AND custom_field_value_id = " . (int)$customer['custom_field'][$column]);
								if ($custom_field_value_query->num_rows) {
									$merge_array[$merge['tag']] = $custom_field_value_query->row['name'];
								} else {
									$merge_array[$merge['tag']] = $customer['custom_field'][$column];
								}
							}
						} elseif (!empty($data[$column])) {
							$merge_array[$merge['tag']] = $data[$column];
						} elseif (!empty($customer[$column])) {
							$merge_array[$merge['tag']] = $customer[$column];
						} else {
							$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . " WHERE customer_id = " . (int)$customer['customer_id']);
							if (!empty($customer_query->row[$column])) {
								$merge_array[$merge['tag']] = $customer_query->row[$column];
							} elseif ($merge['required']) {
								$merge_array[$merge['tag']] = ($merge['type'] == 'zip') ? '00000' : '(none)';
							}
						}
						
						if ($merge['type'] == 'phone' && !empty($merge_array[$merge['tag']])) {
							$telephone = preg_replace('/[^0-9]/', '', $merge_array[$merge['tag']]);
							if ($telephone || $merge['required']) {
								$merge_array[$merge['tag']] = substr($telephone, 0, 3) . '-' . substr($telephone, 3, 3) . '-' . substr($telephone, 6);
							} else {
								unset($merge_array[$merge['tag']]);
							}
						}
					}
					
					if ($merge['type'] == 'birthday') {
						$explode = explode('-', $merge_array[$merge['tag']]);
						if (isset($explode[1]) && isset($explode[2])) {
							$merge_array[$merge['tag']] = $explode[1] . '/' . $explode[2];
						} else {
							unset($merge_array[$merge['tag']]);
						}
					}
					
					if ($merge['type'] == 'address') {
						if (empty($merge_array[$merge['tag']]['addr1']) || empty($merge_array[$merge['tag']]['city']) || empty($merge_array[$merge['tag']]['state']) || empty($merge_array[$merge['tag']]['country'])) {
							unset($merge_array[$merge['tag']]);
						} else {
							$zip = trim($merge_array[$merge['tag']]['zip']);
							if (empty($zip)) $merge_array[$merge['tag']]['zip'] = '00000';
						}
					}
				}
				
				foreach ($merge_array as &$merge) {
					if (is_array($merge)) {
						foreach ($merge as &$m) {
							$m = html_entity_decode($m, ENT_QUOTES, 'UTF-8');
						}
					} else {
						$merge = html_entity_decode($merge, ENT_QUOTES, 'UTF-8');
					}
				}
				
				// Subscribe
				if (isset($data['update_existing']) && !$data['update_existing']) {
					$curl_request = 'POST';
					$curl_api = 'lists/' . $list_id . '/members';
				} else {
					$curl_request = 'PUT';
					$curl_api = 'lists/' . $list_id . '/members/' . md5(strtolower($customer['email']));
				}
				
				$curl_data = array(
					'email_type'	=> 'html',
					'status'		=> 'subscribed',
					'merge_fields'	=> $merge_array,
					'language'		=> $merge_array['MC_LANGUAGE'],
					'email_address'	=> (isset($data['email'])) ? $data['email'] : $customer['email'],
				);
				
				/*
				$curl_data['marketing_permissions'] = array(array(
					'marketing_permission_id'	=> '36a9e0c2c9',
					'enabled'					=> true,
				));
				*/
				
				//if (empty($curl_data['merge_fields'])) unset($curl_data['merge_fields']);
				
				$double_optin = (isset($data['double_optin'])) ? $data['double_optin'] : $settings['double_optin'];
				
				if ($double_optin && !$customer['newsletter']) {
					$curl_data['status'] = 'pending';
					
					/* The "status_if_new" field isn't documented in the API currently, so I'm not sure if they removed it or if it just can't be set alongside the "status" field
					if ($curl_request == 'POST') {
						$curl_data['status'] = 'pending';
					} else {
						$curl_data['status_if_new'] = 'pending';
					}
					*/
				} else {
					$curl_data['ip_opt'] = $_SERVER['REMOTE_ADDR'];
				}
				
				// Interest Groups
				if (!empty($settings['interest_groups']) && !empty($data['interests'])) {
					$interests = array();
					foreach ($data['interests'] as $id) {
						$interests[$id] = true;
					}
					$curl_data['interests'] = $interests;
					unset($this->session->data['mailchimp_interest_groups']);
					unset($this->session->data['mailchimp_interests']);
				}
			}
			
			$response = $this->curlRequest($curl_request, $curl_api, $curl_data);
			
			if (!empty($response['error'])) {
				$errors[] = $response['error'];
			}
		}
		
		return ($errors) ? '&bull; ' . implode('<br />&bull; ', $errors) : '';
	}
	
	//==============================================================================
	// syncCustomers()
	//==============================================================================
	public function syncCustomers($start, $end) {
		$settings = $this->getSettings();
		
		if (empty($settings['apikey'])) {
			return 'Error: No API Key is filled in';
		} elseif (empty($settings['listid'])) {
			return 'Error: No List ID is set';
		}
		
		$output = "Completed!\n\n";
		$data_center = explode('-', $settings['apikey']);
		$lists = $this->getLists();
		
		// MailChimp to OpenCart
		if ($settings['autocreate']) {
			// Get all OpenCart e-mails
			$opencart_emails = array();
			$all_customers = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer")->rows;
			
			foreach ($all_customers as $customer) {
				$opencart_emails[] = strtolower($customer['email']);
			}
			
			// Loop through lists to get MailChimp e-mails
			$created = 0;
			$autocreate_lists = explode(';', $settings['autocreate_lists']);
			
			foreach ($lists as $list) {
				if (!in_array($list['id'], $autocreate_lists)) continue;
				
				$context = stream_context_create(array('http' => array('ignore_errors' => '1')));
				$response = @file_get_contents('https://' . $data_center[1] . '.api.mailchimp.com/export/1.0/list/?apikey=' . $settings['apikey'] . '&id=' . $list['id'], false, $context);
				
				$mailchimp_emails = array();
				foreach (explode("\n", $response) as $line) {
					$subscriber = json_decode($line);
					if (strpos($subscriber[0], '@') === false) continue;
					$mailchimp_emails[] = strtolower($subscriber[0]);
				}
				$diff_emails = array_diff($mailchimp_emails, $opencart_emails);
				
				// Auto-create customers that don't exist in OpenCart
				foreach ($diff_emails as $email) {
					$response = $this->curlRequest('GET', 'lists/' . $list['id'] . '/members/' . md5(strtolower($email)));
					
					if (!empty($response['error'])) {
						$this->logMessage('SYNC ERROR: ' . $errors);
						return $response['error'];
					} else {
						$this->createCustomer($response);
						$created++;
					}
				}
			}
			
			$output .= $created . " customer(s) created in OpenCart\n";
		}
		
		// Get merge fields
		$merge_array = array();
		$birthday_fields = array();
		
		foreach ($lists as $list) {
			foreach ($this->getMergeFields($list['id']) as $merge) {
				if ($merge['tag'] == 'EMAIL') {
					$merge_array[$list['id']]['EMAIL'] = 'email';
					continue;
				}
				
				$merge_setting_value = (!empty($settings[$list['id'] . '_' . $merge['tag']])) ? $settings[$list['id'] . '_' . $merge['tag']] : '';
				
				if (empty($merge_setting_value)) {
					if (!$merge['required']) continue;
					$merge_array[$list['id']][$merge['tag']] = ($merge['type'] == 'zip') ? '00000' : '(none)';
				} else {
					$merge_setting_split = explode(':', $merge_setting_value);
					if ($merge_setting_split[0] == 'customer' || $merge_setting_split[1] == 'address_id') {
						$merge_array[$list['id']][$merge['tag']] = $merge_setting_split[1];
					} else {
						$merge_array[$list['id']][$merge['tag']] = $merge_setting_value;
						if ($merge['type'] == 'birthday') {
							$birthday_fields[] = $merge_setting_value;
						}
					}
				}
			}
		}
		
		// Get OpenCart customers, and change customer groups
		$customer_id_sql = '';
		if ($start) $customer_id_sql .= " AND customer_id >= " . (int)$start;
		if ($end) $customer_id_sql .= " AND customer_id <= " . (int)$end;
		
		$customers = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE TRUE" . $customer_id_sql . " ORDER BY customer_group_id, store_id ASC")->rows;
		
		if (!empty($settings['subscribed_group'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['subscribed_group'] . " WHERE newsletter = 1" . $customer_id_sql);
		}
		if (!empty($settings['unsubscribed_group'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['unsubscribed_group'] . " WHERE newsletter = 0" . $customer_id_sql);
		}
		
		// OpenCart to MailChimp
		$operations = array();
		$count = 0;
		
		foreach ($customers as $customer) {
			if (!$customer['newsletter']) continue;
			
			$address = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id'])->row;
			
			$customer_group = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = " . (int)$customer['customer_group_id'])->row;
			$customer['customer_group_name'] = (!empty($customer_group['name'])) ? $customer_group['name'] : '';
			
			$this->config->set($this->name . '_testing_mode', 0);
			$listid = $this->determineList($customer, $address);
			$this->config->set($this->name . '_testing_mode', $settings['testing_mode']);
			
			$formatted_customer = array('email_address' => $customer['email'], 'status' => 'subscribed', 'merge_fields' => array());
			
			/*
			$formatted_customer['marketing_permissions'] = array(array(
				'marketing_permission_id'	=> '36a9e0c2c9',
				'enabled'					=> true,
			));
			*/
			
			foreach ($merge_array as $merge_listid => $merges) {
				if ($merge_listid != $listid) continue;
				foreach ($merges as $merge_tag => $opencart_field) {
					if ($opencart_field == 'address_id') {
						$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
						if ($address_query->num_rows) {
							$address = $address_query->row;
							if (!empty($address['country_id'])) {
								$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$address['country_id']);
								$address['iso_code_2'] = (!empty($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
							}
							if (!empty($address['zone_id'])) {
								$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$address['zone_id']);
								$address['zone_name'] = (!empty($zone_query->row['name'])) ? html_entity_decode($zone_query->row['name'], ENT_QUOTES, 'UTF-8') : '(none)';
							}
							if (!empty($address['address_1']) && !empty($address['city']) && !empty($address['zone_name']) && !empty($address['postcode']) && !empty($address['iso_code_2'])) {
								$formatted_customer['merge_fields'][$merge_tag] = array(
									'addr1'		=> $address['address_1'],
									'addr2'		=> $address['address_2'],
									'city'		=> $address['city'],
									'state'		=> $address['zone_name'],
									'zip'		=> $address['postcode'],
									'country'	=> $address['iso_code_2'],
								);
							}
						}
					} elseif ($opencart_field == 'telephone') {
						$telephone = preg_replace('/[^0-9]/', '', $customer[$opencart_field]);
						if ($telephone) {
							$formatted_customer['merge_fields'][$merge_tag] = substr($telephone, 0, 3) . '-' . substr($telephone, 3, 3) . '-' . substr($telephone, 6);
						}
					} elseif ($opencart_field == '00000' || $opencart_field == '(none)') {
						$formatted_customer['merge_fields'][$merge_tag] = $opencart_field;
					} elseif (isset($customer[$opencart_field])) {
						$formatted_customer['merge_fields'][$merge_tag] = $customer[$opencart_field];
					} else {
						$field_split = explode(':', $opencart_field);
						if ($field_split[0] == 'custom_field') {
							$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
							if ($address_query->num_rows && !empty($address_query->row['custom_field'])) {
								$address_custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($address_query->row['custom_field']) : json_decode($address_query->row['custom_field'], true);
							} else {
								$address_custom_fields = array();
							}
							if (!empty($customer['custom_field'])) {
								$custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($customer['custom_field']) : json_decode($customer['custom_field'], true);
							} else {
								$custom_fields = array();
							}
							$custom_fields += $address_custom_fields;
							
							if (isset($custom_fields[$field_split[1]])) {
								$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_id = " . (int)$field_split[1] . " AND custom_field_value_id = " . (int)$custom_fields[$field_split[1]]);
								if ($custom_field_value_query->num_rows) {
									$formatted_customer['merge_fields'][$merge_tag] = $custom_field_value_query->row['name'];
								} else {
									$formatted_customer['merge_fields'][$merge_tag] = $custom_fields[$field_split[1]];
								}
								if (in_array($opencart_field, $birthday_fields)) {
									$explode = explode('-', $formatted_customer['merge_fields'][$merge_tag]);
									if (isset($explode[1]) && isset($explode[2])) {
										$formatted_customer['merge_fields'][$merge_tag] = $explode[1] . '/' . $explode[2];
									} else {
										unset($formatted_customer['merge_fields'][$merge_tag]);
									}
								}
							}
							if (empty($formatted_customer['merge_fields'][$merge_tag])) {
								$formatted_customer['merge_fields'][$merge_tag] = '(none)';
							}
						} else {
							if ($field_split[0] == 'address') {
								$database_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
								if (!empty($database_query->row['country_id'])) {
									$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$database_query->row['country_id']);
									$database_query->row['country_name'] = (!empty($country_query->row['name'])) ? $country_query->row['name'] : '';
								}
								if (!empty($database_query->row['zone_id'])) {
									$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$database_query->row['zone_id']);
									$database_query->row['zone_name'] = (!empty($zone_query->row['name'])) ? $zone_query->row['name'] : '';
								}
							} else {
								$database_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $field_split[0] . " WHERE customer_id = " . (int)$customer['customer_id']);
							}
							$formatted_customer['merge_fields'][$merge_tag] = (isset($field_split[1]) && isset($database_query->row[$field_split[1]])) ? $database_query->row[$field_split[1]] : '(none)';
						}
					}
				}
			}
			
			if (!empty($customer['language_id'])) {
				$language_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = " . (int)$customer['language_id']);
				if ($language_query->num_rows) {
					$formatted_customer['merge_fields']['MC_LANGUAGE'] = $language_query->row['code'];
				}
			}
			
			foreach ($formatted_customer['merge_fields'] as &$merge) {
				if (is_array($merge)) {
					foreach ($merge as &$m) {
						$m = html_entity_decode($m, ENT_QUOTES, 'UTF-8');
					}
				} else {
					$merge = html_entity_decode($merge, ENT_QUOTES, 'UTF-8');
				}
			}
			
			//if (empty($formatted_customer['merge_fields'])) unset($formatted_customer['merge_fields']);
			
			$operations[] = array(
				'method'	=> 'PUT',
				'path'		=> 'lists/' . $listid . '/members/' . md5(strtolower($formatted_customer['email_address'])),
				'body'		=> json_encode($formatted_customer),
			);
			
			$count += 1;
		}
		
		if (empty($operations)) {
			return 'No eligible customers';
		}
		
		$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
		
		if (!empty($response['error'])) {
			$this->logMessage('SYNC ERROR: ' . $errors);
			return $response['error'];
		}
		
		$output .= $count . " customer(s) sent to MailChimp\n";
		$output .= "\nNote: Customers are processed by MailChimp as part of a batch request, and added or updated accordingly. You can check the status of the batch request here:\n\n";
		$output .= 'https://' . $data_center[1] . '.api.mailchimp.com/3.0/batches/' . $response['id'] . '?apikey=' . $settings['apikey'] . "\n\n";
		$output .= 'Products that already exist in MailChimp will result in 1 "errored_operation" each. This is normal behavior, and the product data will still be updated after it attempts to create the product in MailChimp.';
		
		$this->logMessage('SYNC SUCCESS: ' . str_replace("\n", ' ', $output));
		
		return $output;
	}
	
	//==============================================================================
	// sendCart()
	//==============================================================================
	public function sendCart($cart, $customer_id) {
		// Set up customer data
		if (strpos($customer_id, '@')) {
			$customer_info = array(
				'email'		=> $customer_id,
				'firstname'	=> (isset($this->session->data['guest']['firstname'])) ? $this->session->data['guest']['firstname'] : '',
				'lastname'	=> (isset($this->session->data['guest']['lastname'])) ? $this->session->data['guest']['lastname'] : '',
			);
		} else {
			$customer_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$customer_id)->row;
			if (empty($customer_info['email'])) {
				return;
			}
		}
		
		// Check cart contents
		$cart_products = $cart->getProducts();
		
		if (empty($cart_products)) {
			return;
		}
		
		// Generate cart URL
		$product_array = array();
		
		foreach ($cart_products as $product) {
			$product_to_add = array(
				'p' => $product['product_id'],
				'q' => $product['quantity'],
			);
			
			if (!empty($product['option'])) {
				foreach ($product['option'] as $option) {
					if ($option['type'] == 'checkbox') {
						$product_to_add['o'][$option['product_option_id']][] = $option['product_option_value_id'];
					} else {
						if (!empty($option['product_option_value_id'])) {
							$option_value = $option['product_option_value_id'];
						} elseif (version_compare(VERSION, '2.0', '<')) {
							$option_value = $option['option_value'];
						} else {
							$option_value = $option['value'];
						}
						$product_to_add['o'][$option['product_option_id']] = $option_value;
					}
				}
			}
			
			if (!empty($product['recurring'])) {
				$product_to_add['r'] = $product['recurring']['recurring_id'];
			}
			
			$product_array[] = $product_to_add;
		}
		
		$cart_url = '&' . http_build_query(array('c' => $product_array));
		
		// Set up curl data
		$cart_total = $cart->getTotal();
		$cart_subtotal = $cart->getSubTotal();
		
		$curl_data = array(
			'id'			=> $customer_info['email'],
			'customer'		=> array(
				'id'			=> $customer_info['email'],
				'email_address'	=> $customer_info['email'],
				'first_name'	=> $customer_info['firstname'],
				'last_name'		=> $customer_info['lastname'],
				'opt_in_status'	=> false,
			),
			'checkout_url'	=> HTTPS_SERVER . 'index.php?route=checkout/cart' . $cart_url,
			'currency_code'	=> strtoupper($_COOKIE['currency']),
			'order_total'	=> round($cart_total, 2),
			'tax_total'		=> round($cart_total - $cart_subtotal, 2),
		);
		
		// Set up cart data
		foreach ($cart_products as &$cart_product) {
			$options = array();
			foreach ($cart_product['option'] as $option) {
				if ($option['type'] == 'file') continue;
				$option_value = (version_compare(VERSION, '2.0', '<')) ? $option['option_value'] : $option['value'];
				$options[] = html_entity_decode($option['name'] . ': ' . $option_value, ENT_QUOTES, 'UTF-8');
			}
			$cart_product['options'] = $options;
		}
		
		// Set up line items
		$operations = array();
		$settings = $this->getSettings();
		
		$curl_data['lines'] = array();
		$mailchimp_store_id = $settings['store-' . $this->config->get('config_store_id') . '-list'] . '-' . $this->config->get('config_store_id');
		
		$this->buildLineItems($cart_products, $mailchimp_store_id, $operations, $curl_data['lines']);
		
		if (empty($curl_data['lines'])) {
			return;
		}
		
		// Set campaign_id if present
		$campaign_curl_data = array();
		
		if (isset($_COOKIE['mc_cid'])) {
			$campaign_curl_data = $curl_data;
			$campaign_curl_data['campaign_id'] = $_COOKIE['mc_cid'];
		}
		
		// Send cart data
		$operations[] = array(
			'method'	=> 'POST',
			'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/carts',
			'body'		=> json_encode($curl_data),
		);
		$operations[] = array(
			'method'	=> 'PATCH',
			'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/carts/' . $customer_info['email'],
			'body'		=> json_encode($curl_data),
		);
		
		if (!empty($campaign_curl_data)) {
			$operations[] = array(
				'method'	=> 'PATCH',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/carts/' . $customer_info['email'],
				'body'		=> json_encode($campaign_curl_data),
			);
		}
		
		$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
		
		if (!empty($response['error'])) {
			$this->logMessage('CART SEND ERROR: ' . $response['error']);
		}
	}
	
	//==============================================================================
	// deleteCart()
	//==============================================================================
	public function deleteCart($email) {
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		$mailchimp_store_id = $this->config->get($prefix .$this->name . '_store-' . $this->config->get('config_store_id') . '-list') . '-' . $this->config->get('config_store_id');
		
		$this->curlRequest('DELETE', 'ecommerce/stores/' . $mailchimp_store_id . '/carts/' . $email);
		
		/*
		$operations[] = array(
			'method'	=> 'DELETE',
			'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/carts/' . $email,
			'body'		=> array(),
		);
		$this->curlRequest('POST', 'batches', array('operations' => $operations));
		*/
	}
	
	//==============================================================================
	// buildLineItems()
	//==============================================================================
	private function buildLineItems($products, $mailchimp_store_id, &$operations, &$lines) {
		$settings = $this->getSettings();
		$config_url = ($this->config->get('config_url')) ? $this->config->get('config_url') : HTTP_CATALOG;
		
		foreach ($products as $product) {
			$product_info = $this->db->query("SELECT *, p.image AS image, pd.name AS name, m.name AS manufacturer FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = " . (int)$product['product_id'])->row;
			
			if (empty($product_info['product_id'])) {
				$product['description'] = '';
				$product['manufacturer'] = '';
				$product['image'] = (version_compare(VERSION, '2.0', '<')) ? 'no_image.jpg' : 'placeholder.png';
				$product['cart_quantity'] = 0;
				$product['inventory_quantity'] = 0;
			} else {
				$product['cart_quantity'] = (!empty($product['quantity'])) ? $product['quantity'] : 0;
				$product['inventory_quantity'] = $product_info['quantity'];
				$product = array_merge($product_info, $product);
				
				if (!empty($product['discount'])) {
					$product['price'] = $product['discount'];
				} elseif (!empty($product['special'])) {
					$product['price'] = $product['special'];
				}
				
				if ($settings['product_prices'] == 'taxed') {
					$product['price'] = $this->tax->calculate($product['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				}
				
				if ($settings['vendor_field'] == 'category') {
					$product_category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = (SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = " . (int)$product['product_id'] . " ORDER BY category_id ASC LIMIT 1)");
					if ($product_category_query->num_rows) {
						$product['manufacturer'] = $product_category_query->row['name'];
					}
				}
			}
			
			if (empty($product['name'])) continue;
			
			if (empty($product['product_id'])) $product['product_id'] = md5($product['name']);
			
			// Create product URL
			$product_url = (defined('HTTP_CATALOG')) ? HTTP_CATALOG : HTTP_SERVER;
			
			$seo_table = (version_compare(VERSION, '3.0', '<')) ? 'url_alias' : 'seo_url';
			$seo_url_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $seo_table . " WHERE `query` = 'product_id=" . (int)$product['product_id'] . "' ORDER BY keyword DESC");
			
			if ($seo_url_query->num_rows && !empty($seo_url_query->row['keyword'])) {
				$product_url .= $seo_url_query->row['keyword'];
			} else {
				$product_url .= 'index.php?route=product/product&product_id=' . $product['product_id'];
			}
			
			//$product['image'] = str_replace(array('.jpg', '.png'), array('-150x150.jpg', '-150x150.png'), $product['image']);
			
			// Set up curl data
			$product_curl_data = array(
				'id'			=> $product['product_id'],
				'title'			=> html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
				'url'			=> $product_url,
				'description'	=> html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
				'vendor'		=> html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8'),
				'image_url'		=> $config_url . 'image/' . str_replace(' ', '%20', $product['image']),
				'variants'		=> array(
					array(
						'id'					=> $product['product_id'],
						'title'					=> '',
						'sku'					=> html_entity_decode($product['model'], ENT_QUOTES, 'UTF-8'),
						'price'					=> $product['price'],
						'inventory_quantity'	=> (int)$product['inventory_quantity'],
					),
				),
			);
			
			// Add product operations
			$this->config->set($this->name . '_testing_mode', 0);
			
			$operations[] = array(
				'method'	=> 'POST',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/products',
				'body'		=> json_encode($product_curl_data),
			);
			$operations[] = array(
				'method'	=> 'PATCH',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/products/' . $product['product_id'],
				'body'		=> json_encode($product_curl_data),
			);
			
			// Add variant operations
			if (!empty($product['options'])) {
				$product_variant = implode(', ', $product['options']);
				$product_variant_id = md5($product_variant);
				
				$variant_curl_data = array(
					'id'					=> $product_variant_id,
					'title'					=> $product_variant,
					'url'					=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'price'					=> $product['price'],
					'inventory_quantity'	=> (int)$product['inventory_quantity'],
					'image_url'				=> $config_url . 'image/' . $product['image'],
				);
				
				$operations[] = array(
					'method'	=> 'PUT',
					'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/products/' . $product['product_id'] . '/variants/' . $product_variant_id,
					'body'		=> json_encode($variant_curl_data),
				);
			} else {
				$product_variant = $product['name'];
				$product_variant_id = $product['product_id'];
			}
			
			$this->config->set($this->name . '_testing_mode', $settings['testing_mode']);
			
			$lines[] = array(
				'id'					=> 'line_' . (count($lines) + 1),
				'product_id'			=> $product['product_id'],
				'product_variant_id'	=> $product_variant_id,
				'quantity'				=> (int)$product['cart_quantity'],
				'price'					=> $product['price'],
			);
		}
	}
	
	//==============================================================================
	// syncProducts()
	//==============================================================================
	public function syncProducts($start, $end) {
		$settings = $this->getSettings();
		$customer_group_id = $this->config->get('config_customer_group_id');
		
		// Get products
		$sql = "SELECT *, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = " . (int)$customer_group_id . " AND pd2.quantity = 1 AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = " . (int)$customer_group_id . " AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p WHERE p.status = 1";
		if ($start) $sql .= " AND p.product_id >= " . (int)$start;
		if ($end) $sql .= " AND p.product_id <= " . (int)$end;
		$products = $this->db->query($sql)->rows;
		
		// Get stores
		$stores = $this->db->query("SELECT * FROM " . DB_PREFIX . "store")->rows;
		$stores[] = array('store_id' => 0);
		
		foreach ($stores as $store) {
			// Set is_syncing flag to true
			$mailchimp_store_id = $settings['store-' . $store['store_id'] . '-list'] . '-' . $store['store_id'];
			$store_response = $this->curlRequest('PATCH', 'ecommerce/stores/' . $mailchimp_store_id, array('is_syncing' => true));
			
			// Set up operations
			$operations = array();
			$temp_lines = array();
			
			$this->buildLineItems($products, $mailchimp_store_id, $operations, $temp_lines);
			
			// Send operations
			$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
			
			if (!empty($response['error'])) {
				$this->logMessage('PRODUCT SYNC ERROR: ' . $response['error']);
			}
			
			// Set is_syncing flag to false
			$store_response = $this->curlRequest('PATCH', 'ecommerce/stores/' . $mailchimp_store_id, array('is_syncing' => false));
		}
		
		// Return output
		$data_center = explode('-', $settings['apikey']);
		
		$output = count($products) . " products sent to MailChimp\n";
		$output .= "\nNote: Products are processed by MailChimp as part of a batch request, and added or updated accordingly. You can check the status of the batch request here:\n\n";
		$output .= 'https://' . $data_center[1] . '.api.mailchimp.com/3.0/batches/' . $response['id'] . '?apikey=' . $settings['apikey'] . "\n\n";
		$output .= 'Products that already exist in MailChimp will result in 1 "errored_operation" each. This is normal behavior, and the product data will still be updated after it attempts to create the product in MailChimp.';
		
		$this->logMessage('PRODUCT SYNC SUCCESS: ' . $output);
		
		return $output;
	}
	
	//==============================================================================
	// sendOrder()
	//==============================================================================
	public function sendOrder($order_info, $ordertype) {
		$operations = $this->formatOrder($order_info, $ordertype);
		if ($operations) {
			$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
		}
	}
	
	//==============================================================================
	// deleteOrder()
	//==============================================================================
	public function deleteOrder($order_info) {
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		
		$delete_statuses = explode(';', $this->config->get($prefix . $this->name . '_deletestatus'));
		if (!in_array($order_info['order_status_id'], $delete_statuses)) return;
		
		$mailchimp_store_id = $this->config->get($prefix . $this->name . '_store-' . $order_info['store_id'] . '-list') . '-' . $order_info['store_id'];
		$response = $this->curlRequest('DELETE', 'ecommerce/stores/' . $mailchimp_store_id . '/orders/' . $order_info['order_id']);
	}
	
	//==============================================================================
	// formatOrder()
	//==============================================================================
	public function formatOrder($order_info, $ordertype) {
		if ($ordertype == 'newsletter' && empty($_COOKIE['mc_cid'])) {
			return array();
		}
		
		$operations = array();
		$settings = $this->getSettings();
		$config_url = ($this->config->get('config_url')) ? $this->config->get('config_url') : HTTP_CATALOG;
		$mailchimp_store_id = $settings['store-' . $order_info['store_id'] . '-list'] . '-' . $order_info['store_id'];
		
		// Check order status
		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = " . (int)$order_info['order_status_id']);
		$order_status = ($order_status_query->num_rows) ? $order_status_query->row['name'] : '';
		
		if (!in_array($order_info['order_status_id'], explode(';', $settings['orderstatus']))) {
			$this->logMessage('Order ID ' . $order_info['order_id'] . ' was not sent to MailChimp because its order status is ' . $order_status);
			return array();
		}
		
		$order_status = 'pending';
		if ($order_info['order_status_id']) {
			if ($order_info['order_status_id'] == $settings['orderstatus_refunded'])	$order_status = 'refunded';
			if ($order_info['order_status_id'] == $settings['orderstatus_cancelled'])	$order_status = 'cancelled';
			if ($order_info['order_status_id'] == $settings['orderstatus_shipped'])		$order_status = 'shipped';
			if ($order_info['order_status_id'] == $settings['orderstatus_paid'])		$order_status = 'paid';
		}
		
		// Set up shipping and tax costs
		$order_info['discount_total'] = 0;
		$order_info['tax_total'] = 0;
		$order_info['shipping_total'] = 0;
		
		$order_totals = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_info['order_id'])->rows;
		foreach ($order_totals as $order_total) {
			if ($order_total['code'] == 'shipping') {
				$order_info['shipping_total'] = $order_total['value'];
			} elseif (in_array($order_total['code'], array('tax', 'taxcloud_integration', 'taxjar_integration'))) {
				$order_info['tax_total'] = $order_total['value'];
			} elseif ($order_total['code'] != 'sub_total' && $order_total['code'] != 'total') {
				$order_info['discount_total'] -= $order_total['value'];
			}
		}
		
		$shipping_country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$order_info['shipping_country_id'])->row;
		$shipping_zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$order_info['shipping_zone_id'])->row;
		
		$payment_country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$order_info['payment_country_id'])->row;
		$payment_zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$order_info['payment_zone_id'])->row;
		
		// Send customer data
		if ($order_info['customer_id']) {
			$past_orders_query = $this->db->query("SELECT COUNT(*) AS orders, SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0 AND customer_id = " . (int)$order_info['customer_id']);
		} else {
			$past_orders_query = $this->db->query("SELECT COUNT(*) AS orders, SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0 AND email = '" . $this->db->escape($order_info['email']) . "'");
		}
		$past_orders_count = (int)$past_orders_query->row['orders'];
		$past_total_spent = number_format((float)$past_orders_query->row['total'], 2, '.', '');
		
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$order_info['customer_id']);
		$customer_email = ($customer_query->num_rows) ? $customer_query->row['email'] : $order_info['email'];
		
		$customer_data = array(
			'id'				=> $customer_email,
			'email_address'		=> $customer_email,
			'opt_in_status'		=> false,
			'company'			=> $order_info['shipping_company'],
			'first_name'		=> $order_info['firstname'],
			'last_name'			=> $order_info['lastname'],
			'orders_count'		=> $past_orders_count,
			'total_spent'		=> $past_total_spent,
			'address'			=> array(
				'address1'			=> $order_info['shipping_address_1'],
				'address2'			=> $order_info['shipping_address_2'],
				'city'				=> $order_info['shipping_city'],
				'province'			=> $order_info['shipping_zone'],
				'province_code'		=> (isset($shipping_zone['code'])) ? $shipping_zone['code'] : '',
				'postal_code'		=> $order_info['shipping_postcode'],
				'country'			=> $order_info['shipping_country'],
				'country_code'		=> (isset($shipping_country['iso_code_2'])) ? $shipping_country['iso_code_2'] : '',
			),
		);
		
		$operations[] = array(
			'method'	=> 'PUT',
			'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/customers/' . $customer_email,
			'body'		=> json_encode($customer_data),
		);
		
		// Set up curl data
		$curl_data = array(
			'id'				=> $order_info['order_id'],
			'customer'			=> array('id' => $customer_email),
			'landing_site'		=> $config_url,
			'financial_status'	=> $order_status,
			'fulfillment_status'=> $order_status,
			'currency_code'		=> strtoupper($order_info[isset($order_info['currency']) ? 'currency' : 'currency_code']),
			'order_total'		=> $order_info['total'],
			'discount_total'	=> $order_info['discount_total'],
			'tax_total'			=> $order_info['tax_total'],
			'shipping_total'	=> $order_info['shipping_total'],
			'processed_at_foreign' => $order_info['date_added'],
			'updated_at_foreign' => date('Y-m-d H:i'),
			'shipping_address'	=> array(
				'name'				=> $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'],
				'address1'			=> $order_info['shipping_address_1'],
				'address2'			=> $order_info['shipping_address_2'],
				'city'				=> $order_info['shipping_city'],
				'province'			=> $order_info['shipping_zone'],
				'province_code'		=> (isset($shipping_zone['code'])) ? $shipping_zone['code'] : '',
				'postal_code'		=> $order_info['shipping_postcode'],
				'country'			=> $order_info['shipping_country'],
				'country_code'		=> (isset($shipping_country['iso_code_2'])) ? $shipping_country['iso_code_2'] : '',
				'phone'				=> $order_info['telephone'],
				'company'			=> $order_info['shipping_company'],
			),
			'billing_address'	=> array(
				'name'				=> $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
				'address1'			=> $order_info['payment_address_1'],
				'address2'			=> $order_info['payment_address_2'],
				'city'				=> $order_info['payment_city'],
				'province'			=> $order_info['payment_zone'],
				'province_code'		=> (isset($payment_zone['code'])) ? $payment_zone['code'] : '',
				'postal_code'		=> $order_info['payment_postcode'],
				'country'			=> $order_info['payment_country'],
				'country_code'		=> (isset($payment_country['iso_code_2'])) ? $payment_country['iso_code_2'] : '',
				'phone'				=> $order_info['telephone'],
				'company'			=> $order_info['payment_company'],
			),
		);
		
		// Set up line items
		$order_products = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = " . (int)$order_info['order_id'])->rows;

		foreach ($order_products as &$order_product) {
			$options = array();
			$order_options = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = " . (int)$order_info['order_id'] . " AND order_product_id = " . (int)$order_product['order_product_id'])->rows;
			foreach ($order_options as $option) {
				if ($option['type'] == 'file') continue;
				$options[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
			}
			$order_product['options'] = $options;
		}
		
		$curl_data['lines'] = array();
		$this->buildLineItems($order_products, $mailchimp_store_id, $operations, $curl_data['lines']);
		
		if (empty($curl_data['lines'])) {
			$this->logMessage('No valid products on order #' . $order_info['order_id']);
			return array();
		}
		
		// Set campaign_id if present
		$campaign_curl_data = array();
		
		if (isset($_COOKIE['mc_cid'])) {
			$campaign_curl_data = $curl_data;
			$campaign_curl_data['campaign_id'] = $_COOKIE['mc_cid'];
		}
		
		// Send order data
		$operations[] = array(
			'method'	=> 'POST',
			'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/orders',
			'body'		=> json_encode($curl_data),
		);
		$operations[] = array(
			'method'	=> 'PATCH',
			'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/orders/' . $order_info['order_id'],
			'body'		=> json_encode($curl_data),
		);
		if (!empty($campaign_curl_data)) {
			$operations[] = array(
				'method'	=> 'PATCH',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/orders/' . $order_info['order_id'],
				'body'		=> json_encode($campaign_curl_data),
			);
		}
		
		return $operations;
	}
	
	//==============================================================================
	// syncOrders()
	//==============================================================================
	public function syncOrders($start, $end) {
		$settings = $this->getSettings();
		$data_center = explode('-', $settings['apikey']);
		
		// Delete campaign_id cookie if the syncing admin has it present
		setcookie('mc_cid', '', -1, '/');
		
		// Set is_syncing flag to true
		$stores = $this->db->query("SELECT * FROM " . DB_PREFIX . "store")->rows;
		$stores[] = array('store_id' => 0);
		
		foreach ($stores as $store) {
			$mailchimp_store_id = $settings['store-' . $store['store_id'] . '-list'] . '-' . $store['store_id'];
			$store_response = $this->curlRequest('PATCH', 'ecommerce/stores/' . $mailchimp_store_id, array('is_syncing' => true));
		}
		
		// Send OpenCart orders
		$sql = "SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0";
		if ($start) $sql .= " AND order_id >= " . (int)$start;
		if ($end) $sql .= " AND order_id <= " . (int)$end;
		$opencart_orders = $this->db->query($sql)->rows;
		
		$count = 0;
		$operations = array();
		
		foreach ($opencart_orders as $order_info) {
			$new_operations = $this->formatOrder($order_info, 'all');
			if ($new_operations) {
				$operations = array_merge($operations, $new_operations);
				$count += 1;
			}
		}
		
		if (empty($operations)) {
			return 'No eligible orders';
		}
		
		$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
		
		// Set is_syncing flag to false
		foreach ($stores as $store) {
			$mailchimp_store_id = $settings['store-' . $store['store_id'] . '-list'] . '-' . $store['store_id'];
			$store_response = $this->curlRequest('PATCH', 'ecommerce/stores/' . $mailchimp_store_id, array('is_syncing' => false));
		}
		
		// Return output
		$output = $count . " orders(s) sent to MailChimp\n";
		$output .= "\nNote: Orders are processed by MailChimp as part of a batch request, and added or updated accordingly. You can check the status of the batch request here:\n\n";
		$output .= 'https://' . $data_center[1] . '.api.mailchimp.com/3.0/batches/' . $response['id'] . '?apikey=' . $settings['apikey'] . "\n\n";
		$output .= 'Orders and products that already exist in MailChimp will result in 1 "errored_operation" each. This is normal behavior, and the order/product data will still be updated after it attempts to create the order/product in MailChimp.';
		
		$this->logMessage('SYNC SUCCESS: ' . $output);
		
		return $output;
	}
	
	//==============================================================================
	// webhook()
	//==============================================================================
	public function webhook($type, $data) {
		$settings = $this->getSettings();
		
		if (empty($settings['status'])) {
			$this->logMessage('WEBHOOK ERROR: Extension is disabled in the admin panel');
			return;
		}
		
		if (empty($settings['webhooks'])) {
			$this->logMessage('WEBHOOK ERROR: No webhooks are enabled in the admin panel');
			return;
		}
		
		$webhooks = explode(';', $settings['webhooks']);
		
		$listid = $settings['listid'];
		$customer_group_id = $this->config->get('config_customer_group_id');
		/*
		foreach ($settings as $key => $value) {
			if (strpos($value, '_list') && $value == $data['list_id']) {
				if (customer group rule exists) {
					$listid = $data['list_id'];
					$customer_group_id = customer group value
					break;
				}
			}
		}
		*/
		$data['customer_group_id'] = $customer_group_id;
		
		$success = false;
		
		if ($type == 'subscribe' && in_array('subscribe', $webhooks)) {
			
			if ($settings['autocreate'] && in_array($data['list_id'], explode(';', $settings['autocreate_lists']))) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "'");
				if (!$query->num_rows) {
					$this->createCustomer($data);
				}
			}
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE email = '" . $this->db->escape($data['email']) . "'");
			if (!empty($settings['subscribed_group'])) $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['subscribed_group'] . " WHERE email = '" . $this->db->escape($data['email']) . "'");
			$success = true;
			
		} elseif (($type == 'unsubscribe' && in_array('unsubscribe', $webhooks)) || ($type == 'cleaned' && in_array('cleaned', $webhooks))) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 0 WHERE email = '" . $this->db->escape($data['email']) . "'");
			//$this->db->query("DELETE FROM " . DB_PREFIX . "journal2_newsletter WHERE email = '" . $this->db->escape($data['email']) . "'");
			if (!empty($settings['unsubscribed_group'])) $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['unsubscribed_group'] . " WHERE email = '" . $this->db->escape($data['email']) . "'");
			$success = true;
			
		} elseif ($type == 'profile' && in_array('profile', $webhooks)) {
			
			$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "'")->row;
			if (empty($customer)) return;
			
			foreach ($data['merges'] as $merge_tag => $merge_value) {
				if ($merge_tag == 'EMAIL' || empty($settings[$data['list_id'] . '_' . $merge_tag])) continue;
				$merge_mapping = $settings[$data['list_id'] . '_' . $merge_tag];
				if (strpos($merge_mapping, ':firstname')) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
					$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($merge_value) . "' WHERE address_id = " . (int)$customer['address_id']);
				} elseif (strpos($merge_mapping, ':lastname')) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET lastname = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
					$this->db->query("UPDATE " . DB_PREFIX . "address SET lastname = '" . $this->db->escape($merge_value) . "' WHERE address_id = " . (int)$customer['address_id']);
				} elseif (strpos($merge_mapping, ':telephone')) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET telephone = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
				} elseif (strpos($merge_mapping, ':address_id')) {
					$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($merge_value['country']) . "'");
					$country_id = ($country_query->num_rows) ? (int)$country_query->row['country_id'] : 0;
					
					$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE (`name` = '" . $this->db->escape($merge_value['state']) . "' OR `code` = '" . $this->db->escape($merge_value['state']) . "') AND country_id = " . (int)$country_id);
					$zone_id = ($zone_query->num_rows) ? (int)$zone_query->row['zone_id'] : 0;
					
					$this->db->query("
						UPDATE " . DB_PREFIX . "address SET
						address_1 = '" . $this->db->escape($merge_value['addr1']) . "',
						address_2 = '" . $this->db->escape($merge_value['addr2']) . "',
						city = '" . $this->db->escape($merge_value['city']) . "',
						zone_id = " . $zone_id . ",
						postcode = '" . $this->db->escape($merge_value['zip']) . "',
						country_id = " . $country_id . "
						WHERE address_id = " . (int)$customer['address_id'] . "
					");
				} else {
					$merge_mapping_split = explode(':', $merge_mapping);
					if ($merge_mapping_split[0] == 'custom_field') {
						$custom_field = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field WHERE custom_field_id = " . (int)$merge_mapping_split[1])->row;
						if (!empty($custom_field) && $custom_field['type'] != 'checkbox' && $custom_field['type'] != 'radio' && $custom_field['type'] != 'select') {
							if ($custom_field['location'] == 'account') {
								$cf_array = (version_compare(VERSION, '2.1', '<')) ? unserialize($customer['custom_field']) : json_decode($customer['custom_field'], true);
								$cf_array[$merge_mapping_split[1]] = $merge_value;
								$cf_array = (version_compare(VERSION, '2.1', '<')) ? serialize($cf_array) : json_encode($cf_array);
								$this->db->query("UPDATE " . DB_PREFIX . "customer SET custom_field = '" . $this->db->escape($cf_array) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
							} elseif ($custom_field['location'] == 'address') {
								$address = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id'])->row;
								$cf_array = (version_compare(VERSION, '2.1', '<')) ? unserialize($address['custom_field']) : json_decode($address['custom_field'], true);
								$cf_array[$merge_mapping_split[1]] = $merge_value;
								$cf_array = (version_compare(VERSION, '2.1', '<')) ? serialize($cf_array) : json_encode($cf_array);
								$this->db->query("UPDATE " . DB_PREFIX . "address SET custom_field = '" . $this->db->escape($cf_array) . "' WHERE address_id = " . (int)$address['address_id']);
							}
						}
					} elseif ($merge_mapping_split[1] != 'customer_id') {
						$this->db->query("UPDATE `" . DB_PREFIX . $merge_mapping_split[0] . "` SET `" . $merge_mapping_split[1] . "` = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
					}
				}
			}
			
			$success = true;
			
		} elseif ($type == 'upemail' && in_array('profile', $webhooks)) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET email = '" . $this->db->escape($data['new_email']) . "' WHERE email = '" . $this->db->escape($data['old_email']) . "'");
			$success = true;
			
		}
		
		if ($success) {
			$this->logMessage('WEBHOOK SUCCESS: ' . $type . ' ' . $data['email'] . ' (List ID ' . $data['list_id'] . ')');
		}
	}
	
	//==============================================================================
	// createCustomer()
	//==============================================================================
	private function createCustomer($data) {
		$settings = $this->getSettings();
		
		$merge_mapping = array();
		foreach ($settings as $key => $value) {
			if (strpos($key, $data['list_id']) !== 0 || is_array($value)) {
				continue;
			}
			if (strpos($value, ':firstname')) {
				$merge_mapping['firstname'] = str_replace($data['list_id'] . '_', '', $key);
			} elseif (strpos($value, ':lastname')) {
				$merge_mapping['lastname'] = str_replace($data['list_id'] . '_', '', $key);
			} elseif (strpos($value, ':telephone')) {
				$merge_mapping['telephone'] = str_replace($data['list_id'] . '_', '', $key);
			} elseif (strpos($value, ':address_id')) {
				$merge_mapping['address'] = str_replace($data['list_id'] . '_', '', $key);
			}
		}
		
		$customer = array(
			'status'			=> (int)($settings['autocreate'] == 2),
			'customer_group_id'	=> (!empty($data['customer_group_id']) ? $data['customer_group_id'] : $this->config->get('config_customer_group_id')),
			'email'				=> (isset($data['email_address'])) ? $data['email_address'] : $data['email'],
			'firstname'			=> (isset($merge_mapping['firstname']) &&!empty($data['merges'][$merge_mapping['firstname']]) ? $data['merges'][$merge_mapping['firstname']] : ''),
			'lastname'			=> (isset($merge_mapping['lastname']) &&!empty($data['merges'][$merge_mapping['lastname']]) ? $data['merges'][$merge_mapping['lastname']] : ''),
			'telephone'			=> (isset($merge_mapping['telephone']) && !empty($data['merges'][$merge_mapping['telephone']]) ? $data['merges'][$merge_mapping['telephone']] : ''),
			'address'			=> (isset($merge_mapping['address']) &&!empty($data['merges'][$merge_mapping['address']]) ? $data['merges'][$merge_mapping['address']] : array()),
			'ip'				=> $data['ip_opt'],
			'password'			=> rand(),
		);
		
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "customer SET
			status = " . (int)$customer['status'] . ",
			" . (version_compare(VERSION, '3.0', '<') ? "approved = 1," : "") . "
			newsletter = 1,
			customer_group_id = " . (int)$customer['customer_group_id'] . ",
			email = '" . $this->db->escape($customer['email']) . "',
			firstname = '" . $this->db->escape($customer['firstname']) . "',
			lastname = '" . $this->db->escape($customer['lastname']) . "',
			telephone = '" . $this->db->escape($customer['telephone']) . "',
			ip = '" . $this->db->escape($customer['ip']) . "',
			password = '" . $this->db->escape(md5($customer['password'])) . "',
			date_added = NOW()
		");
		
		$customer_id = $this->db->getLastId();
		
		if (version_compare(VERSION, '2.3', '>=')) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = " . (int)$this->config->get('config_language_id') . " WHERE customer_id = " . (int)$customer_id);
		}
		
		if (!isset($customer['address']['addr1']))		$customer['address']['addr1']	= '';
		if (!isset($customer['address']['addr2']))		$customer['address']['addr2']	= '';
		if (!isset($customer['address']['city']))		$customer['address']['city']	= '';
		if (!isset($customer['address']['zip']))		$customer['address']['zip']	= '';
		if (!isset($customer['address']['country']))	$customer['address']['country']	= '';
		if (!isset($customer['address']['state']))		$customer['address']['state'] = '';
		
		$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($customer['address']['country']) . "'");
		$country_id = ($country_query->num_rows) ? $country_query->row['country_id'] : 0;
		$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE (`name` = '" . $this->db->escape($customer['address']['state']) . "' OR `code` = '" . $this->db->escape($customer['address']['state']) . "') AND country_id = " . (int)$country_id);
		$zone_id = ($zone_query->num_rows) ? $zone_query->row['zone_id'] : 0;
		
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "address SET
			customer_id = " . (int)$customer_id . ",
			firstname = '" . $this->db->escape($customer['firstname']) . "',
			lastname = '" . $this->db->escape($customer['lastname']) . "',
			address_1 = '" . $this->db->escape($customer['address']['addr1']) . "',
			address_2 = '" . $this->db->escape($customer['address']['addr2']) . "',
			city = '" . $this->db->escape($customer['address']['city']) . "',
			zone_id = " . (int)$zone_id . ",
			postcode = '" . $this->db->escape($customer['address']['zip']) . "',
			country_id = " . (int)$country_id . "
		");
		
		$address_id = $this->db->getLastId();
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = " . (int)$address_id . " WHERE customer_id = " . (int)$customer_id);
		
		$language = (!empty($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		$email_subject = str_replace('[store]', $this->config->get('config_name'), $settings['emailtext_subject_' . $language]);
		$email_body = html_entity_decode(str_replace(array('[store]', '[email]', '[password]', '[firstname]', '[lastname]'), array($this->config->get('config_name'), $customer['email'], $customer['password'], $customer['firstname'], $customer['lastname']), $settings['emailtext_body_' . $language]), ENT_QUOTES, 'UTF-8');
		
		if ($settings['email_password'] && $customer['status']) {
			if (version_compare(VERSION, '2.0', '<')) {
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = html_entity_decode($this->config->get('config_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
			} elseif (version_compare(VERSION, '2.0.2', '<')) {
				$mail = new Mail($this->config->get('config_mail'));
			} else {
				if (version_compare(VERSION, '3.0', '<')) {
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
				} else {
					$mail = new Mail($this->config->get('config_mail_engine'));
				}
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			}
			
			$mail->setSubject($email_subject);
			$mail->setHtml($email_body);
			$mail->setSender(str_replace(array(',', '&'), array('', 'and'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setTo($customer['email']);
			$mail->send();
		}
		
		$this->logMessage('CUSTOMER CREATED: ' . $customer['firstname'] . ' ' . $customer['lastname'] . ' (' . $customer['email'] . ')');
	}
	
	//==============================================================================
	// Private functions
	//==============================================================================
	private function getSettings() {
		if (!empty($this->settings)) {
			return $this->settings;
		}
		
		$code = (version_compare(VERSION, '3.0', '<')) ? $this->name : $this->type . '_' . $this->name;
		
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($code) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			$split_key = preg_split('/_(\d+)_?/', str_replace($code . '_', '', $setting['key']), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			
				if (count($split_key) == 1)	$settings[$split_key[0]] = $value;
			elseif (count($split_key) == 2)	$settings[$split_key[0]][$split_key[1]] = $value;
			elseif (count($split_key) == 3)	$settings[$split_key[0]][$split_key[1]][$split_key[2]] = $value;
			elseif (count($split_key) == 4)	$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]] = $value;
			else 							$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]][$split_key[4]] = $value;
		}
		
		$this->settings = $settings;
		return $settings;
	}
	
	private function logMessage($message) {
		$settings = $this->getSettings();
		if ($settings['testing_mode']) {
			file_put_contents(DIR_LOGS . $this->name . '.messages', print_r($message, true) . "\n\n", FILE_APPEND|LOCK_EX);
		}
	}
	
	private function commaMerge(&$rule) {
		$merged_rule = array();
		foreach ($rule as $comparison => $values) {
			$merged_rule[$comparison] = array();
			foreach ($values as $value) {
				$merged_rule[$comparison] = array_merge($merged_rule[$comparison], array_map('trim', explode(',', strtolower($value))));
			}
		}
		$rule = $merged_rule;
	}
	
	private function ruleViolation($rules, $rule, $value) {
		$violation = false;
		$function = (is_array($value)) ? 'array_intersect' : 'in_array';
		
		if (isset($rules[$rule]['after']) && strtotime($value) < min(array_map('strtotime', $rules[$rule]['after']))) {
			$violation = true;
			$comparison = 'after';
		}
		if (isset($rules[$rule]['before']) && strtotime($value) > max(array_map('strtotime', $rules[$rule]['before']))) {
			$violation = true;
			$comparison = 'before';
		}
		if (isset($rules[$rule]['is']) && !$function($value, $rules[$rule]['is'])) {
			$violation = true;
			$comparison = 'is';
		}
		if (isset($rules[$rule]['not']) && $function($value, $rules[$rule]['not'])) {
			$violation = true;
			$comparison = 'not';
		}
		
		return $violation;
	}
	
	private function inRange($value, $range_list, $charge_type = '', $list) {
		$in_range = false;
		
		foreach ($range_list as $range) {
			if ($range == '') continue;
			
			$range = (strpos($range, '::')) ? explode('::', $range) : explode('-', $range);
			
			if (strpos($charge_type, 'distance') === 0) {
				if (empty($range[1])) {
					array_unshift($range, 0);
				}
				if ($value >= (float)$range[0] && $value <= (float)$range[1]) {
					$in_range = true;
				}
			} elseif (strpos($charge_type, 'postcode') === 0) {
				$postcode = preg_replace('/[^A-Z0-9]/', '', strtoupper($value));
				$from = preg_replace('/[^A-Z0-9]/', '', strtoupper($range[0]));
				$to = (isset($range[1])) ? preg_replace('/[^A-Z0-9]/', '', strtoupper($range[1])) : $from;
				
				if (strlen($from) < 3 && !preg_match('/[0-9]/', $from)) $from .= '1';
				if (strlen($to) < 3 && !preg_match('/[0-9]/', $to)) $to .= '99';
				
				if (strlen($from) < strlen($postcode)) $from = str_pad($from, max(strlen($postcode), strlen($from) + 3), ' ');
				if (strlen($to) < strlen($postcode)) $to = str_pad($to, max(strlen($postcode), strlen($to) + 3), preg_match('/[A-Z]/', $postcode) ? 'Z' : '9');
				
				$postcode = substr_replace(substr_replace($postcode, ' ', -3, 0), ' ', -2, 0);
				$from = substr_replace(substr_replace($from, ' ', -3, 0), ' ', -2, 0);
				$to = substr_replace(substr_replace($to, ' ', -3, 0), ' ', -2, 0);
				
				if (strnatcasecmp($postcode, $from) >= 0 && strnatcasecmp($postcode, $to) <= 0) {
					$in_range = true;
				}
			} else {
				if ($charge_type != 'attribute' && $charge_type != 'option' && strpos($charge_type, 'customer_data') !== 0 && $charge_type != 'other product data' && !isset($range[1])) {
					$range[1] = 999999999;
				}
				
				if ((count($range) > 1 && $value >= $range[0] && $value <= $range[1]) || (count($range) == 1 && $value == $range[0])) {
					$in_range = true;
				}
			}
		}
		
		return $in_range;
	}
	
	//==============================================================================
	// curlRequest()
	//==============================================================================
	private function curlRequest($request, $api, $data = array()) {
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		
		$apikey = $this->config->get($prefix . $this->name . '_apikey');
		$data_center = explode('-', $apikey);
		$url = 'https://' . (isset($data_center[1]) ? $data_center[1] : 'us1') . '.api.mailchimp.com/3.0/';
		
		if ($request == 'GET') {
			$curl = curl_init($url . $api . '?' . http_build_query($data));
		} else {
			$curl = curl_init($url . $api);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			if ($request != 'POST') {
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request);
			}
		}
		
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		curl_setopt($curl, CURLOPT_USERPWD, ' :' . $apikey);
		$response = json_decode(curl_exec($curl), true);
		
		if ((!empty($response['type']) && $response['type'] == 'akamai_error_message') || ($this->config->get($prefix . $this->name . '_testing_mode') == 'debug' && empty($response['detail']) && $api != 'lists' && !strpos($api, 'interest-categories') && !strpos($api, 'merge-fields') && !strpos($api, 'webhooks'))) {
			$hr = '------------------------------------------------------------------------------';
			$this->logMessage($hr . "\n" . $request . '   ' . $url . $api . "\n" . $hr);
			$this->logMessage('DATA SENT: ' . print_r($data, true));
			
			$response_with_no_links = $response;
			
			if (is_array($response_with_no_links)) {
				foreach ($response_with_no_links as $key => $value) {
					if (!is_array($value)) continue;
					if ($key == '_links') unset($response_with_no_links[$key]);
					
					foreach ($value as $ke => $va) {
						if (!is_array($va)) continue;
						if ($ke == '_links') unset($response_with_no_links[$key][$ke]);
						
						foreach ($va as $k => $v) {
							if ($k == '_links') unset($response_with_no_links[$key][$ke][$k]);
						}
					}
				}
			}
			
			$this->logMessage('DATA RECEIVED: ' . print_r($response_with_no_links, true));
		}
		
		if (curl_error($curl)) {
			$response['error'] = 'CURL ERROR #' . curl_errno($curl) . ' - ' . curl_error($curl);
		} elseif (empty($response) && $request != 'DELETE') {
			$response['error'] = 'Empty CURL gateway response';
		} elseif (!empty($response['detail'])) {
			$response['error'] = $response['detail'];
			if (isset($response['errors'])) {
				foreach ($response['errors'] as $error) {
					$response['error'] .= ' '. $error['message'];
				}
			}
		}
		curl_close($curl);
		
		if (!empty($response['error']) && $this->config->get($prefix . $this->name . '_testing_mode') && !strpos($response['error'], 'store with the provided ID') && !strpos($response['error'], 'store with the domain') && $response['error'] != 'The requested resource could not be found.') {
			$this->logMessage('ERROR: ' . $response['error']);
		}
		
		return $response;
	}
}
?>