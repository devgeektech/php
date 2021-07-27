<?php
class ModelAccountAddress extends Model {
	
	            // Query modified by Extendons - Checkout Manager
	            	public function addAddress($customer_id, $data) {

						$this->db->query("INSERT INTO " . DB_PREFIX . "address SET 
							customer_id = '" . (int)$customer_id . "', firstname = '" . (isset($data['firstname']) ? $this->db->escape($data['firstname']) : '') . "', lastname = '" . (isset($data['lastname']) ? $this->db->escape($data['lastname']) : '') . "', company = '" . (isset($data['company']) ? $this->db->escape($data['company']) : '') . "', address_1 = '" . (isset($data['address_1']) ? $this->db->escape($data['address_1']) : '') . "', address_2 = '" . (isset($data['address_2']) ? $this->db->escape($data['address_2']) : '') . "', postcode = '" . (isset($data['postcode']) ? $this->db->escape($data['postcode']) : '') . "', city = '" . (isset($data['city']) ? $this->db->escape($data['city']) : '') . "', zone_id = '" . (isset($data['zone_id']) ? (int)$data['zone_id'] : '') . "', country_id = '" . (isset($data['country_id']) ? (int)$data['country_id'] : '') . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "'");

						$address_id = $this->db->getLastId();

						if (!empty($data['default'])) {
							$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
						}

						return $address_id;
					}
				// Extendons - Checkout Manager /- End
		    	

	
            	// Update Query modified by Extendons - Checkout Manager
	            	public function editAddress($address_id, $data) {
// Clear Thinking: MailChimp Integration
				if (!empty($data['default']) && $this->customer->getNewsletter()) {
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->send(array_merge($data, array('newsletter' => 1, 'customer_id' => $this->customer->getId())));
				}
				// end
						$sql = "UPDATE " . DB_PREFIX . "address SET ";

						foreach ($data as $field_name => $value) {
							if ($field_name != 'default') {
								if ($field_name != 'billing_address') {
									if (isset($value) && !empty($value)) {
										$sql .= "`" . $field_name . "` = '" . $this->db->escape($value) . "', ";
									}
								}
							}
						}

						$sql .= "custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'";

						$this->db->query($sql);

						if (!empty($data['default'])) {
							$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
						}
					}
				// Extendons - Checkout Manager /- End
		    	

	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

            	// Extendons - Checkout Manager
	            	$check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `code` = 'checkout_manager'");

			        if ($check->num_rows) {
		            	$custom_data_q = $this->db->query("SELECT db_data_id FROM " . DB_PREFIX . "extendons_checkout_fields_data WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

						$db_data_id = $custom_data_q->row['db_data_id'];

						$this->db->query("DELETE FROM " . DB_PREFIX . "extendons_checkout_fields_data_columns  WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `db_data_id` = '" . $db_data_id . "' ");

						$this->db->query("DELETE FROM " . DB_PREFIX . "extendons_checkout_fields_data  WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' ");
			        }
				// Extendons - Checkout Manager /- End
		    	
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}


            	// Extendons - Checkout Manager
	            	$check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `code` = 'checkout_manager'");

			        if ($check->num_rows) {
	            		$getIDofCustomAddress = $this->db->query("SELECT db_data_id FROM " . DB_PREFIX . "extendons_checkout_fields_data WHERE address_id = '" . $address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

						if ($getIDofCustomAddress->num_rows) 
						{
							// my custom address id
							$my_custom_address_id = $getIDofCustomAddress->row['db_data_id'];

							// get all input fields with status = 1
							$getAllFieldsID = $this->db->query("SELECT db_field_id FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 ORDER BY field_sort_order");
							
							// loop through all input fields
							foreach ($getAllFieldsID->rows as $value)
							{
								$db_field_id = $value['db_field_id'];

								// get single input field using its id
								$getSingleFieldByID = $this->db->query("SELECT * FROM " . DB_PREFIX . "extendons_checkout_fields WHERE status = 1 AND db_field_id = " . $db_field_id);
								
								$field_to_show = @unserialize($getSingleFieldByID->row['field_to_show']);
								$field_visibility = @unserialize($getSingleFieldByID->row['field_visibility']);

								if ($field_to_show == true) {
									$getSingleFieldByID->row['field_to_show'] = $field_to_show;
								}
								if ($field_visibility == true) {
									$getSingleFieldByID->row['field_visibility'] = $field_visibility;
								}

								// if field has options
								$getSingleFieldByID_options = $this->db->query("SELECT * FROM " . DB_PREFIX . "extendons_checkout_fields_options WHERE db_field_id = " . $db_field_id);
								
								$getSingleFieldByID->row['field_options'] = array();

								// if field_options exists
								if ($getSingleFieldByID_options->num_rows) {
									$getSingleFieldByID->row['field_options'] = $getSingleFieldByID_options->rows;
								}
								
								// get data of all input fields from both table using left join where id=field_id and data_id=the id which is coming from data table ($my_custom_address_id)
								$custom_address_query2 = $this->db->query("SELECT * FROM
														" . DB_PREFIX . "extendons_checkout_fields_data ecfd
														WHERE ecfd.db_data_id = " . $my_custom_address_id);

								$custom_address_meta_q = $this->db->query("SELECT * FROM
														" . DB_PREFIX . "extendons_checkout_fields_data_columns ecfdc
														WHERE ecfdc.db_field_id = " . $db_field_id." AND ecfdc.db_data_id = " . $my_custom_address_id." AND ecfdc.customer_id = ". (int)$this->customer->getId());

								// this meta value is from column table
								$meta_value = @unserialize($custom_address_meta_q->row['meta_value']);
								if ($meta_value == true) {
									$custom_address_meta_q->row['meta_value'] = $meta_value;
								}
								

								$custom_address_query2->row['meta_address'] = $custom_address_meta_q->row;
								$getSingleFieldByID->row['my_custom_address'] = $custom_address_query2->row;

								$update_my_custom_address[] = $getSingleFieldByID->row;
							} // endforeach
						}
					}
				// Extendons - Checkout Manager /- End
		    	
			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($address_query->row['custom_field'], true)

            	// Extendons - Checkout Manager
					,'update_my_custom_address' => (isset($update_my_custom_address) && !empty($update_my_custom_address)) ? $update_my_custom_address : array()
				// Extendons - Checkout Manager /- End
		    	
			);

			return $address_data;
		} else {
			return false;
		}
	}

	public function getAddresses() {
		$address_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}


	            // Extendons - Checkout Manager
	        		$my_custom_address = array();
	        		$custom_fields_values = array();
	            	$check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `code` = 'checkout_manager'");

			        if ($check->num_rows) {
						// get new added fields and existing fields data from my custom table  
						$col_data = $this->db->query("SELECT address_id, db_data_id, firstname, lastname, company, address_1, address_2, postcode, city  FROM " . DB_PREFIX . "extendons_checkout_fields_data WHERE address_id = '" . $result['address_id'] . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

						$my_custom_address = $col_data->row;

						if ($col_data->num_rows) {
							$my_custom_address['zone'] 				= $zone;
							$my_custom_address['zone_code'] 		= $zone_code;
							$my_custom_address['country'] 			= $country;
							$my_custom_address['iso_code_2'] 		= $iso_code_2;
							$my_custom_address['iso_code_3'] 		= $iso_code_3;
							$my_custom_address['address_format'] 	= $address_format;

							$db_data_id = $col_data->row['db_data_id'];
							
							$col_q = $this->db->query("SELECT meta_key, meta_value FROM " . DB_PREFIX . "extendons_checkout_fields_data_columns WHERE `db_data_id` = '" . $db_data_id . "' ");
							if ($col_q->num_rows) {
								foreach ($col_q->rows as $k => $val) 
								{
									$check = @unserialize($val['meta_value']);
									if ($check == true) {
										$val['meta_value'] = $check;
									}
									$custom_fields_values[$k]['meta_key'] = $val['meta_key'];
									$custom_fields_values[$k]['meta_value'] = $val['meta_value'];
								}
							}
							$my_custom_address['custom_fields_values'] = $custom_fields_values;
						}
					}
					// echo "<pre>";print_r($custom_fields_values);
					// echo "<pre>";print_r($my_custom_address);exit;
				// Extendons - Checkout Manager /- End
		    	
			$address_data[$result['address_id']] = array(
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'postcode'       => $result['postcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($result['custom_field'], true)

            	// Extendons - Checkout Manager
					,'my_custom_address'   => $my_custom_address
				// Extendons - Checkout Manager /- End
		    	

			);
		}

		return $address_data;
	}

	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}
