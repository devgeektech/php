<?php
//==============================================================================
// Ultimate Coupons v303.1
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ModelExtensionTotalUltimateCoupons extends Model {
	private $type = 'total';
	private $name = 'ultimate_coupons';
	private $testing_mode;
	private $charge;
	
	public function checkCoupon($code) {
		if (empty($code)) {
			unset($this->session->data['coupon']);
			return false;
		}
		
		$total_data = array();
		$order_total = $this->cart->getSubTotal();
		$taxes = $this->cart->getTaxes();
		
		$this->session->data['temp_coupon'] = $code;
		$valid_coupon = $this->getTotal(array('totals' => &$total_data, 'total' => &$order_total, 'taxes' => &$taxes));
		unset($this->session->data['temp_coupon']);
		
		return $valid_coupon;
	}
	
	public function getTotal($total_input) {
		$total_data = &$total_input['totals'];
		$order_total = &$total_input['total'];
		$taxes = &$total_input['taxes'];
		
		$settings = $this->cache->get($this->name . '.settings');
		if (empty($settings)) {
			$settings = $this->getSettings();
			$this->cache->set($this->name . '.settings', $settings);
		}
		
		$this->testing_mode = $settings['testing_mode'];
		$this->logMessage("\n" . '------------------------------ Starting Test ' . date('Y-m-d G:i:s') . ' ------------------------------');
		
		if (empty($settings['status'])) {
			$this->logMessage('Extension is disabled');
			return;
		}
		
		// non-standard
		if (isset($this->session->data['temp_coupon'])) {
			$coupon_code = $this->session->data['temp_coupon'];
		} elseif (isset($this->session->data['coupon'])) {
			$coupon_code = $this->session->data['coupon'];
		} else {
			return;
		}
		
		$coupon_array = (is_array($coupon_code)) ? $coupon_code : array($coupon_code);
		foreach ($coupon_array as $coupon) { // start coupon loop
		
		$coupon = strtoupper(trim($coupon));
		
		// Set address info
		$addresses = array();
		$this->load->model('account/address');
		foreach (array('shipping', 'payment', 'geoiptools') as $address_type) {
			if ($address_type == 'geoiptools' && !empty($this->session->data['geoip_data']['location'])) {
				$address = $this->session->data['geoip_data']['location'];
			} elseif (($address_type == 'shipping' && empty($address)) || $address_type == 'payment') {
				$address = array();
				
				if ($this->customer->isLogged()) 										$address = $this->model_account_address->getAddress($this->customer->getAddressId());
				if (!empty($this->session->data['country_id']))							$address['country_id'] = $this->session->data['country_id'];
				if (!empty($this->session->data['zone_id']))							$address['zone_id'] = $this->session->data['zone_id'];
				if (!empty($this->session->data['postcode']))							$address['postcode'] = $this->session->data['postcode'];
				if (!empty($this->session->data['city']))								$address['city'] = $this->session->data['city'];
				
				if (!empty($this->session->data[$address_type . '_country_id']))		$address['country_id'] = $this->session->data[$address_type . '_country_id'];
				if (!empty($this->session->data[$address_type . '_zone_id']))			$address['zone_id'] = $this->session->data[$address_type . '_zone_id'];
				if (!empty($this->session->data[$address_type . '_postcode']))			$address['postcode'] = $this->session->data[$address_type . '_postcode'];
				if (!empty($this->session->data[$address_type . '_city']))				$address['city'] = $this->session->data[$address_type . '_city'];
				
				if (!empty($this->session->data['guest'][$address_type]))				$address = $this->session->data['guest'][$address_type];
				if (!empty($this->session->data[$address_type . '_address_id']))		$address = $this->model_account_address->getAddress($this->session->data[$address_type . '_address_id']);
				if (!empty($this->session->data[$address_type . '_address']))			$address = $this->session->data[$address_type . '_address'];
			}
			
			if (empty($address['company']))		$address['company'] = '';
			if (empty($address['address_1']))	$address['address_1'] = '';
			if (empty($address['address_2']))	$address['address_2'] = '';
			if (empty($address['city']))		$address['city'] = '';
			if (empty($address['postcode']))	$address['postcode'] = '';
			if (empty($address['country_id']))	$address['country_id'] = $this->config->get('config_country_id');
			if (empty($address['zone_id']))		$address['zone_id'] =  $this->config->get('config_zone_id');
			
			$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$address['country_id']);
			$address['country'] = (isset($country_query->row['name'])) ? $country_query->row['name'] : '';
			$address['iso_code_2'] = (isset($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
			
			$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$address['zone_id']);
			$address['zone'] = (isset($zone_query->row['name'])) ? $zone_query->row['name'] : '';
			$address['zone_code'] = (isset($zone_query->row['code'])) ? $zone_query->row['code'] : '';
			
			$addresses[$address_type] = $address;
			
			$addresses[$address_type]['geo_zones'] = array();
			$geo_zones_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = " . (int)$address['country_id'] . " AND (zone_id = 0 OR zone_id = " . (int)$address['zone_id'] . ")");
			if ($geo_zones_query->num_rows) {
				foreach ($geo_zones_query->rows as $geo_zone) {
					$addresses[$address_type]['geo_zones'][] = $geo_zone['geo_zone_id'];
				}
			} else {
				$addresses[$address_type]['geo_zones'] = array(0);
			}
		}
		
		// Record testing mode info
		if ($this->customer->isLogged()) {
			$this->logMessage('CUSTOMER: ' . $this->customer->getFirstName() . ' ' . $this->customer->getLastName() . ' (customer_id: ' . $this->customer->getId() . ', ip: ' . $this->request->server['REMOTE_ADDR'] . ')');
		} else {
			$this->logMessage('CUSTOMER: Guest (' . $this->request->server['REMOTE_ADDR'] . ')');
		}
		
		if ($this->type != 'shipping') {
			$billing_address = array(
				$addresses['payment']['address_1'],
				$addresses['payment']['address_2'],
				$addresses['payment']['city'],
				$addresses['payment']['zone'],
				$addresses['payment']['postcode'],
				$addresses['payment']['country'],
			);
			$this->logMessage('BILLING ADDRESS: ' . implode(', ', array_filter($billing_address)));
		}
		
		$shipping_address = array(
			$addresses['shipping']['address_1'],
			$addresses['shipping']['address_2'],
			$addresses['shipping']['city'],
			$addresses['shipping']['zone'],
			$addresses['shipping']['postcode'],
			$addresses['shipping']['country'],
		);
		$this->logMessage('SHIPPING ADDRESS: ' . implode(', ', array_filter($shipping_address)));
		
		$this->logMessage('EVALUATING RULES:');
		
		// Set order totals if necessary
		if ($this->type != 'total') {
			$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'total_';
			
			$order_totals_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'total' ORDER BY `code` ASC");
			$order_totals = $order_totals_query->rows;
			
			$sort_order = array();
			foreach ($order_totals as $key => $value) {
				$sort_order[$key] = $this->config->get($prefix . $value['code'] . '_sort_order');
			}
			array_multisort($sort_order, SORT_ASC, $order_totals);
			
			$total_data = array();
			$order_total = 0;
			$taxes = $this->cart->getTaxes();
			$total_array = array('totals' => &$total_data, 'total' => &$order_total, 'taxes' => &$taxes);
			
			foreach ($order_totals as $ot) {
				if ($ot['code'] == 'shipping' && $this->type == 'shipping') break;
				if (!$this->config->get($prefix . $ot['code'] . '_status') || $ot['code'] == 'intermediate_order_total') continue;
				if (version_compare(VERSION, '2.2', '<')) {
					$this->load->model('total/' . $ot['code']);
					$this->{'model_total_' . $ot['code']}->getTotal($total_data, $order_total, $taxes);
				} elseif (version_compare(VERSION, '2.3', '<')) {
					$this->load->model('total/' . $ot['code']);
					$this->{'model_total_' . $ot['code']}->getTotal($total_array);
				} else {
					$this->load->model('extension/total/' . $ot['code']);
					$this->{'model_extension_total_' . $ot['code']}->getTotal($total_array);
				}
			}
		}
		
		// Set shipping/payment info
		$shipping_method = (isset($this->session->data['shipping_method']['code'])) ? substr($this->session->data['shipping_method']['code'], 0, strpos($this->session->data['shipping_method']['code'], '.')) : '';
		$shipping_rate = (isset($this->session->data['shipping_method']['title'])) ? strtolower($this->session->data['shipping_method']['title']) : '';
		$shipping_cost = (isset($this->session->data['shipping_method']['cost'])) ? $this->session->data['shipping_method']['cost'] : 0;
		
		if (isset($this->session->data['payment_method']['code'])) {
			$payment_method = $this->session->data['payment_method']['code'];
		} elseif (isset($this->request->post['payment_code'])) {
			$payment_method = $this->request->post['payment_code'];
		} else {
			$payment_method = '';
		}
		
		// Set cart and order data
		$this->load->model('catalog/product');
		
		$cart_products = $this->cart->getProducts();
		if (version_compare(VERSION, '2.1', '>=')) {
			foreach ($cart_products as &$cart_product) {
				$cart_product['key'] = $cart_product['product_id'] . json_encode($cart_product['option']);
			}
		}
		
		$cumulative_total_value = $order_total;
		$currency = $this->session->data['currency'];
		$customer_id = (int)$this->customer->getId();
		$customer_group_id = (int)$this->customer->getGroupId();
		$distance = 0;
		$language = (isset($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		$main_currency = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency' AND store_id = 0")->row['value'];
		$store_id = (isset($this->session->data['store_id'])) ? (int)$this->session->data['store_id'] : (int)$this->config->get('config_store_id');
		
		$this->load->model('account/reward');
		//$coupon = (isset($this->session->data['coupon'])) ? $this->session->data['coupon'] : '';
		$reward_points = (isset($this->session->data['reward'])) ? $this->session->data['reward'] : '';
		$reward_points_in_account = $this->model_account_reward->getTotalPoints();
		$voucher = (isset($this->session->data['voucher'])) ? $this->session->data['voucher'] : '';
		
		$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$customer_id);
		if (!empty($customer->row['custom_field'])) {
			$customer_custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($customer->row['custom_field']) : json_decode($customer->row['custom_field'], true);
		} else {
			$customer_custom_fields = array();
		}
		
		// Loop through charges
		$sort_order = array();
		foreach ($settings['charge'] as $key => $value) {
			$sort_order[$key] = (empty($value['group'])) ? 0 : $value['group'];
		}
		array_multisort($sort_order, SORT_ASC, $settings['charge']);
		
		$charges = array();
		
		foreach ($settings['charge'] as $charge) {
			// Check coupon code
			$matches = array();
			preg_match('/^' . strtoupper($charge['coupon']) . '$/', $coupon, $matches);
			
			if (empty($charge['coupon']) || empty($matches)) {
				continue;
			}
			
			// Check other coupon settings
			$charge['group'] = 0;
			$charge['title'] = $charge['coupon'];
			$this->charge = $charge;
			
			if (empty($charge['status'])) {
				$this->logMessage('Coupon "' . $this->charge['title'] . '" is currently disabled.');
				continue;
			}
			
			if (!empty($charge['uses_per_coupon']) && empty($this->session->data['api_id'])) {
				$past_coupon_uses = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE UCASE(coupon_id) = '" . $this->db->escape($coupon) . "'")->row['total'];
				if ($past_coupon_uses >= (int)$charge['uses_per_coupon']) {
					$this->logMessage('Coupon "' . $this->charge['title'] . '" disabled because coupon has been used more than the maximum of ' . $charge['uses_per_coupon'] . ' times.');
					continue;
				}
			}
			
			if (!empty($charge['uses_per_customer']) && empty($this->session->data['api_id'])) {
				$past_coupon_uses = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE UCASE(coupon_id) = '" . $this->db->escape($coupon) . "' AND customer_id = " . (int)$customer_id)->row['total'];
				
				if (!$this->customer->isLogged()) {
					$past_coupon_uses = 0;
					if (!empty($this->session->data['guest']['email'])) {
						$past_coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_total ot ON (ot.order_id = o.order_id) WHERE o.order_status_id > 0 AND o.email = '" . $this->db->escape($this->session->data['guest']['email']) . "' AND ot.code = '" . $this->db->escape($this->name) . "'");
						foreach ($past_coupon_query->rows as $past_coupon_row) {
							$explode = explode('(', $past_coupon_row['title']);
							$past_coupon = str_replace(')', '', $explode[1]);
							if (strtoupper($past_coupon) == $coupon) {
								$past_coupon_uses++;
							}
						}
					}
				}
				
				if ($past_coupon_uses >= (int)$charge['uses_per_customer']) {
					$this->logMessage('Coupon "' . $this->charge['title'] . '" disabled because customer with id ' . $customer_id . ' has used it more than the maximum of ' . $charge['uses_per_customer'] . ' times.');
					continue;
				}
			}

			// Compile rules and rule sets
			$rule_list = (!empty($charge['rule'])) ? $charge['rule'] : array();
			$rule_sets = array();
			
			foreach ($rule_list as $rule) {
				if (isset($rule['type']) && $rule['type'] == 'rule_set') {
					$rule_sets[] = $settings['rule_set'][$rule['value']]['rule'];
				}
			}
			
			foreach ($rule_sets as $rule_set) {
				$rule_list = array_merge($rule_list, $rule_set);
			}
			
			$rules = array();
			foreach ($rule_list as $rule) {
				if (empty($rule['type'])) continue;
				
				if (isset($rule['value'])) {
					if (in_array($rule['type'], array('attribute_group', 'category', 'manufacturer', 'product', 'zone'))) {
						$value = substr($rule['value'], strrpos($rule['value'], '[') + 1, -1);
					} else {
						$value = $rule['value'];
					}
				} else {
					$value = 1;
				}
				
				if (!isset($rule['comparison'])) $rule['comparison'] = '';
				if (in_array($rule['type'], array('attribute', 'custom_field', 'option'))) {
					$comparison = substr($rule['comparison'], strrpos($rule['comparison'], '[') + 1, -1);
				} else {
					$comparison = $rule['comparison'];
				}
				$rules[$rule['type']][$comparison][] = $value;
			}
			$this->charge['rules'] = $rules;
			
			// Perform settings overrides
			if (!empty($defaults)) {
				foreach ($defaults as $key => $value) {
					$this->config->set($key, $value);
				}
			}
			
			$defaults = array();
			
			if (isset($rules['setting_override'])) {
				foreach ($rules['setting_override'] as $setting => $override) {
					$defaults[$setting] = $this->config->get($setting);
					$this->config->set($setting, $override[0]);
					
					if ($setting == 'config_address') {
						$distance = 0;
					}
				}
			}
			
			// Check date/time criteria
			if ($this->ruleViolation('day', strtolower(date('l'))) ||
				$this->ruleViolation('date', date('Y-m-d')) ||
				$this->ruleViolation('time', date('H:i'))
			) {
				continue;
			}
			
			// Check discount criteria
			if (isset($rules['gift_voucher'])) {
				foreach ($rules['gift_voucher'] as $comparison => $rule_vouchers) {
					if ($comparison == 'applied') {
						$voucher_value = 0;
						if ($voucher) {
							foreach ($total_data as $ot) {
								if ($ot['code'] == 'voucher') $voucher_value = -$ot['value'];
							}
							if (!$voucher_value) {
								$temp_total_data = array();
								$temp_total = 1000000;
								$temp_taxes = $this->cart->getTaxes();
								$temp_totals = array(
									'totals'	=> &$temp_total_data,
									'total'		=> &$temp_total,
									'taxes'		=> &$temp_taxes,
								);
								
								if (version_compare(VERSION, '2.2', '<')) {
									$this->load->model('total/voucher');
									$this->model_total_voucher->getTotal($temp_total_data, $temp_total, $temp_taxes);
								} elseif (version_compare(VERSION, '2.3', '<')) {
									$this->load->model('total/voucher');
									$this->model_total_voucher->getTotal($temp_totals);
								} else {
									$this->load->model('extension/total/voucher');
									$this->model_extension_total_voucher->getTotal($temp_totals);
								}
								
								$voucher_value = 1000000 - $temp_total;
							}
						}
						if (!$this->inRange($voucher_value, $rule_vouchers, 'gift voucher applied to cart')) {
							continue 2;
						}
					} elseif ($comparison == 'purchased') {
						$qualifying_voucher_being_purchased = false;
						$vouchers = (!empty($this->session->data['vouchers'])) ? $this->session->data['vouchers'] : array(array('amount' => 0));
						foreach ($vouchers as $voucher) {
							if ($this->inRange($voucher['amount'], $rule_vouchers, 'gift voucher being purchased', true)) {
								$qualifying_voucher_being_purchased = true;
							}
						}
						if (!$qualifying_voucher_being_purchased) {
							$this->logMessage('"' . $this->charge['title'] . '" disabled for violating "Gift Voucher being purchased" rule(s)');
							continue 2;
						}
					}
				}
			}
			
			if (isset($rules['reward_points'])) {
				$cart_reward_points = 0;
				foreach ($cart_products as $product) {
					$cart_reward_points += $product['reward'];
				}
				foreach ($rules['reward_points'] as $comparison => $rule_reward_points) {
					if ($comparison == 'applied') {
						if (!$this->inRange($reward_points, $rule_reward_points, 'reward points ' . $comparison)) {
							continue 2;
						}
					} elseif ($comparison == 'products') {
						if (!$this->inRange($cart_reward_points, $rule_reward_points, 'reward points of ' . $comparison)) {
							continue 2;
						}
					} elseif ($comparison == 'customer') {
						if (!$this->inRange($reward_points_in_account, $rule_reward_points, 'reward points of ' . $comparison)) {
							continue 2;
						}
					}
				}
			}
			
			// Check location criteria
			if (isset($rules['location_comparison'])) {
				$location_comparison = $rules['location_comparison'][''][0];
			} else {
				$location_comparison = ($this->type == 'shipping' || empty($addresses['payment']['city'])) ? 'shipping' : 'payment';
			}
			$address = $addresses[$location_comparison];
			$postcode = $address['postcode'];
			
			if (isset($rules['address'])) {
				$this->commaMerge($rules['address']);
				$this->charge['rules']['address'] = $rules['address'];
				
				$address_line_1 = strtolower($address['address_1']);
				
				foreach ($rules['address'] as $comparison => $values) {
					$skip_charge = ($comparison == 'is');
					$skip_message = '';
					
					foreach ($values as $value) {
						if (strpos($address_line_1, $value) !== false) {
							$skip_charge = ($comparison == 'not');
						} else {
							$skip_message = '"' . $this->charge['title'] . '" disabled for violating rule "address ' . $comparison . ' ' . $value . '"';
						}
					}
					
					if ($skip_charge) {
						$this->logMessage($skip_message);
						continue 2;
					}
				}
			}
			
			if (isset($rules['city'])) {
				$this->commaMerge($rules['city']);
				$this->charge['rules']['city'] = $rules['city'];
			}
			
			if ($this->ruleViolation('city', strtolower(trim($address['city']))) ||
				$this->ruleViolation('country', $address['country_id']) ||
				$this->ruleViolation('geo_zone', $address['geo_zones']) ||
				$this->ruleViolation('zone', $address['zone_id'])
			) {
				continue;
			}
			
			if (isset($rules['postcode'])) {
				$this->commaMerge($rules['postcode']);
				
				foreach ($rules['postcode'] as $comparison => $postcodes) {
					$in_range = $this->inRange($postcode, $postcodes, 'postcode' . ($comparison == 'not' ? ' not' : ''));
					
					if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
						continue 2;
					}
				}
			}
			
			// Check order criteria
			if ($this->ruleViolation('currency', $currency) ||
				$this->ruleViolation('customer', $customer_id) ||
				$this->ruleViolation('customer_group', $customer_group_id) ||
				$this->ruleViolation('language', $language) ||
				$this->ruleViolation('store', $store_id)
			) {
				continue;
			}
			
			// extension-specific
			if (empty($this->session->data['temp_coupon'])) {
				if ($this->ruleViolation('payment_extension', $payment_method) || $this->ruleViolation('shipping_extension', $shipping_method)) {
					continue;
				}
			}
			// end
			
			if (isset($rules['custom_field'])) {
				$this->commaMerge($rules['custom_field']);
				
				$custom_fields = $customer_custom_fields;
				if (!empty($address['custom_field'])) {
					$custom_fields += $address['custom_field'];
				}
				
				foreach ($rules['custom_field'] as $comparison => $values) {
					foreach ($custom_fields as $custom_field_id => $custom_field_value) {
						$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_id = " . (int)$custom_field_id . " AND custom_field_value_id = " . (int)$custom_field_value);
						if ($custom_field_value_query->num_rows) {
							$custom_field_value = $custom_field_value_query->row['name'];
						}
						if ($comparison == $custom_field_id && (empty($values[0]) || in_array(strtolower($custom_field_value), $values))) {
							continue 2;
						}
					}
					
					$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "custom_field_id ' . $comparison . ' = ' . implode(', ', $values) . '"');
					continue 2;
				}
			}
			
			if (isset($rules['customer_data'])) {
				$this->commaMerge($rules['customer_data']);
				
				if ($this->customer->isLogged()) {
					$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$this->customer->getId())->row;
				} elseif (isset($this->session->data['guest'])) {
					$customer = $this->session->data['guest'];
				} else {
					$customer = array();
				}
				
				$customer['company'] = $address['company'];
				
				foreach ($rules['customer_data'] as $comparison => $values) {
					if (!isset($customer[$comparison])) $customer[$comparison] = '';
					
					if (empty($values[0])) {
						if (empty($customer[$comparison])) {
							$this->logMessage('"' . $this->charge['title'] . '" ignored for violating rule "' . $comparison . ' must be filled in"');
							continue 2;
						}
					} else {
						if (!$this->inRange($customer[$comparison], $values, $comparison)) {
							continue 2;
						}
					}
				}
			}
			
			if (isset($rules['past_orders'])) {
				$this->commaMerge($rules['past_orders']);
				
				$coupon_sql = "";
				$days_sql = "";
				$order_status_sql = " AND o.order_status_id > 0";
				$product_sql = "";
				$total_table = "o.";
				
				foreach ($rules['past_orders'] as $comparison => $values) {
					if ($comparison == 'coupon_used' || $comparison == 'coupon_unused') {
						$this->db->query("SET group_concat_max_len = 9999");
					}
					
					if ($comparison == 'days') {
						$value = array_pop($values);
						$days = explode('-', $value);
						$days_sql = " AND o.date_added <= (CURDATE() - INTERVAL " . ($days[0] - 1) . " DAY)";
						if (isset($days[1])) $days_sql .= " AND o.date_added >= (CURDATE() - INTERVAL " . $days[1] . " DAY)";
					}
					
					$values = array_map('intval', $values);
					
					if ($comparison == 'manufacturer') {
						$manufacturer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE (manufacturer_id = " . implode(" OR manufacturer_id = ", $values) . ")");
						$product_ids = array();
						foreach ($manufacturer_query->rows as $row) {
							$product_ids[] = (int)$row['product_id'];
						}
						$product_sql .= " AND (op.product_id = " . implode(" OR op.product_id = ", $product_ids) . ")";
						$total_table = "op.";
					}
					
					if ($comparison == 'order_status') {
						$order_status_sql = " AND (o.order_status_id = " . implode(" OR o.order_status_id = ", $values) . ")";
					}
					
					if ($comparison == 'product') {
						$product_sql .= " AND (op.product_id = " . implode(" OR op.product_id = ", $values) . ")";
						$total_table = "op.";
					}
				}
				
				$past_orders_query = $this->db->query("SELECT IFNULL(GROUP_CONCAT(DISTINCT(LCASE(ch.coupon_id)) SEPARATOR ','), '') AS coupons, IFNULL(MIN(ROUND((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(o.date_added)) / 86400)), 0) AS days, IFNULL(COUNT(*), 0) AS quantity, IFNULL(AVG(" . $total_table . "total), 0) AS average, IFNULL(SUM(" . $total_table . "total), 0) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_id = o.order_id) LEFT JOIN " . DB_PREFIX . "coupon_history ch ON (ch.order_id = o.order_id) WHERE o.customer_id = " . (int)$customer_id . " AND o.customer_id != 0 " . $coupon_sql . $days_sql . $order_status_sql . $product_sql);
				
				$coupons = explode(',', $past_orders_query->row['coupons']);
				
				foreach ($rules['past_orders'] as $comparison => $values) {
					if (in_array($comparison, array('manufacturer', 'order_status', 'product'))) {
						continue;
					}
					
					if ($comparison == 'coupon_used') {
						if (!array_intersect($values, $coupons)) {
							$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "past order ' . $comparison . ' = ' . implode(', ', $values) . '"');
							continue 2;
						}
					} elseif ($comparison == 'coupon_unused') {
						if (array_intersect($values, $coupons)) {
							$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "past order ' . $comparison . ' = ' . implode(', ', $values) . '"');
							continue 2;
						}
					} elseif ($comparison == 'order_amount') {
						$skip = true;
						$single_orders_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = " . (int)$customer_id . " AND o.customer_id != 0 " . $days_sql . $order_status_sql);
						
						foreach ($single_orders_query->rows as $order) {
							$order_query = $this->db->query("SELECT SUM(op.total) AS order_amount FROM " . DB_PREFIX . "order_product op WHERE op.order_id = " . (int)$order['order_id'] . $product_sql);
							if ($this->inRange($order_query->row[$comparison], $values, 'past order ' . $comparison, true)) {
								$skip = false;
								break;
							}
						}
						
						if ($skip) {
							continue 2;
						}
					} elseif (!$this->inRange($past_orders_query->row[$comparison], $values, 'past order ' . $comparison)) {
						continue 2;
					}
				}
			}
			
			if (isset($rules['shipping_cost'])) {
				$this->commaMerge($rules['shipping_cost']);
				
				foreach ($rules['shipping_cost'] as $comparison => $brackets) {
					$in_range = $this->inRange($shipping_cost, $brackets, 'shipping_cost' . ($comparison == 'not' ? ' not' : ''));
					
					if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
						continue 2;
					}
				}
			}
			
			if (isset($rules['shipping_rate'])) {
				$this->commaMerge($rules['shipping_rate']);
				$is_rule_passed = empty($rules['shipping_rate']['is']);
				$not_rule_violation = false;
				$skip_message = '';
				
				foreach ($rules['shipping_rate'] as $comparison => $values) {
					foreach ($values as $value) {
						if ($comparison == 'is') {
							if (strpos($shipping_rate, $value) !== false) {
								$is_rule_passed = true;
							} else {
								$skip_message = '"' . $this->charge['title'] . '" disabled for violating rule "shipping_rate ' . $comparison . ' ' . $value . '"';
							}
						}
						if ($comparison == 'not') {
							if (strpos($shipping_rate, $value) !== false) {
								$not_rule_violation = true;
								$skip_message = '"' . $this->charge['title'] . '" disabled for violating rule "shipping_rate ' . $comparison . ' ' . $value . '"';
							}
						}
					}
				}
				
				if (!$is_rule_passed || $not_rule_violation) {
					$this->logMessage($skip_message);
					continue;
				}
			}
			
			// Generate comparison values
			$cart_criteria = array(
				'length',
				'width',
				'height',
				'quantity',
				'product_count',
				'stock',
				'total',
				'volume',
				'weight',
			);
			
			foreach ($cart_criteria as $spec) {
				${$spec.'s'} = array();
				if (isset($rules[$spec])) {
					$this->commaMerge($rules[$spec]);
				}
			}
			
			$attributes = array();
			$attribute_groups = array();
			$attribute_values = array();
			$categorys = array();
			$manufacturers = array();
			$options = array();
			$option_values = array();
			$option_array = array();
			$products = array();
			
			$other_product_data_charges = array();
			$product_keys = array();
			$total_value = $cumulative_total_value;
			
			foreach ($cart_products as $product) {
				if ($this->type == 'shipping' && !$product['shipping']) {
					$total_value -= $product['total'];
					$this->logMessage($product['name'] . ' (product_id: ' . $product['product_id'] . ') does not require shipping and was ignored');
					continue;
				}
				
				// check if Special and Discount products should be ignored
				if (isset($rules['ignore_specials'])) {
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					$product_discount_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = " . (int)$product['product_id'] . " AND customer_group_id = " . (int)($customer_group_id ? $customer_group_id : $this->config->get('config_customer_group_id')) . " AND quantity <= " . (int)$product['quantity'] . " AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
					
					if ($product_info['special'] || $product_discount_query->num_rows) {
						$this->logMessage($product['name'] . ' (product_id: ' . $product['product_id'] . ') has a Special or Discount price, and was ignored');
						continue;
					}
				}
				
				// get extra product data
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = " . (int)$product['product_id']);
				
				// dimensions
				$length_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class WHERE length_class_id = " . (int)$product['length_class_id']);
				if ($length_class_query->num_rows) {
					$lengths[$product['key']] = $this->length->convert($product['length'], $product['length_class_id'], $this->config->get('config_length_class_id'));
					$widths[$product['key']] = $this->length->convert($product['width'], $product['length_class_id'], $this->config->get('config_length_class_id'));
					$heights[$product['key']] = $this->length->convert($product['height'], $product['length_class_id'], $this->config->get('config_length_class_id'));
				} else {
					$message = $product['name'] . ' (product_id: ' . $product['product_id'] . ') does not have a valid length class, which causes a "Division by zero" error, and means it cannot be used for dimension/volume calculations. You can fix this by re-saving the product data.';
					$this->log->write($message);
					$this->logMessage($message);
					
					$lengths[$product['key']] = 0;
					$widths[$product['key']] = 0;
					$heights[$product['key']] = 0;
				}
				
				// product_count
				$product_counts[$product['key']] = 1;
				
				// quantity
				$quantitys[$product['key']] = $product['quantity'];
				
				// stock
				$stocks[$product['key']] = $product_query->row['quantity'] - $product['quantity'];
				
				// total
				if (isset($rules['total_value'])) {
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					$product_price = ($product_info['special']) ? $product_info['special'] : $product_info['price'];
					
					if (in_array('prediscounted', $rules['total_value'][''])) {
						$totals[$product['key']] = $product['total'] + ($product['quantity'] * ($product_query->row['price'] - $product_price));
					} elseif (in_array('nondiscounted', $rules['total_value'][''])) {
						$product_discount_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = " . (int)$product['product_id'] . " AND customer_group_id = " . (int)($customer_group_id ? $customer_group_id : $this->config->get('config_customer_group_id')) . " AND quantity <= " . (int)$product['quantity'] . " AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
						$totals[$product['key']] = ($product_info['special'] || $product_discount_query->num_rows) ? 0 : $product['total'];
					} elseif (in_array('taxed', $rules['total_value'][''])) {
						$totals[$product['key']] = $this->tax->calculate($product['total'], $product['tax_class_id']);
					} elseif (in_array('ignoreoptions', $rules['total_value'][''])) {
						$totals[$product['key']] = $product_price * $product['quantity'];
					}
				}
				if (!isset($totals[$product['key']])) {
					$totals[$product['key']] = $product['total'];
				}
				
				// volume
				$volumes[$product['key']] = $lengths[$product['key']] * $widths[$product['key']] * $heights[$product['key']] * $product['quantity'];
				
				// weight
				$weight_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class WHERE weight_class_id = " . (int)$product['weight_class_id']);
				if ($weight_class_query->num_rows) {
					$weights[$product['key']] = $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
				} else {
					$message = $product['name'] . ' (product_id: ' . $product['product_id'] . ') does not have a valid weight class, which causes a "Division by zero" error, and means it cannot be used for weight calculations. You can fix this by re-saving the product data.';
					$this->log->write($message);
					$this->logMessage($message);
					
					$weights[$product['key']] = 0;
				}
				
				// attributes
				$attribute_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (pa.attribute_id = a.attribute_id) WHERE pa.product_id = " . (int)$product['product_id']);
				if ($attribute_query->num_rows) {
					foreach ($attribute_query->rows as $attribute) {
						$attributes[$product['key']][] = $attribute['attribute_id'];
						$attribute_groups[$product['key']][] = $attribute['attribute_group_id'];
						foreach (explode(',', $attribute['text']) as $attribute_value) {
							$attribute_values[$product['key']][$attribute['attribute_id']][] = trim($attribute_value);
						}
					}
				} else {
					$attributes[$product['key']][] = 0;
					$attribute_groups[$product['key']][] = 0;
					$attribute_values[$product['key']][0][] = 0;
				}
				
				// categories
				$category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = " . (int)$product['product_id']);
				if ($category_query->num_rows) {
					foreach ($category_query->rows as $category) {
						$categorys[$product['key']][] = $category['category_id'];
					}
				} else {
					$categorys[$product['key']][] = 0;
				}
				
				// manufacturer
				$manufacturers[$product['key']][] = $product_query->row['manufacturer_id'];
				
				// options
				if (!empty($product['option'])) {
					foreach ($product['option'] as $option) {
						$options[$product['key']][] = $option['option_id'];
						$option_values[$product['key']][] = $option['option_value_id'];
						$option_array[$product['key']][$option['option_id']][] = $option['value'];
					}
				} else {
					$options[$product['key']][] = 0;
					$option_values[$product['key']][] = 0;
					$option_array[$product['key']][0][] = 0;
				}
				
				// products
				$products[$product['key']][] = $product['product_id'];
				
				// Check item criteria (entire cart comparisons)
				foreach ($cart_criteria as $spec) {
					if (isset($rules['adjust']['item_' . $spec])) {
						foreach ($rules['adjust']['item_' . $spec] as $adjustment) {
							${$spec.'s'}[$product['key']] += (strpos($adjustment, '%')) ? ${$spec.'s'}[$product['key']] * (float)$adjustment / 100 : (float)$adjustment;
						}
					}
					
					$spec_value = ${$spec.'s'}[$product['key']];
					if ($spec == 'weight') $spec_value /= $product['quantity'];
					
					if (isset($rules[$spec]['entire_any'])) {
						if (!$this->inRange($spec_value, $rules[$spec]['entire_any'], $spec . ' of any item in entire cart', true)) {
							continue 2;
						}
					}
					
					if (isset($rules[$spec]['entire_every'])) {
						if (!$this->inRange($spec_value, $rules[$spec]['entire_every'], $spec . ' of every item in entire cart', true)) {
							continue 3;
						}
					}
				}
				
				// Check product criteria
				if (isset($rules['attribute'])) {
					$this->commaMerge($rules['attribute']);
					
					foreach ($rules['attribute'] as $attribute_id => $values) {
						$attribute_rule_text = 'attribute_id ' . $attribute_id . ' = ' . implode(', ', $values);
						if (empty($values[0]) && isset($attribute_values[$product['key']][$attribute_id])) {
							continue;
						} elseif (isset($attribute_values[$product['key']][$attribute_id])) {
							foreach ($attribute_values[$product['key']][$attribute_id] as $attribute_value) {
								if ($this->inRange(strtolower($attribute_value), $values, 'attribute', true)) {
									continue 2;
								}
							}
						}
						$this->logMessage('Product "' . $product['name'] . ' (product_id: ' . $product['product_id'] . ') is not eligible for charge "' . $this->charge['title'] . '" because it violates rule "' . $attribute_rule_text . '"');
						continue 2;
					}
				}
				
				foreach (array('attribute_group', 'category') as $criteria) {
					if (isset($rules[$criteria])) {
						if ($this->ruleViolation($criteria, ${$criteria . 's'}[$product['key']])) {
							continue 2;
						}
					}
				}
				
				if (isset($rules['option'])) {
					$this->commaMerge($rules['option']);
					
					foreach ($rules['option'] as $option_id => $values) {
						$option_rule_text = 'option_id ' . $option_id . ' = ' . implode(', ', $values);
						if (empty($values[0]) && isset($option_array[$product['key']][$option_id])) {
							continue;
						} elseif (isset($option_array[$product['key']][$option_id])) {
							foreach ($option_array[$product['key']][$option_id] as $option_value) {
								if ($this->inRange(strtolower($option_value), $values, 'option', true)) {
									continue 2;
								}
							}
						}
						$this->logMessage('Product "' . $product['name'] . ' (product_id: ' . $product['product_id'] . ') is not eligible for charge "' . $this->charge['title'] . '" because it violates rule "' . $option_rule_text . '"');
						continue 2;
					}
				}
				
				if (isset($rules['manufacturer']) && $this->ruleViolation('manufacturer', $product_query->row['manufacturer_id'])) {
					continue;
				}
				
				if (isset($rules['product']) && $this->ruleViolation('product', $product['product_id'])) {
					continue;
				}
				
				if (isset($rules['recurring_profile']) && $this->ruleViolation('recurring_profile', $product['recurring']['recurring_id'])) {
					continue;
				}
				
				// Check item criteria (eligible item comparisons)
				foreach ($cart_criteria as $spec) {
					$spec_value = ${$spec.'s'}[$product['key']];
					if ($spec == 'weight') $spec_value /= $product['quantity'];
					
					if (isset($rules[$spec]['any'])) {
						if (!$this->inRange($spec_value, $rules[$spec]['any'], $spec . ' of any item', true)) {
							continue 2;
						}
					}
					
					if (isset($rules[$spec]['every'])) {
						if (!$this->inRange($spec_value, $rules[$spec]['every'], $spec . ' of every item', true)) {
							continue 3;
						}
					}
				}
				
				// Check other product data
				if (isset($rules['other_product_data'])) {
					$this->commaMerge($rules['other_product_data']);
					foreach ($rules['other_product_data'] as $comparison => $values) {
						if ($values[0] == '') {
							if ($charge['type'] == 'flat') {
								$other_product_data_charges[] = (float)$product_query->row[$comparison];
							} elseif ($charge['type'] == 'peritem') {
								$other_product_data_charges[] = (float)($product_query->row[$comparison] * $product['quantity']);
							} else {
								$brackets = array_filter(explode(',', $product_query->row[$comparison]));
								$other_product_data_charges[] = (float)$this->calculateBrackets($brackets, $charge['type'], ${$charge['type'].'s'}[$product['key']], $product['quantity'], $product['total']);
							}
							continue;
						}
						if (!$this->inRange(strtolower($product_query->row[$comparison]), $values, 'other product data')) {
							continue 2;
						}
					}
				}
				
				// product passed all rules and is eligible for charge
				$product_keys[] = $product['key'];
			}
			
			// Check product group rules
			$row_disabled_text = '"' . $this->charge['title'] . '" disabled';
			
			if (isset($rules['product_group'])) {
				$list_types = array(
					'attribute',
					'attribute_group',
					'category',
					'manufacturer',
					'option',
					'option_value',
					'product',
				);
				
				foreach ($list_types as $list_type) {
					${$list_type . 's_array'} = array();
					foreach (${$list_type . 's'} as $list) {
						${$list_type . 's_array'} = array_merge(${$list_type . 's_array'}, $list);
					}
				}
				
				$eligible_products = array();
				$ineligible_products = array();
				
				foreach ($rules['product_group'] as $comparison => $product_group_ids) {
					$rule_satisfied = false;
					
					foreach ($product_group_ids as $product_group_id) {
						if (empty($settings['product_group'][$product_group_id]['member'])) continue;
						
						$product_group_rule_text = 'cart has items from ' . ($comparison == 'none' ? 'none of the' : $comparison) . ' members of ' . $settings['product_group'][$product_group_id]['name'];
						unset($members_array);
						
						foreach ($settings['product_group'][$product_group_id]['member'] as $member) {
							$bracket = strrpos($member, '[');
							$colon = strrpos($member, ':');
							$member_type = substr($member, $bracket + 1, $colon - $bracket - 1);
							$member_id = substr($member, $colon + 1, -1);
							$members_array[$member_type][] = $member_id;
							
							if ($member_type == 'category' && $settings['product_group'][$product_group_id]['subcategories']) {
								$child_category_ids = $this->getChildCategoryIds($member_id);
								foreach ($child_category_ids as $child_category_id) {
									$members_array[$member_type][] = $child_category_id;
								}
							}
						}
						
						foreach ($members_array as $type => $members) {
							// Check "all", "onlyall", and "none" comparisons
							if (($comparison == 'all' || $comparison == 'onlyall') && array_diff($members, ${$type.'s_array'})) {
								$this->logMessage($row_disabled_text . ' for violating product group rule "' . $product_group_rule_text . '", due to missing ' . $type . '_id(s) "' . implode(', ', array_diff($members, ${$type.'s_array'})) . '"');
								continue 4;
							}
							
							if (($comparison == 'not' || $comparison == 'none') && empty($cart_products)) {
								$rule_satisfied = true;
							}
							
							// Check product eligibility
							foreach ($cart_products as $product) {
								if ($this->type == 'shipping' && !$product['shipping']) {
									continue;
								}
								
								if ($type == 'category') {
									if (($comparison == 'onlyany' || $comparison == 'onlyall') && array_intersect(${$type.'s'}[$product['key']], $members)) {
										$rule_satisfied = true;
										$eligible_products[] = $product['key'];
										continue;
									}
									if ($comparison == 'not' && array_intersect(${$type.'s'}[$product['key']], $members)) {
										$ineligible_products[] = $product['key'];
										continue;
									}
								}
								
								if ((($comparison == 'onlyany' || $comparison == 'onlyall') && array_diff(${$type.'s'}[$product['key']], $members)) ||
									($comparison == 'none' && array_intersect(${$type.'s'}[$product['key']], $members))
								) {
									$this->logMessage($row_disabled_text . ' for violating product group rule "' . $product_group_rule_text . '"');
									continue 5;
								} elseif (($comparison != 'not' && $comparison != 'none' && !array_intersect(${$type.'s'}[$product['key']], $members)) ||
									(($comparison == 'not' || $comparison == 'none') && !array_diff(${$type.'s'}[$product['key']], $members))
								) {
									$ineligible_products[] = $product['key'];
								} else {
									$rule_satisfied = true;
									$eligible_products[] = $product['key'];
								}
							}
						}
					}
					
					// Check that rule has at least one matching product
					if (!$rule_satisfied) {
						$this->logMessage($row_disabled_text . ' for having no eligible products');
						continue 2;
					}
				}
				
				// Remove ineligible products
				foreach ($ineligible_products as $ineligible_key) {
					if (in_array($ineligible_key, $eligible_products)) continue;
					foreach ($product_keys as $index => $product_key) {
						if ($product_key == $ineligible_key) unset($product_keys[$index]);
					}
				}
			}
			
			// Check for empty product list
			if (empty($product_keys)) {
				$disable_charge = true;
				
				if (!empty($this->session->data['vouchers'])) {
					$disable_charge = false;
					foreach ($rules as $type => $value) {
						if (in_array($type, array('attribute', 'attribute_group', 'category', 'manufacturer', 'option', 'product', 'product_group', 'other_product_data'))) {
							$disable_charge = true;
						}
					}
				}
				
				if ($disable_charge) {
					$this->logMessage($row_disabled_text . ' for having no eligible products');
					continue;
				}
			}
			
			// Check cart criteria and generate total comparison values
			$single_foreign_currency = (isset($rules['currency']['is']) && count($rules['currency']['is']) == 1 && $main_currency != $currency) ? $rules['currency']['is'][0] : '';
			
			foreach ($cart_criteria as $spec) {
				// note: cart_comparison to be added here if requested
				if ($spec == 'total' && isset($rules['total_value']) && in_array('total', $rules['total_value'][''])) {
					$total = $total_value;
					$cart_total = $total_value;
				} else {
					${$spec} = 0;
					foreach ($product_keys as $product_key) {
						${$spec} += ${$spec.'s'}[$product_key];
					}
					${'cart_'.$spec} = array_sum(${$spec.'s'});
				}
				
				if ($spec == 'total' && $single_foreign_currency) {
					$total = $this->currency->convert($total, $main_currency, $single_foreign_currency);
				}
				
				if (isset($rules['adjust']['cart_' . $spec])) {
					foreach ($rules['adjust']['cart_' . $spec] as $adjustment) {
						${$spec} += (strpos($adjustment, '%')) ? ${$spec} * (float)$adjustment / 100 : (float)$adjustment;
						${'cart_'.$spec} += (strpos($adjustment, '%')) ? ${'cart_'.$spec} * (float)$adjustment / 100 : (float)$adjustment;
					}
				}
				
				if (isset($rules[$spec]['cart'])) {
					if (!$this->inRange(${$spec}, $rules[$spec]['cart'], $spec . ' of cart')) {
						continue 2;
					}
				}
				
				if (isset($rules[$spec]['entire_cart'])) {
					if (!$this->inRange(${'cart_'.$spec}, $rules[$spec]['entire_cart'], $spec . ' of entire cart')) {
						continue 2;
					}
				}
			}
			
			// Check distance rules
			if ((isset($rules['distance']) || $charge['type'] == 'distance') && !$distance) {
				$store_address = html_entity_decode(preg_replace('/\s+/', '+', $this->config->get('config_address')), ENT_QUOTES, 'UTF-8');
				$settings['google_apikey'] = trim($settings['google_apikey']);
				
				if (!empty($address['geocode'])) {
					$customer_address = $address['geocode'];
				} else {
					$customer_address = $address['address_1'] . ' ' . $address['address_2'] . ' ' . $address['city'] . ' ' . $address['zone'] . ' ' . $address['country'] . ' ' . $address['postcode'];
					$customer_address = html_entity_decode(preg_replace('/\s+/', '+', $customer_address), ENT_QUOTES, 'UTF-8');
				}
				
				if (isset($settings['distance_calculation']) && $settings['distance_calculation'] == 'driving') {
					$directions = $this->curlRequest('https://maps.googleapis.com/maps/api/directions/json?key=' . $settings['google_apikey'] . '&origin=' . $store_address . '&destination=' . $customer_address);
					if (empty($directions['routes'])) {
						sleep(1);
						$directions = $this->curlRequest('https://maps.googleapis.com/maps/api/directions/json?key=' . $settings['google_apikey'] . '&origin=' . $store_address . '&destination=' . $customer_address);
						if (empty($directions['routes'])) {
							$this->logMessage('The Google directions service returned the error "' . $directions['status'] . '" for origin "' . $store_address . '" and destination "' . $customer_address . '"');
							continue;
						}
					}
					$distance = $directions['routes'][0]['legs'][0]['distance']['value'] / 1609.344;
				} else {
					if ($this->config->get('config_geocode')) {
						$xy = explode(',', $this->config->get('config_geocode'));
						$x1 = $xy[0];
						$y1 = $xy[1];
					} else {
						$geocode = $this->curlRequest('https://maps.googleapis.com/maps/api/geocode/json?key=' . $settings['google_apikey'] . '&address=' . $store_address);
						if (empty($geocode['results'])) {
							sleep(1);
							$geocode = $this->curlRequest('https://maps.googleapis.com/maps/api/geocode/json?key=' . $settings['google_apikey'] . '&address=' . $store_address);
							if (empty($geocode['results'])) {
								$this->logMessage('The Google geocoding service returned the error "' . $geocode['status'] . '" for address "' . $store_address . '"');
								continue;
							}
						}
						$x1 = $geocode['results'][0]['geometry']['location']['lat'];
						$y1 = $geocode['results'][0]['geometry']['location']['lng'];
					}
					
					if (!empty($address['geocode'])) {
						$xy = explode(',', $address['geocode']);
						$x2 = $xy[0];
						$y2 = $xy[1];
					} else {
						$geocode = $this->curlRequest('https://maps.googleapis.com/maps/api/geocode/json?key=' . $settings['google_apikey'] . '&address=' . $customer_address);
						if (empty($geocode['results'])) {
							sleep(1);
							$geocode = $this->curlRequest('https://maps.googleapis.com/maps/api/geocode/json?key=' . $settings['google_apikey'] . '&address=' . $customer_address);
							if (empty($geocode['results'])) {
								$this->logMessage('The Google geocoding service returned the error "' . $geocode['status'] . '" for address "' . $customer_address . '"');
								continue;
							}
						}
						$x2 = $geocode['results'][0]['geometry']['location']['lat'];
						$y2 = $geocode['results'][0]['geometry']['location']['lng'];
					}
					
					$distance = rad2deg(acos(sin(deg2rad($x1)) * sin(deg2rad($x2)) + cos(deg2rad($x1)) * cos(deg2rad($x2)) * cos(deg2rad($y1 - $y2)))) * 60 * 114 / 99;
				}
				
				if (isset($settings['distance_units']) && $settings['distance_units'] == 'km') {
					$distance *= 1.609344;
				}
				$this->logMessage('Calculated distance between ' . $store_address . ' and ' . $customer_address . ' = ' . round($distance, 3) . ' ' . $settings['distance_units']);
			}
			
			if (isset($rules['distance'])) {
				$this->commaMerge($rules['distance']);
				
				foreach ($rules['distance'] as $comparison => $distances) {
					$in_range = $this->inRange($distance, $distances, 'distance' . ($comparison == 'not' ? ' not' : ''));
					
					if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
						continue 2;
					}
				}
			}
			
			// Calculate the charge
			$rate_found = false;
			$brackets = (!empty($charge['charges'])) ? array_filter(explode(',', str_replace(array("\n", ',,'), ',', $charge['charges']))) : array(0);
			
			if ($charge['type'] == 'flat') {
				
				$cost = (strpos($charge['charges'], '%')) ? $total * (float)$charge['charges'] / 100 : (float)$charge['charges'];
				
				if (strpos($charge['charges'], '}')) {
					$cost = preg_replace_callback('/\{([^\}]+)\}/', function ($matches) use ($replace, $with) {
						return @eval('return number_format(' . preg_replace('/[^\d\.\+\-\*\/\(\)]/', '', str_replace($replace, $with, $matches[1])) . ', ' . (strpos($matches[1], 'quantity') !== false ? '0' : '2') . ');');
					}, $charge['charges']);
				}

				$rate_found = true;
				
			} elseif ($charge['type'] == 'peritem') {
				
				$cost = (strpos($charge['charges'], '%')) ? $total * (float)$charge['charges'] / 100 : (float)$charge['charges'] * $quantity;
				$rate_found = true;
				
			} elseif ($charge['type'] == 'buy_x_for_y') {
				
				$prices = array();
				foreach ($cart_products as $product) {
					if (!in_array($product['key'], $product_keys)) continue;
					for ($i = 0; $i < $product['quantity']; $i++) {
						$prices[] = $product['price'];
					}
				}
				sort($prices);
				
				foreach ($brackets as $bracket) {
					$bracket = str_replace(array('::', ':', ' '), array('-', '=', ''), $bracket);
					$bracket_pieces = explode('=', $bracket);
					
					$from_and_to = explode('-', $bracket_pieces[0]);
					$from = (int)$from_and_to[0];
					$to = (isset($from_and_to[1])) ? (int)$from_and_to[1] : (int)$from_and_to[0];
					
					if ($from > $quantity) continue;
					
					$number_of_discounts = max(1, floor($quantity / $to));
					$qualifying_prices = array_slice($prices, 0, $number_of_discounts * $from);
					
					$cost = ($bracket_pieces[1] * $number_of_discounts) - array_sum($qualifying_prices);
					$rate_found = true;
				}
				
			} elseif ($charge['type'] == 'buy_x_get_y') {
				
				$prices = array();
				foreach ($cart_products as $product) {
					if (!in_array($product['key'], $product_keys)) continue;
					
					if (isset($rules['total_value']) && in_array('ignoreoptions', $rules['total_value'][''])) {
						$product_info = $this->model_catalog_product->getProduct($product['product_id']);
						$product_price = ($product_info['special']) ? $product_info['special'] : $product_info['price'];
					} else {
						$product_price = $product['price'];
					}
					
					for ($i = 0; $i < $product['quantity']; $i++) {
						$prices[] = $product_price;
					}
				}
				rsort($prices);
				
				$cost = 0;
				
				foreach ($brackets as $bracket) {
					$bracket = str_replace(array('::', ':', ' '), array('-', '=', ''), $bracket);
					$bracket_pieces = explode('=', $bracket);
					
					$from_and_to = explode('-', $bracket_pieces[0]);
					$from = (int)$from_and_to[0];
					$to = (isset($from_and_to[1])) ? (int)$from_and_to[1] : (int)$from_and_to[0];
					
					$items_and_percentage = explode('/', $bracket_pieces[1]);
					$items = (int)$items_and_percentage[0];
					$percentage = (float)$items_and_percentage[1] / 100;
					
					if (($from + $items) > $quantity) continue;
					
					$qualifying_items = $from + $items;
					$number_of_discounts = $items * max(1, floor($quantity / ($to + $items)));
					
					$indexes = array();
					$count = 0;
					$discounts_count = 0;
					
					while ($discounts_count < $number_of_discounts) {
						$count++;
						if ($count % $qualifying_items) continue;
						
						for ($i = 0; $i < $items; $i++) {
							$indexes[] = $count - $items;
							$count++;
							$discounts_count++;
						}
					}
					
					foreach ($prices as $index => $price) {
						if (!in_array($index, $indexes)) continue;
						$cost += $price * abs($percentage);
					}
					
					$rate_found = true;
				}
				
				$cost = -$cost;
				
			} elseif ($charge['type'] == 'price') {
				
				$cost = 0;
				
				foreach ($cart_products as $product) {
					if (!in_array($product['key'], $product_keys)) continue;
					
					$product_cost = $this->calculateBrackets($brackets, $charge['type'], $product['price'], $product['quantity'], $product['price']);
					
					if ($product_cost !== false) {
						$cost += $product_cost * $product['quantity'];
						$rate_found = true;
					}
				}
				
			} elseif (in_array($charge['type'], array('distance', 'postcode', 'product_count', 'quantity', 'shipping_cost', 'total', 'volume', 'weight'))) {
				
				$percentage_total = ($charge['type'] == 'shipping_cost') ? $shipping_cost : $total;
				$cost = $this->calculateBrackets($brackets, $charge['type'], ${$charge['type']}, $quantity, $percentage_total);
				
				if ($cost !== false) {
					$rate_found = true;
				}
				
			}
			
			if (!empty($other_product_data_charges)) {
				$cost = array_sum($other_product_data_charges);
				$rate_found = true;
			}
			
			if (!$rate_found) {
				$this->logMessage('"' . $this->charge['title'] . '" disabled because the value "' . (isset(${$charge['type']}) ? ${$charge['type']} : '') . '" does not match any of the brackets "' . implode(', ', $brackets) . '"');
				continue;
			}
			
			// Adjust charge
			if (isset($rules['adjust']['charge'])) {
				foreach ($rules['adjust']['charge'] as $adjustment) {
					$cost += (strpos($adjustment, '%')) ? $cost * (float)$adjustment / 100 : (float)$adjustment;
				}
			}
			if (isset($rules['round'])) {
				foreach ($rules['round'] as $comparison => $values) {
					$round = $values[0];
					if ($comparison == 'nearest') {
						$cost = round($cost / $round) * $round;
					} elseif ($comparison == 'up') {
						$cost = ceil($cost / $round) * $round;
					} elseif ($comparison == 'down') {
						$cost = floor($cost / $round) * $round;
					}
				}
			}
			if (isset($rules['min'])) {
				$cost = max($cost, $rules['min'][''][0]);
			}
			if (isset($rules['max'])) {
				$cost = min($cost, $rules['max'][''][0]);
			}
			if ($single_foreign_currency) {
				$cost = $this->currency->convert($cost, $single_foreign_currency, $main_currency);
			}
			
			// Add to charge array
			$this->logMessage('ENABLED "' . $this->charge['title'] . '" with cost ' . (float)$cost);
			
			$replace = array('[distance]', '[postcode]', '[quantity]', '[total]', '[volume]', '[weight]');
			$with = array(round($distance, 2), $postcode, round($quantity, 2), number_format($total, 2), round($volume, 2), round($weight, 2));
			
			if (isset($rules['tax_class'])) {
				$tax_class_id = ($rules['tax_class'][''][0] == 'highest') ? $highest_tax_class : $rules['tax_class'][''][0];
			} elseif ($settings['tax_class_id'] == 'highest') {
				$tax_class_id = $highest_tax_class;
			} else {
				$tax_class_id = $settings['tax_class_id'];
			}
			
			// extension-specific
			$cost = (float)$cost;
			if ($cost > 0) $cost = -$cost;
				
			$taxable_total = 0;
			foreach ($cart_products as $product) {
				if (in_array($product['key'], $product_keys) && $product['tax_class_id'] == $tax_class_id) {
					$taxable_total += $product['total'];
				}
			}
			// end
			
			$charges[strtolower($charge['group'])][] = array(
				'title'			=> str_replace($replace, $with, html_entity_decode($charge['title_' . $language], ENT_QUOTES, 'UTF-8')),
				'charge'		=> $cost,
				'tax_class_id'	=> $tax_class_id,
				'taxable_cost'	=> ($taxable_total / $this->cart->getSubTotal()) * $cost, // extension-specific
			);
			
			if ($this->type != 'shipping') {
				$cumulative_total_value += (float)$cost;
			}
			
			// Restore setting defaults
			foreach ($defaults as $key => $value) {
				$this->config->set($key, $value);
			}
			
			// Coupon code is valid
			if (isset($this->session->data['temp_coupon'])) {
				return !empty($cost);
			}
			
		} // end charge loop
		
		// Combine charges
		$quote_data = array();
		
		foreach ($charges as $group_value => $group) {
			foreach ($group as $rate) {
				if (($this->type == 'shipping' && $rate['charge'] < 0) || ($this->type == 'total' && $rate['charge'] == 0)) continue;
				
				$taxed_charge = $this->tax->calculate($rate['charge'], $rate['tax_class_id'], $this->config->get('config_tax'));
				
				$quote_data[$this->name . '_' . count($quote_data)] = array(
					'code'			=> $this->name . '.' . $this->name . '_' . count($quote_data),
					'sort_order'	=> $group_value,
					'title'			=> $rate['title'] . ' (' . $coupon . ')',
					'cost'			=> $rate['charge'],
					'value'			=> $rate['charge'],
					'tax_class_id'	=> $rate['tax_class_id'],
					'taxable_cost'	=> $rate['taxable_cost'], // extension-specific
					'text'			=> $this->currency->format($this->type == 'total' ? $rate['charge'] : $taxed_charge, $currency),
				);
			}
		}
		
		$sort_order = array();
		foreach ($quote_data as $key => $value) $sort_order[$key] = $value['sort_order'];
		array_multisort($sort_order, SORT_ASC, $quote_data);
		
		foreach ($quote_data as $quote) {
			$quote['code'] = $this->name;
			$quote['sort_order'] = $settings['sort_order'];
			
			$total_data[] = $quote;
			
			if ($quote['tax_class_id']) {
				foreach ($this->tax->getRates($quote['taxable_cost'], $quote['tax_class_id']) as $tax_rate) { // extension-specific
					$taxes[$tax_rate['tax_rate_id']] = (isset($taxes[$tax_rate['tax_rate_id']])) ? $taxes[$tax_rate['tax_rate_id']] + $tax_rate['amount'] : $tax_rate['amount'];
				}
			}
			
			$order_total += $quote['cost'];
		}
		
		} // end coupon loop
		
		if ($this->type == 'shipping' && $quote_data) {
			$replace = array('[distance]', '[postcode]', '[quantity]', '[total]', '[volume]', '[weight]');
			$with = array(round($distance, 2), $postcode, round($cart_quantity, 2), round($cart_total, 2), round($cart_volume, 2), round($cart_weight, 2));
			
			return array(
				'code'			=> $this->name,
				'title'			=> str_replace($replace, $with, html_entity_decode($settings['heading_' . $language], ENT_QUOTES, 'UTF-8')),
				'quote'			=> $quote_data,
				'sort_order'	=> $settings['sort_order'],
				'error'			=> false
			);
		} else {
			return array();
		}
	}
	
	//==============================================================================
	// Private functions
	//==============================================================================
	private function getSettings() {
		$code = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name;
		
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `key` ASC");
		
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
		
		return $settings;
	}
	
	private function curlRequest($url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 3);
		$response = json_decode(curl_exec($curl), true);
		curl_close($curl);
		return $response;
	}
	
	private function logMessage($message) {
		if ($this->testing_mode) {
			file_put_contents(DIR_LOGS . $this->name . '.messages', print_r($message, true) . "\n", FILE_APPEND|LOCK_EX);
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
	
	private function ruleViolation($rule, $value) {
		$violation = false;
		$rules = $this->charge['rules'];
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
		
		if ($violation) {
			$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "' . $rule . ' ' . $comparison . ' ' . implode(', ', $rules[$rule][$comparison]) . '" with value "' . (is_array($value) ? implode(',', $value) : $value) . '"');
		}
		
		return $violation;
	}
	
	private function inRange($value, $range_list, $charge_type = '', $skip_testing = false) {
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
				if ($charge_type != 'attribute' && $charge_type != 'option' && $charge_type != 'other product data' && !isset($range[1])) {
					$range[1] = 999999999;
				}
				
				if ((count($range) > 1 && $value >= $range[0] && $value <= $range[1]) || (count($range) == 1 && $value == $range[0])) {
					$in_range = true;
				}
			}
		}
		
		if (!$skip_testing) {
			if (strpos($charge_type, ' not') ? $in_range : !$in_range) {
				$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "' . $charge_type . (strpos($charge_type, ' not') ? ' ' : ' is ') . implode(', ', $range_list) . '" with value "' . $value . '"');
			}
		}
		
		return $in_range;
	}
	
	private function calculateBrackets($brackets, $charge_type, $comparison_value, $quantity, $total) {
		$to = 0;
		
		foreach ($brackets as $bracket) {
			$bracket = str_replace(array('::', ':'), array('~', '='), $bracket);
			
			$bracket_pieces = explode('=', $bracket);
			if (count($bracket_pieces) == 1) {
				array_unshift($bracket_pieces, ($charge_type == 'postcode') ? '0-ZZZZ' : '0-999999');
			}
			
			$from_and_to = (strpos($bracket_pieces[0], '~')) ? explode('~', $bracket_pieces[0]) : explode('-', $bracket_pieces[0]);
			if (count($from_and_to) == 1) {
				array_unshift($from_and_to, ($charge_type == 'postcode') ? $from_and_to[0] : $to);
			}
			$from = trim($from_and_to[0]);
			$to = trim($from_and_to[1]);
			
			$cost_and_per = explode('/', $bracket_pieces[1]);
			$per = (isset($cost_and_per[1])) ? (float)$cost_and_per[1] : 0;
			
			$top = min($to, $comparison_value);
			$bottom = (isset($this->charge['rules']['cumulative'])) ? $from : 0;
			$difference = ($charge_type == 'postcode' || $charge_type == 'price') ? $quantity : $top - $bottom;
			$multiplier = ($per) ? ceil($difference / $per) : 1;
			
			if (!isset($cost) || !isset($this->charge['rules']['cumulative'])) {
				$cost = 0;
			}
			$cost += (strpos($cost_and_per[0], '%')) ? (float)$cost_and_per[0] * $multiplier * $total / 100 : (float)$cost_and_per[0] * $multiplier;
			
			$in_range = $this->inRange($comparison_value, array($from . '::' . $to), $charge_type, true);
			if ($in_range) {
				return $cost;
			}
		}
		
		return false;
	}
	
	private function getChildCategoryIds($parent_id) {
		$child_ids = array();
		$child_categories = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = " . (int)$parent_id)->rows;
		foreach ($child_categories as $child_category) {
			$child_ids[] = $child_category['category_id'];
			$child_ids = array_merge($child_ids, $this->getChildCategoryIds($child_category['category_id']));
		}
		return array_unique($child_ids);
	}
	
	//==============================================================================
	// Coupon functions
	//==============================================================================
	public function confirm($order_info, $order_total) {
		$start = strrpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');
		$coupon = ($start && $end) ? substr($order_total['title'], $start, $end - $start) : '';
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_history SET coupon_id = '" . $this->db->escape($coupon) . "', order_id = " . (int)$order_info['order_id'] . ", customer_id = " . (int)$order_info['customer_id'] . ", amount = " . (float)$order_total['value'] . ", date_added = NOW()");
		
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		if ($this->config->get($prefix . $this->name . '_delete_used')) {
			$code = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name;
			
			$coupon_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE UCASE(`value`) = '" . $this->db->escape($coupon) . "'");
			$key_parts = explode('_', $coupon_query->row['key']);
			$correct_key = $key_parts[version_compare(VERSION, '3.0', '<') ? 3 : 4];
			
			$uses_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = '" . $this->db->escape($code . '_charge_' . $correct_key . '_uses_per_coupon') . "'");
			$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . $this->db->escape($coupon) . "'");
			
			if ($uses_query->num_rows && $coupon_history_query->num_rows && (int)$uses_query->row['value'] > 0 && (int)$uses_query->row['value'] <= (int)$coupon_history_query->row['total']) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` LIKE '" . $this->db->escape($code . '_charge_' . $correct_key) . "_%'");
			}
		}
	}

	public function unconfirm($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE order_id = " . (int)$order_id);
	}
}
?>