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

class ControllerExtensionTotalTaxcloudIntegration extends Controller {
	private $type = 'total';
	private $name = 'taxcloud_integration';
	
	public function index() {
		$data = array(
			'type'			=> $this->type,
			'name'			=> $this->name,
			'autobackup'	=> false,
			'save_type'		=> 'keepediting',
			'permission'	=> $this->hasPermission('modify'),
		);
		
		$this->loadSettings($data);
		
		//------------------------------------------------------------------------------
		// Data Arrays
		//------------------------------------------------------------------------------
		$data['language_array'] = array($this->config->get('config_language') => '');
		$data['language_flags'] = array();
		$this->load->model('localisation/language');
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['language_array'][$language['code']] = $language['name'];
			$data['language_flags'][$language['code']] = (version_compare(VERSION, '2.2', '<')) ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}
		
		$data['store_array'] = array(0 => $this->config->get('config_name'));
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		foreach ($store_query->rows as $store) {
			$data['store_array'][$store['store_id']] = $store['name'];
		}
		
		$data['geo_zone_array'] = array(0 => $data['text_everywhere_else']);
		$this->load->model('localisation/geo_zone');
		foreach ($this->model_localisation_geo_zone->getGeoZones() as $geo_zone) {
			$data['geo_zone_array'][$geo_zone['geo_zone_id']] = $geo_zone['name'];
		}
		
