<?php
//==============================================================================
// TaxCloud Integration v303.5
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ModelExtensionTotalTaxcloudIntegration extends Model {
	private $type = 'total';
	private $name = 'taxcloud_integration';
	
	public function getTotal($total_input) {
		$total_data = &$total_input['totals'];
		$order_total = &$total_input['total'];
		$taxes = &$total_input['taxes'];
		
		$settings = $this->getSettings();
		$language = (isset($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		
		// Set address info
		$addresses = array();
		$this->load->model('account/address');
		foreach (array('shipping', 'payment') as $address_type) {
			if (empty($address) || $address_type == 'payment') {
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
			
			if (empty($address['address_1']))	$address['address_1'] = '';
			if (empty($address['address_2']))	$address['address_2'] = '';
			if (empty($address['city']))		$address['city'] = '';
			if (empty($address['postcode']))	$address['postcode'] = '';
			if (empty($address['country_id']))	$address['country_id'] = '';
			if (empty($address['zone_id']))		$address['zone_id'] = '';
				
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
		
		// Check order criteria
		$address = $addresses[$this->cart->hasShipping() ? 'shipping' : 'payment'];
		
		if (!$settings['status'] ||	
			empty($address['iso_code_2']) ||
			$address['iso_code_2'] != 'US' || 
			!$this->cart->hasProducts() ||
			!array_intersect(array($this->config->get('config_store_id')), explode(';', $settings['stores'])) ||
			!array_intersect($address['geo_zones'], explode(';', $settings['geo_zones'])) ||
			!array_intersect(array((int)$this->customer->getGroupId()), explode(';', $settings['customer_groups']))
		) {
			return;
		}
		
		// Find fallback tax rate
		$fallback_tax_rate = 0;
		
		foreach ($address['geo_zones'] as $geo_zone_id) {
			if (!empty($settings['fallback'][$geo_zone_id])) {
				$fallback_tax_rate = (float)$settings['fallback'][$geo_zone_id];
				break;
			}
		}
		
		// Check pre-checkout page settings
		$route = (isset($this->request->get['route'])) ? explode('/', $this->request->get['route']) : array('common', 'home');
		
		if ($route[1] == 'cart' || (strpos($route[0], 'checkout') === false && strpos($route[1], 'checkout') === false)) {
			if ($settings['precheckout_pages'] == 'hide' || ($settings['precheckout_pages'] == 'fallback' && empty($fallback_tax_rate))) {
				return;
			} elseif ($settings['precheckout_pages'] == 'fallback') {
				$tax_amount = $order_total * (float)$fallback_tax_rate / 100;
				
				$total_data[] = array(
					'code'			=> $this->name,
					'title'			=> html_entity_decode(str_replace(array('[zipcode]', '[postcode]', '[state]'), array($address['postcode'], $address['postcode'], $address['zone']), $settings['title_' . $language]), ENT_QUOTES, 'UTF-8'),
					'text'			=> $this->currency->format($tax_amount, $this->session->data['currency']),
					'value'			=> $tax_amount,
					'sort_order'	=> $settings['sort_order'],
				);
				
				$order_total += $tax_amount;
				
				return;
			}
		}
		
		/*
		// Check for custom field
		$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$this->customer->getId())->row;
		$custom_fields = json_decode($customer['custom_field'], true);
		if (!empty($custom_fields[1])) {
			return;
		}
		
		if (!empty($address['custom_field'][1])) {
			return;
		}
		*/
		
		// Set store address
		$store_address = array(
			'Address1'	=> $settings['address1'],
			'Address2'	=> $settings['address2'],
			'City'		=> $settings['city'],
			'State'		=> $settings['state'],
			'Zip5'		=> $settings['zip5'],
			'Zip4'		=> $settings['zip4'],
		);
		
		// Set customer address
		$zip_code = preg_replace('/[^0-9]/', '', $address['postcode']);
		$zip4 = substr($zip_code, 5);
		
		$customer_address = array(
			'Address1'		=> $address['address_1'],
			'City'			=> $address['city'],
			'State'			=> $address['zone_code'],
			'Zip5'			=> substr($zip_code, 0, 5),
		);
		
		if (!empty($address['address_2']))	$customer_address['Address2'] = $address['address_2'];
		if (!empty($zip4))					$customer_address['Zip4'] = $zip4;
		
		// Verify customer address
		if (empty($customer_address['Zip5'])) {
			$customer_address = $store_address;
		} else {
			if (empty($customer_address['City']) && !empty($settings['google_apikey'])) {
				$geocode = $this->simpleCurlRequest('https://maps.googleapis.com/maps/api/geocode/json?key=' . $settings['google_apikey'] . '&address=' . preg_replace('/\s+/', '+', implode('+', $customer_address)));
				if (!empty($geocode['results'][0])) {
					foreach ($geocode['results'][0]['address_components'] as $address_component) {
						if (in_array('locality', $address_component['types']) || in_array('sublocality', $address_component['types'])) {
							$customer_address['City'] = $address_component['long_name'];
							break;
						}
					}
				}
			}
			
			if (!empty($customer_address['City']) && $settings['usps_id']) {
				$response = $this->curlRequest('VerifyAddress', array_merge(array('UspsUserID' => $settings['usps_id']), $customer_address));
				if (!empty($response) && $response['ErrNumber'] == 0) {
					$customer_address = $response;
				}
			}
		}
		
		// Calculate fee/discount adjustment
		$adjustment = 0;
		
		foreach ($total_data as $line_item) {
			if ($line_item['code'] == 'sub_total' || $line_item['code'] == 'intermediate_order_total' || $line_item['code'] == 'shipping') continue;
			$adjustment += $line_item['value'];
		}
		
		// Set up cart array
		$cart_products = $this->cart->getProducts();
		
		$total_of_all_products = 0;
		foreach ($cart_products as $product) {
			$total_of_all_products += $product['total'];
		}
		
		$cart = array(
			'CustomerID'		=> (int)$this->customer->getId(),
			'CartID'			=> session_id(),
			'CartItems'			=> array(),
			'Origin'			=> $store_address,
			'Destination'		=> $customer_address,
			'DeliveredBySeller'	=> false,
			'ExemptCert'		=> null,
		);
		
		$product_totals = array();
		
		foreach ($cart_products as $product) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = " . (int)$product['product_id']);
			$tic = ($settings['tic_field'] && !empty($product_query->row[$settings['tic_field']])) ? $product_query->row[$settings['tic_field']] : '';
			
			$index = count($cart['CartItems']);
			$individual_adjustment = ($adjustment / $product['quantity']) * ($product['total'] / $total_of_all_products);
			$product_totals[$index] = $product['total'] + $individual_adjustment * $product['quantity'];
			
			$cart['CartItems'][] = array(
				'Index'		=> $index,
				'TIC'		=> $tic,
				'ItemID'	=> substr(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'), 0, 50),
				'Price'		=> round($product['price'] + $individual_adjustment, 2),
				'Qty'		=> $product['quantity'],
			);
		}
		
		// Determine shipping cost
		$shipping_cost = 0;
		
		if (!empty($this->request->post['order_total'])) {
			foreach ($this->request->post['order_total'] as $line_item) {
				if ($line_item['code'] == 'shipping') {
					$shipping_cost = $line_item['value'];
				}
			}
		} else {
			if (!empty($this->session->data['shipping_method']['cost'])) {
				$shipping_cost = $this->session->data['shipping_method']['cost'];
			}
		}
		
		if ($shipping_cost) {
			$shipping_title = (isset($this->session->data['shipping_method']['title'])) ? $this->session->data['shipping_method']['title'] : 'Shipping';
			$product_totals[count($cart['CartItems'])] = $shipping_cost;
			
			$cart['CartItems'][] = array(
				'Index'		=> count($cart['CartItems']),
				'TIC'		=> '11010',
				'ItemID'	=> substr(html_entity_decode($shipping_title, ENT_QUOTES, 'UTF-8'), 0, 50),
				'Price'		=> $shipping_cost,
				'Qty'		=> 1,
			);
		}
		
		if (empty($cart['CartItems'])) {
			return;
		}
		
		// Determine tax rates for each TIC
		$tax_amount = 0;
		
		$response = $this->curlRequest('Lookup', $cart);
		
		if (empty($response) || $response['ResponseType'] == 0) {
			if (!empty($response['Messages'])) {
				foreach($response['Messages'] as $message) {
					$this->log->write('Tax retrieval error #' . $message['ResponseType'] . ': ' . $message['Message']);
				}
			}
			// Use fallback tax rate
			foreach ($product_totals as $index => $product_total) {
				$tax_amount += $product_total * (float)$fallback_tax_rate / 100;
			}
		} else {
			foreach ($response['CartItemsResponse'] as $item) {
				$tax_amount += $item['TaxAmount'];
			}
		}
		
		// Set $total_data
		if (empty($tax_amount)) return;
		
		$tax_amount = round($tax_amount, 2);
		
		$total_data[] = array(
			'code'			=> $this->name,
			'title'			=> html_entity_decode(str_replace(array('[zipcode]', '[postcode]', '[state]'), array($address['postcode'], $address['postcode'], $address['zone']), $settings['title_' . $language]), ENT_QUOTES, 'UTF-8'),
			'text'			=> $this->currency->format($tax_amount, $this->session->data['currency']),
			'value'			=> $tax_amount,
			'sort_order'	=> $settings['sort_order'],
		);
		
		$order_total += $tax_amount;
	}
	
	//==============================================================================
	// authorizeAndCapture()
	//==============================================================================
	public function authorizeAndCapture($order_info, $order_status_id, $products) {
		$settings = $this->getSettings();
		
		if (!$settings['status']) {
			return 'The TaxCloud Integration extension is not enabled.';
		}
		
		if (!array_intersect(array((int)$this->config->get('config_store_id')), explode(';', $settings['stores']))) {
			return 'The store for this order does not meet the store requirements set up in the TaxCloud Integration extension.';
		}
		
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$order_info['customer_id']);
		if (!empty($customer_query->row['customer_group_id']) && !array_intersect(array((int)$customer_query->row['customer_group_id']), explode(';', $settings['customer_groups']))) {
			return 'The customer group for this order does not meet the customer group requirements set up in the TaxCloud Integration extension.';
		}
		
		$address_type = (!empty($order_info['shipping_country_id'])) ? 'shipping' : 'payment';
		
		$geo_zones = array();
		$geo_zones_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = " . (int)$order_info[$address_type . '_country_id'] . " AND (zone_id = 0 OR zone_id = " . (int)$order_info[$address_type . '_zone_id'] . ")");
		foreach ($geo_zones_query->rows as $geo_zone) {
			$geo_zones[] = $geo_zone['geo_zone_id'];
		}
		$geo_zone_settings = explode(';', $settings['geo_zones']);
		if ((!empty($geo_zones) && !array_intersect($geo_zones, $geo_zone_settings)) || (empty($geo_zones) && !in_array(0, $geo_zone_settings))) {
			return 'The address for this order does not meet the geo zone requirements set up in the TaxCloud Integration extension.';
		}
		
		// Set store address
		$store_address = array(
			'Address1'	=> $settings['address1'],
			'Address2'	=> $settings['address2'],
			'City'		=> $settings['city'],
			'State'		=> $settings['state'],
			'Zip5'		=> $settings['zip5'],
			'Zip4'		=> $settings['zip4'],
		);
		
		// Verify address
		if ($order_info[$address_type . '_iso_code_2'] != 'US') {
			return 'Error: customer is not within the United States';
		}
		
		$zip_code = preg_replace('/[^0-9]/', '', $order_info[$address_type . '_postcode']);
		$zip4 = substr($zip_code, 5);
		
		$customer_address = array(
			'UspsUserID'	=> $settings['usps_id'],
			'Address1'		=> $order_info[$address_type . '_address_1'],
			'City'			=> $order_info[$address_type . '_city'],
			'State'			=> $order_info[$address_type . '_zone_code'],
			'Zip5'			=> substr($zip_code, 0, 5),
		);
		if (!empty($order_info[$address_type . '_address_2']))	$customer_address['Address2'] = $order_info[$address_type . '_address_2'];
		if (!empty($zip4))										$customer_address['Zip4'] = $zip4;
		
		if (!empty($customer_address['Address1']) && !empty($customer_address['City']) && !empty($customer_address['State']) && !empty($customer_address['Zip5'])) {
			if ($settings['usps_id']) {
				$response = $this->curlRequest('VerifyAddress', $customer_address);
				if ($response['ErrNumber'] == 0) {
					$customer_address = $response;
				} else {
					array_shift($customer_address);
				}
			}
		} else {
			array_shift($customer_address);
			return 'Error: customer address is missing information:' . "\n\n" . print_r($customer_address, true);
		}
		
		// Set up cart array
		$total_of_all_products = 0;
		foreach ($products as $product) {
			$total_of_all_products += $product['total'];
		}
		
		$cart = array(
			'CustomerID'		=> $order_info['customer_id'],
			'CartID'			=> $order_info['order_id'],
			'CartItems'			=> array(),
			'Origin'			=> $store_address,
			'Destination'		=> $customer_address,
			'DeliveredBySeller'	=> false,
			'ExemptCert'		=> null,
		);
		
		// Calculate fee/discount adjustment
		$adjustment = 0;
		
		$order_totals = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_info['order_id'] . " ORDER BY sort_order")->rows;
		foreach ($order_totals as $line_item) {
			if ($line_item['code'] == $this->name || $line_item['code'] == 'tax' || $line_item['code'] == 'taxjar_integration' || $line_item['code'] == 'avalara_integration') {
				break;
			}
			
			if ($line_item['code'] == 'sub_total' || $line_item['code'] == 'intermediate_order_total' || $line_item['code'] == 'shipping') {
				continue;
			}
			
			if ($line_item['code'] == 'shipping') {
				$cart['CartItems'][] = array(
					'Index'		=> count($cart['CartItems']),
					'TIC'		=> '11010',
					'ItemID'	=> substr(html_entity_decode($line_item['title'], ENT_QUOTES, 'UTF-8'), 0, 50),
					'Price'		=> $line_item['value'],
					'Qty'		=> 1,
				);
				continue;
			}
			
			$adjustment += $line_item['value'];
		}
		
		// Add cart products
		foreach ($products as $product) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = " . (int)$product['product_id']);
			
			$tic = ($settings['tic_field'] && !empty($product_query->row[$settings['tic_field']])) ? $product_query->row[$settings['tic_field']] : '';
			$individual_adjustment = ($adjustment / $product['quantity']) * ($product['total'] / $total_of_all_products);
			
			$cart['CartItems'][] = array(
				'Index'		=> count($cart['CartItems']),
				'TIC'		=> $tic,
				'ItemID'	=> substr(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'), 0, 50),
				'Price'		=> round($product['price'] + $individual_adjustment, 2),
				'Qty'		=> $product['quantity'],
			);
		}
		
		if (empty($cart['CartItems'])) {
			return 'Order does not have any taxable items';
		}
		
		// Send cart data
		$response = $this->curlRequest('Lookup', $cart);
		
		if (empty($response) || $response['ResponseType'] == 0) {
			if (!empty($response['Messages'])) {
				foreach ($response['Messages'] as $message) {
					$this->log->write('Tax retrieval error #' . $message['ResponseType'] . ': ' . $message['Message']);
				}
			}
			return;
		}
		
		// Authorize and capture order
		$order = array(
			'CustomerID'		=> $order_info['customer_id'],
			'CartID'			=> $order_info['order_id'],
			'OrderID'			=> $order_info['order_id'],
			'DateAuthorized'	=> gmdate(DATE_ATOM),
			'DateCaptured'		=> gmdate(DATE_ATOM),
		);
		
		$messages = '';
		$response = $this->curlRequest('AuthorizedWithCapture', $order);
		
		if (empty($response) || $response['ResponseType'] == 0) {
			if (!empty($response['Messages'])) {
				foreach($response['Messages'] as $message) {
					$messages .= $message['Message'] . "\n\n";
					$this->log->write('Order submission error #' . $message['ResponseType'] . ': ' . $message['Message']);
				}
			} else {
				$messages = 'Curl Error: Empty gateway response';
			}
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = " . (int)$order_info['order_id'] . ", order_status_id = " . (int)$order_status_id . ", notify = 0, comment = 'Submitted to TaxCloud (" . date('r') . ")', date_added = NOW()");
		}
		
		return $messages;
	}
	
	//==============================================================================
	// returnOrder()
	//==============================================================================
	public function returnOrder($order_id, $indexes = array()) {
		$settings = $this->getSettings();
		
		$order_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = " . (int)$order_id)->row;
		$order_products = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = " . (int)$order_id . " ORDER BY order_product_id ASC")->rows;
		
		$cart_items = array();
		$returned_products = array();
		
		foreach ($indexes as $index) {
			if (isset($order_products[$index - 1])) {
				$product = $order_products[$index - 1];
				$returned_products[] = $product['name'];
				
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = " . (int)$product['product_id']);
				$tic = ($settings['tic_field'] && !empty($product_query->row[$settings['tic_field']])) ? $product_query->row[$settings['tic_field']] : '';
				
				$cart_items[] = array(
					'Index'		=> $index - 1,
					'TIC'		=> $tic,
					'ItemID'	=> substr(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'), 0, 50),
					'Price'		=> $product['price'],
					'Qty'		=> $product['quantity'],
				);
			} else {
				$shipping_line_item = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_id . " AND `code` = 'shipping'")->row;
				$returned_products[] = $shipping_line_item['title'];
				
				$cart_items[] = array(
					'Index'		=> count($order_products),
					'TIC'		=> '11010',
					'ItemID'	=> substr(html_entity_decode($shipping_line_item['title'], ENT_QUOTES, 'UTF-8'), 0, 50),
					'Price'		=> $shipping_line_item['value'],
					'Qty'		=> 1,
				);
			}
		}
		
		$order = array(
			'OrderID'		=> $order_id,
			'CartItems'		=> $cart_items,
			'ReturnedDate'	=> gmdate(DATE_ATOM),
		);
		
		$messages = '';
		$response = $this->curlRequest('Returned', $order);
		
		if (empty($response) || $response['ResponseType'] == 0) {
			if (!empty($response['Messages'])) {
				foreach($response['Messages'] as $message) {
					$messages .= $message['Message'] . "\n\n";
				}
			} else {
				$messages = 'Curl Error: Empty gateway response';
			}
		} else {
			$returned_text = (!empty($returned_products)) ? '\"' . implode(', ', $returned_products) . '\"' : 'entire order';
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = " . (int)$this->request->get['order_id'] . ", order_status_id = " . (int)$order_info['order_status_id'] . ", notify = 0, comment = 'Returned " . $returned_text . " in TaxCloud (" . date('r') . ")', date_added = NOW()");
		}
		
		return $messages;
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
	
	private function logMessage($message) {
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		if ($this->config->get($prefix . $this->name . '_testing_mode')) {
			file_put_contents(DIR_LOGS . $this->name . '.messages', print_r($message, true) . "\n\n", FILE_APPEND|LOCK_EX);
		}
	}
	
	private function simpleCurlRequest($url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 3);
		$response = json_decode(curl_exec($curl), true);
		curl_close($curl);
		return $response;
	}
	
	private function curlRequest($api, $data) {
		// Check for cached data
		$data_for_hashing = $data;
		$hash = md5(json_encode($data_for_hashing));
		
		if (!empty($this->session->data[$this->name][$api][$hash])) {
			return $this->session->data[$this->name][$api][$hash];
		}
		
		// Start testing mode messages
		$this->logMessage("\n" . '------------------------------ Starting Test ' . date('Y-m-d G:i:s') . ' ------------------------------');
		$this->logMessage('DATA SENT (' . $api . ' API): ' . print_r($data, true));
		
		// Execute curl request
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		$data['ApiLoginID'] = $this->config->get($prefix . $this->name . '_api_id');
		$data['ApiKey'] = $this->config->get($prefix . $this->name . '_api_key');
		
		$curl = curl_init('https://api.taxcloud.net/1.0/Taxcloud/' . $api);
		curl_setopt_array($curl, array(
			CURLOPT_CONNECTTIMEOUT	=> 10,
			CURLOPT_FORBID_REUSE	=> true,
			CURLOPT_FRESH_CONNECT	=> true,
			CURLOPT_HTTPHEADER		=> array('Content-Type: application/json'),
			CURLOPT_POST			=> true,
			CURLOPT_POSTFIELDS		=> json_encode($data),
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_TIMEOUT			=> 30
		));
		$response = json_decode(curl_exec($curl), true);
		
		if (empty($response)) {
			$this->logMessage('Curl Error: Empty gateway response');
		} elseif (curl_error($curl)) {
			$this->logMessage('Curl Error #' . curl_errno($curl) . ': ' . curl_error($curl));
		}
		
		curl_close($curl);
		
		// End testing mode messages
		$this->logMessage('DATA RECEIVED: ' . print_r($response, true));
		
		// Set cached data
		if (empty($response)) $response = array();
		
		$this->session->data[$this->name][$api][$hash] = $response;
		
		// Return
		return $response;
	}
}
?>