		$data['customer_group_array'] = array(0 => $data['text_guests']);
		$this->load->model((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer_group');
		foreach ($this->{'model_' . (version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '_customer_group'}->getCustomerGroups() as $customer_group) {
			$data['customer_group_array'][$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$data['tic_fields'] = array(0 => $data['text_always_use_default_tic']);
		$product_column_query = $this->db->query("DESCRIBE " . DB_PREFIX . "product");
		foreach ($product_column_query->rows as $column) {
			$data['tic_fields'][$column['Field']] = $column['Field'];
		}
		asort($data['tic_fields']);
		
		//------------------------------------------------------------------------------
		// Extension Settings
		//------------------------------------------------------------------------------
		$data['settings'] = array();
		
		$data['settings'][] = array(
			'type'		=> 'tabs',
			'tabs'		=> array('extension_settings', 'taxcloud_settings', 'order_criteria', 'testing_mode'),
		);
		$data['settings'][] = array(
			'key'		=> 'extension_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'status',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'sort_order',
			'type'		=> 'text',
			'default'	=> 8,
			'attributes'=> array('style' => 'width: 50px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'title',
			'type'		=> 'multilingual_text',
			'default'	=> 'Taxes',
		);
		$data['settings'][] = array(
			'key'		=> 'google_apikey',
			'type'		=> 'text',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info well">' . $data['help_info'] . '</div>',
		);
		
		//------------------------------------------------------------------------------
		// TaxCloud Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'taxcloud_settings',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'key'		=> 'taxcloud_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'api_id',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 100px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'api_key',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 300px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'usps_id',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 150px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'store_address',
			'type'		=> 'html',
			'content'	=> '
				<input type="text" class="form-control" name="address1" placeholder="' . $data['placeholder_address_line_1'] . '" value="' . (!empty($data['saved']['address1']) ? $data['saved']['address1'] : '') . '" /><br>
				<input type="text" class="form-control" name="address2" placeholder="' . $data['placeholder_address_line_2'] . '" value="' . (!empty($data['saved']['address2']) ? $data['saved']['address2'] : '') . '" /><br>
				<input type="text" class="form-control" name="city" placeholder="' . $data['placeholder_city'] . '" value="' . (!empty($data['saved']['city']) ? $data['saved']['city'] : '') . '" style="width: 140px !important" />
				<input type="text" class="form-control" name="state" placeholder="' . $data['placeholder_state'] . '" value="' . (!empty($data['saved']['state']) ? $data['saved']['state'] : '') . '" style="width: 50px !important" maxlength="2" /><br>
				<input type="text" class="form-control" name="zip5" placeholder="' . $data['placeholder_zip_code'] . '" value="' . (!empty($data['saved']['zip5']) ? $data['saved']['zip5'] : '') . '" style="width: 75px !important" maxlength="5" />
				<input type="text" class="form-control" name="zip4" placeholder="' . $data['placeholder_zip4'] . '" value="' . (!empty($data['saved']['zip4']) ? $data['saved']['zip4'] : '') . '" style="width: 50px !important" maxlength="4" />
			',
		);
		$data['settings'][] = array(
			'key'		=> 'tic_field',
			'type'		=> 'select',
			'options'	=> $data['tic_fields'],
			'default'	=> '',
		);
		$data['settings'][] = array(
			'key'		=> 'precheckout_pages',
			'type'		=> 'select',
			'options'	=> array('tax' => $data['text_get_tax_amount'], 'fallback' => $data['text_use_fallback_rate'], 'hide' => $data['text_hide']),
		);
		$data['settings'][] = array(
			'key'		=> 'view_sent_orders',
			'type'		=> 'html',
			'content'	=> '<a target="_blank" class="btn btn-primary" href="index.php?route=extension/' . $this->type . '/' . $this->name . '/report&token=' . $data['token'] . '">' . $data['button_view_sent_orders'] . '</a>',
		);
		$data['settings'][] = array(
			'key'		=> 'batch_order_send',
			'type'		=> 'html',
			'content'	=> '
				' . $data['text_starting_order_id'] . ' <input type="text" id="starting-order-id" class="form-control medium" style="margin-bottom: 5px" /><br>
				' . $data['text_ending_order_id'] . ' <input type="text" id="ending-order-id" class="form-control medium" style="margin-bottom: 5px" /><br>
				<a class="btn btn-primary" style="margin-left: 103px" onclick="sendOrders($(this))">' . $data['button_send'] . '</a>
				<script>
					function sendOrders(element) {
						if (confirm("' . $data['standard_confirm'] . '")) {
							$.ajax({
								url: "index.php?route=extension/' . $this->type . '/' . $this->name . '/sendOrders&token=' . $data['token'] . '&start=" + $("#starting-order-id").val() + "&end=" + $("#ending-order-id").val(),
								beforeSend: function() {
									element.html("' . $data['standard_please_wait'] . '").attr("disabled", "disabled");
								},
								success: function(data) {
									alert(data);
									element.html("' . $data['button_send'] . '").removeAttr("disabled");
								},
								error: function(xhr, status, error) {
									alert(xhr.responseText ? xhr.responseText : error);
									element.html("' . $data['button_send'] . '").removeAttr("disabled");
								}
							});
						}
					}
				</script>
			',
		);
		
		// Fallback Tax Rates
		$data['settings'][] = array(
			'key'		=> 'fallback_tax_rates',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center pad-bottom">' . $data['help_fallback_tax_rates'] . '</div>',
		);
		
		foreach ($data['geo_zone_array'] as $geo_zone_id => $geo_zone_name) {
			$data['settings'][] = array(
				'key'		=> 'fallback_' . $geo_zone_id,
				'type'		=> 'text',
				'title'		=> $geo_zone_name . ':',
				'attributes'=> array('style' => 'width: 60px !important'),
				'after'		=> '%',
			);
		}
		
		//------------------------------------------------------------------------------
		// Order Criteria
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'order_criteria',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'key'		=> 'order_criteria',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'stores',
			'type'		=> 'checkboxes',
			'options'	=> $data['store_array'],
			'default'	=> array_keys($data['store_array']),
		);
		$data['settings'][] = array(
			'key'		=> 'geo_zones',
			'type'		=> 'checkboxes',
			'options'	=> $data['geo_zone_array'],
			'default'	=> array_keys($data['geo_zone_array']),
		);
		$data['settings'][] = array(
			'key'		=> 'customer_groups',
			'type'		=> 'checkboxes',
			'options'	=> $data['customer_group_array'],
			'default'	=> array_keys($data['customer_group_array']),
		);
		
		//------------------------------------------------------------------------------
		// Testing Mode
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'testing_mode',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center pad-bottom">' . $data['testing_mode_help'] . '</div>',
		);
		
		$filepath = DIR_LOGS . $this->name . '.messages';
		$testing_mode_log = '';
		$refresh_or_download_button = '<a class="btn btn-info" onclick="refreshLog()"><i class="fa fa-refresh pad-right-sm"></i> ' . $data['button_refresh_log'] . '</a>';
		
		if (file_exists($filepath)) {
			$filesize = filesize($filepath);
			
			if ($filesize > 50000000) {
				file_put_contents($filepath, '');
				$filesize = 0;
			}
			
			if ($filesize > 999999) {
				$testing_mode_log = $data['standard_testing_mode'];
				$refresh_or_download_button = '<a class="btn btn-info" href="index.php?route=extension/' . $this->type . '/' . $this->name . '/downloadLog&token=' . $data['token'] . '"><i class="fa fa-download pad-right-sm"></i> ' . $data['button_download_log'] . ' (' . round($filesize / 1000000, 1) . ' MB)</a>';
			} else {
				$testing_mode_log = html_entity_decode(file_get_contents($filepath), ENT_QUOTES, 'UTF-8');
			}
		}
		
		$data['settings'][] = array(
			'key'		=> 'testing_mode',
			'type'		=> 'heading',
			'buttons'	=> $refresh_or_download_button . ' <a class="btn btn-danger" onclick="clearLog()"><i class="fa fa-trash-o pad-right-sm"></i> ' . $data['button_clear_log'] . '</a>',
		);
		$data['settings'][] = array(
			'key'		=> 'testing_mode',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'testing_messages',
			'type'		=> 'textarea',
			'class'		=> 'nosave',
			'attributes'=> array('style' => 'width: 100% !important; height: 400px; font-size: 12px !important'),
			'default'	=> htmlentities($testing_mode_log),
		);
		
		//------------------------------------------------------------------------------
		// end settings
		//------------------------------------------------------------------------------
		
		$this->document->setTitle($data['heading_title']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$template_file = DIR_TEMPLATE . 'extension/' . $this->type . '/' . $this->name . '.twig';
		
		if (is_file($template_file)) {
			extract($data);
			
			ob_start();
			require(class_exists('VQMod') ? VQMod::modCheck(modification($template_file)) : modification($template_file));
			$output = ob_get_clean();
			
			if (version_compare(VERSION, '3.0', '>=')) {
				$output = str_replace(array('&token=', '&amp;token='), '&user_token=', $output);
			}
			
			echo $output;
		} else {
			echo 'Error loading template file';
		}
	}
	
	//==============================================================================
	// Helper functions
	//==============================================================================
	private function hasPermission($permission) {
		return ($this->user->hasPermission($permission, $this->type . '/' . $this->name) || $this->user->hasPermission($permission, 'extension/' . $this->type . '/' . $this->name));
	}
	
	private function loadLanguage($path) {
		$_ = array();
		$language = array();
		$admin_language = (version_compare(VERSION, '2.2', '<')) ? $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE `code` = '" . $this->db->escape($this->config->get('config_admin_language')) . "'")->row['directory'] : $this->config->get('config_admin_language');
		foreach (array('english', 'en-gb', $admin_language) as $directory) {
			$file = DIR_LANGUAGE . $directory . '/' . $directory . '.php';
			if (file_exists($file)) require($file);
			$file = DIR_LANGUAGE . $directory . '/default.php';
			if (file_exists($file)) require($file);
			$file = DIR_LANGUAGE . $directory . '/' . $path . '.php';
			if (file_exists($file)) require($file);
			$file = DIR_LANGUAGE . $directory . '/extension/' . $path . '.php';
			if (file_exists($file)) require($file);
			$language = array_merge($language, $_);
		}
		return $language;
	}
	
	private function getTableRowNumbers(&$data, $table, $sorting) {
		$groups = array();
		$rules = array();
		
		foreach ($data['saved'] as $key => $setting) {
			if (preg_match('/' . $table . '_(\d+)_' . $sorting . '/', $key, $matches)) {
				$groups[$setting][] = $matches[1];
			}
			if (preg_match('/' . $table . '_(\d+)_rule_(\d+)_type/', $key, $matches)) {
				$rules[$matches[1]][] = $matches[2];
			}
		}
		
		if (empty($groups)) $groups = array('' => array('1'));
		ksort($groups, defined('SORT_NATURAL') ? SORT_NATURAL : SORT_REGULAR);
		
		foreach ($rules as $key => $rule) {
			ksort($rules[$key], defined('SORT_NATURAL') ? SORT_NATURAL : SORT_REGULAR);
		}
		
		$data['used_rows'][$table] = array();
		$rows = array();
		foreach ($groups as $group) {
			foreach ($group as $num) {
				$data['used_rows'][preg_replace('/module_(\d+)_/', '', $table)][] = $num;
				$rows[$num] = (empty($rules[$num])) ? array() : $rules[$num];
			}
		}
		sort($data['used_rows'][$table]);
		
		return $rows;
	}
	
	//==============================================================================
	// Setting functions
	//==============================================================================
	private $encryption_key = '';
	
	public function loadSettings(&$data) {
		$backup_type = (empty($data)) ? 'manual' : 'auto';
		if ($backup_type == 'manual' && !$this->hasPermission('modify')) {
			return;
		}
		
		$this->cache->delete($this->name);
		unset($this->session->data[$this->name]);
		$code = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name;
		
		// Set exit URL
		$data['token'] = $this->session->data[version_compare(VERSION, '3.0', '<') ? 'token' : 'user_token'];
		$data['exit'] = $this->url->link((version_compare(VERSION, '3.0', '<') ? 'extension' : 'marketplace') . '/' . (version_compare(VERSION, '2.3', '<') ? '' : 'extension&type=') . $this->type . '&token=' . $data['token'], '', 'SSL');
		
		// Load saved settings
		$data['saved'] = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$key = str_replace($code . '_', '', $setting['key']);
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			
			$data['saved'][$key] = $value;
			
			if (is_array($value)) {
				foreach ($value as $num => $value_array) {
					foreach ($value_array as $k => $v) {
						$data['saved'][$key . '_' . $num . '_' . $k] = $v;
					}
				}
			}
		}
		
		// Load language and run standard checks
		$data = array_merge($data, $this->loadLanguage($this->type . '/' . $this->name));
		
		if (ini_get('max_input_vars') && ((ini_get('max_input_vars') - count($data['saved'])) < 50)) {
			$data['warning'] = $data['standard_max_input_vars'];
		}
		
		// Modify files according to OpenCart version
		if ($this->type == 'total' && version_compare(VERSION, '2.2', '<')) {
			file_put_contents(DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php', str_replace('public function getTotal($total) {', 'public function getTotal(&$total_data, &$order_total, &$taxes) {' . "\n\t\t" . '$total = array("totals" => &$total_data, "total" => &$order_total, "taxes" => &$taxes);', file_get_contents(DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php')));
		}
		
		if (version_compare(VERSION, '2.3', '>=')) {
			$filepaths = array(
				DIR_APPLICATION . 'controller/' . $this->type . '/' . $this->name . '.php',
				DIR_CATALOG . 'controller/' . $this->type . '/' . $this->name . '.php',
				DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php',
			);
			foreach ($filepaths as $filepath) {
				if (file_exists($filepath)) {
					rename($filepath, str_replace('.php', '.php-OLD', $filepath));
				}
			}
		}
		
		// Set save type and skip auto-backup if not needed
		if (!empty($data['saved']['autosave'])) {
			$data['save_type'] = 'auto';
		}
		
		if ($backup_type == 'auto' && empty($data['autobackup'])) {
			return;
		}
		
		// Create settings auto-backup file
		$manual_filepath = DIR_LOGS . $this->name . $this->encryption_key . '.backup';
		$auto_filepath = DIR_LOGS . $this->name . $this->encryption_key . '.autobackup';
		$filepath = ($backup_type == 'auto') ? $auto_filepath : $manual_filepath;
		if (file_exists($filepath)) unlink($filepath);
		
		file_put_contents($filepath, 'SETTING	NUMBER	SUB-SETTING	SUB-NUMBER	SUB-SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		
		foreach ($data['saved'] as $key => $value) {
			if (is_array($value)) continue;
			
			$parts = explode('|', preg_replace(array('/_(\d+)_/', '/_(\d+)/'), array('|$1|', '|$1'), $key));
			
			$line = '';
			for ($i = 0; $i < 5; $i++) {
				$line .= (isset($parts[$i]) ? $parts[$i] : '') . "\t";
			}
			$line .= str_replace(array("\t", "\n"), array('    ', '\n'), $value) . "\n";
			
			file_put_contents($filepath, $line, FILE_APPEND|LOCK_EX);
		}
		
		$data['autobackup_time'] = date('Y-M-d @ g:i a');
		$data['backup_time'] = (file_exists($manual_filepath)) ? date('Y-M-d @ g:i a', filemtime($manual_filepath)) : '';
		
		if ($backup_type == 'manual') {
			echo $data['autobackup_time'];
		}
	}
	
	public function saveSettings() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		
		$this->cache->delete($this->name);
		unset($this->session->data[$this->name]);
		$code = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name;
		
		if ($this->request->get['saving'] == 'manual') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' AND `key` != '" . $this->db->escape($this->name . '_module') . "'");
		}
		
		$module_id = 0;
		$modules = array();
		$module_instance = false;
		
		foreach ($this->request->post as $key => $value) {
			if (strpos($key, 'module_') === 0) {
				$parts = explode('_', $key, 3);
				$module_id = $parts[1];
				$modules[$parts[1]][$parts[2]] = $value;
				if ($parts[2] == 'module_id') $module_instance = true;
			} else {
				$key = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name . '_' . $key;
				
				if ($this->request->get['saving'] == 'auto') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "'");
				}
				
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "setting SET
					`store_id` = 0,
					`code` = '" . $this->db->escape($code) . "',
					`key` = '" . $this->db->escape($key) . "',
					`value` = '" . $this->db->escape(stripslashes(is_array($value) ? implode(';', $value) : $value)) . "',
					`serialized` = 0
				");
			}
		}
		
		foreach ($modules as $module_id => $module) {
			if (!$module_id) {
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "module SET
					`name` = '" . $this->db->escape($module['name']) . "',
					`code` = '" . $this->db->escape($this->name) . "',
					`setting` = ''
				");
				$module_id = $this->db->getLastId();
				$module['module_id'] = $module_id;
			}
			$module_settings = (version_compare(VERSION, '2.1', '<')) ? serialize($module) : json_encode($module);
			$this->db->query("
				UPDATE " . DB_PREFIX . "module SET
				`name` = '" . $this->db->escape($module['name']) . "',
				`code` = '" . $this->db->escape($this->name) . "',
				`setting` = '" . $this->db->escape($module_settings) . "'
				WHERE module_id = " . (int)$module_id . "
			");
		}
	}
	
	public function deleteSetting() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($prefix . $this->name) . "' AND `key` = '" . $this->db->escape($prefix . $this->name . '_' . str_replace('[]', '', $this->request->get['setting'])) . "'");
	}
	
	//==============================================================================
	// Ajax functions
	//==============================================================================
	public function refreshLog() {
		$data = $this->loadLanguage($this->type . '/' . $this->name);
		
		if (!$this->hasPermission('modify')) {
			echo $data['standard_error'];
			return;
		}
		
		$filepath = DIR_LOGS . $this->name . '.messages';
		
		if (file_exists($filepath)) {
			if (filesize($filepath) > 999999) {
				echo $data['standard_testing_mode'];
			} else {
				echo html_entity_decode(file_get_contents($filepath), ENT_QUOTES, 'UTF-8');
			}
		}
	}
	
	public function downloadLog() {
		$file = DIR_LOGS . $this->name . '.messages';
		if (!file_exists($file) || !$this->hasPermission('access')) {
			return;
		}
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=' . $this->name . '.' . date('Y-n-d') . '.log');
		header('Content-Length: ' . filesize($file));
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: text/plain');
		header('Expires: 0');
		header('Pragma: public');
		readfile($file);
	}
	
	public function clearLog() {
		$data = $this->loadLanguage($this->type . '/' . $this->name);
		
		if (!$this->hasPermission('modify')) {
			echo $data['standard_error'];
			return;
		}
		
		file_put_contents(DIR_LOGS . $this->name . '.messages', '');
	}
	
	//==============================================================================
	// Custom functions
	//==============================================================================
	public function sendOrders() {
		if (!empty($this->request->get['start'])) {
			$start = $this->request->get['start'];
		} else {
			$start = 1;
		}
		
		if (!empty($this->request->get['end'])) {
			$end = $this->request->get['end'];
		} else {
			$end = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0 ORDER BY order_id DESC LIMIT 1")->row['order_id'];
		}
		
		$messages = array();
		
		for ($order_id = $start; $order_id <= $end; $order_id++) {
			$message = $this->submitOrder($order_id);
			$messages[] = trim($message);
		}
		
		$messages = array_filter(array_unique($messages));
		echo (empty($messages)) ? 'Success!' : implode("\n", $messages);
	}
	
	public function submitOrder($order_id = 0) {
		if (empty($order_id)) {
			$order_id = $this->request->get['order_id'];
		} else {
			$batch = true;
		}
		
		$this->load->model('sale/order');
		$order_info = $this->model_sale_order->getOrder($order_id);
		$order_products = $this->model_sale_order->getOrderProducts($order_id);
		
		$model_file = DIR_CATALOG . 'model/extension/' . $this->type . '/' . $this->name . '.php';
		require_once(class_exists('VQMod') ? VQMod::modCheck($model_file) : $model_file);
		
		$taxcloud_integration = new ModelExtensionTotalTaxcloudIntegration($this->registry);
		$messages = $taxcloud_integration->authorizeAndCapture($order_info, $order_info['order_status_id'], $order_products);
		
		if (empty($batch)) {
			echo (empty($messages)) ? 'Success!' : $messages;
		} else {
			return $messages;
		}
	}
	
	public function returnOrder() {
		$indexes = str_replace(' ', '', $this->request->get['indexes']);
		if (empty($indexes)) {
			$indexes = array();
		} else {
			$indexes = explode(',', $indexes);
		}
		
		$model_file = DIR_CATALOG . 'model/extension/' . $this->type . '/' . $this->name . '.php';
		require_once(class_exists('VQMod') ? VQMod::modCheck($model_file) : $model_file);
		
		$taxcloud_integration = new ModelExtensionTotalTaxcloudIntegration($this->registry);
		$messages = $taxcloud_integration->returnOrder($this->request->get['order_id'], $indexes);
		
		echo (empty($messages)) ? 'Success!' : $messages;
	}
	
	public function report() {
		if (!$this->hasPermission('access')) {
			echo 'You do not have permission to view this file.';
			return;
		}
		
		$data = $this->loadLanguage($this->type . '/' . $this->name);
		
		// Get order statuses
		$order_statuses = array();
		
		$order_status_list = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status")->rows;
		foreach ($order_status_list as $order_status) {
			$order_statuses[$order_status['order_status_id']] = $order_status['name'];
		}
		
		// Get sent order_ids
		$order_ids = array();
		$dates_sent = array();
		
		$order_history_notes = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE comment LIKE '%Submitted to TaxCloud%'")->rows;
		foreach ($order_history_notes as $order_history_note) {
			$order_ids[] = $order_history_note['order_id'];
			$dates_sent[$order_history_note['order_id']] = $order_history_note['date_added'];
		}
		
		// Get order data
		if (!empty($this->request->get['order_status_id'])) {
			$filter_order_status = "o.order_status_id = " . (int)$this->request->get['order_status_id'];
		} else {
			$filter_order_status = "o.order_status_id > 0";
		}
		
		$orders = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.order_id IN (" . implode(",", $order_ids) . ") AND " . $filter_order_status . " ORDER BY o.order_id DESC")->rows;
		
		// Set up HTML
		$html = '<html><head><title>' . $data['button_view_sent_orders'] . '</title></head><body>';
		$html .= '<table border="1" style="font-family: monospace; width: 100%" cellspacing="0" cellpadding="5">';
		$html .= '<tr>';
		$html .= '<td><b>' . $data['column_order_id'] . '</b></td>';
		$html .= '<td><b>' . $data['column_customer'] . '</b></td>';
		$html .= '<td><b>' . $data['column_status'] . '</b></td>';
		$html .= '<td><b>' . $data['column_total'] . '</b></td>';
		$html .= '<td><b>' . $data['column_date_added'] . '</b></td>';
		$html .= '<td><b>' . $data['column_date_sent'] . '</b></td>';
		$html .= '</tr>';
		
		if (empty($orders)) {
			$html .= '<tr><td colspan="6">' . $data['text_no_orders_have_been_sent'] . '</td></tr>';
		} else {
			$token = (version_compare(VERSION, '3.0', '<')) ? 'token=' . $this->request->get['token'] : 'user_token=' . $this->request->get['user_token'];
			
			foreach ($orders as $order) {
				$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$order['customer_id']);
				if ($customer_query->num_rows) {
					$customer_link = $this->url->link((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer/edit', 'customer_id=' . $order['customer_id'] . '&' . $token, 'SSL');
					$customer = '<a href="' . $customer_link . '">' . $order['firstname'] . ' ' . $order['lastname'] . ' (' . $order['email'] . ')</a>';
				} else {
					$customer = $order['firstname'] . ' ' . $order['lastname'] . ' (' . $order['email'] . ')';
				}
				
				$html .= '<tr>';
				$html .= '<td>' . '<a href="' . $this->url->link('sale/order/info', 'order_id=' . $order['order_id'] . '&' . $token, 'SSL') . '">' . $order['order_id'] . '</a>' . '</td>';
				$html .= '<td>' . $customer . '</td>';
				$html .= '<td>' . $order_statuses[$order['order_status_id']] . '</td>';
				$html .= '<td>' . $this->currency->format($order['total'], $order['currency_code'], $order['currency_value']) . '</td>';
				$html .= '<td>' . $order['date_added'] . '</td>';
				$html .= '<td>' . $dates_sent[$order['order_id']] . '</td>';
				$html .= '</tr>';
			}
		}
		
		// Output
		echo $html . '</table></body></html>';
	}
}
?>