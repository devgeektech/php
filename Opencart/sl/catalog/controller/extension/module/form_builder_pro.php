<?php
//==============================================================================
// Form Builder Pro v303.1
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ControllerExtensionModuleFormBuilderPro extends Controller {
	private $type = 'module';
	private $name = 'form_builder_pro';
	
	public function index($settings) {
		$data['type'] = $this->type;
		$data['name'] = $this->name;
		$data = array_merge($data, $this->load->language('information/information'));
		
		// Load needed data
		$data['store_id'] = $this->config->get('config_store_id');
		$data['language'] = $this->session->data['language'];
		$data['customer_group_id'] = (int)$this->customer->getGroupId();
		$data['currency'] = $this->session->data['currency'];
		
		// Restrictions check
		if (empty($settings) ||
			empty($settings['status']) ||
			!array_intersect(array($data['store_id']), $settings['stores']) ||
			!array_intersect(array($data['language']), $settings['languages']) ||
			!array_intersect(array($data['customer_group_id']), $settings['customer_groups']) ||
			!array_intersect(array($data['currency']), $settings['currencies'])
		) {
			return;
		}
		
		// Determine layout
		$data['settings'] = $settings;
		$data['total_rows'] = 0;
		$data['fields'] = array();
		
		$layouts = array();
		
		foreach (explode(',', $settings['layout']) as $layout) {
			$pair = explode(':', $layout);
			$layouts[$pair[0]] = $pair[1];
		}
		
		$data['fields'] = array();
		
		$captcha_field = false;
		$date_time_field = false;
		$file_upload_field = false;
		$typeahead_field = false;
		
		foreach ($settings['fields'] as $field) {
			if (empty($layouts[$field['key']])) continue;
			
			if ($field['type'] == 'captcha') {
				$captcha_field = true;
			} elseif ($field['type'] == 'date' || $field['type'] == 'time') {
				$date_time_field = true;
			} elseif ($field['type'] == 'file') {
				$file_upload_field = true;
			} elseif ($field['type'] == 'product' || $field['type'] == 'category' || $field['type'] == 'manufacturer') {
				$typeahead_field = true;
			}
			
			foreach ($field as &$value) {
				$value = $this->replaceShortcodes($value, $settings);
			}
			
			$pos = explode('-', $layouts[$field['key']]);
			if ($pos[0] == 1 && $field['type'] != 'hidden') {
				$data['total_rows'] += $pos[3];
			}
			
			$field['x']		= $pos[0];
			$field['y']		= $pos[1];
			$field['cols']	= $pos[2];
			$field['rows']	= $pos[3];
			
			$data['fields'][$pos[1] . $pos[0]] = $field;
		}
		
		ksort($data['fields']);
		
		// Get captcha keys
		if (!empty($settings['recaptcha_site_key'])) {
			$data['site_key'] = $settings['recaptcha_site_key'];
		} elseif (version_compare(VERSION, '2.1', '<')) {
			$data['site_key'] = $this->config->get('config_google_captcha_public');
		} elseif (version_compare(VERSION, '3.0', '<')) {
			$data['site_key'] = $this->config->get('google_captcha_key');
		} else {
			$data['site_key'] = $this->config->get('captcha_google_key');
		}
		
		if ($captcha_field && $data['site_key']) {
			$this->document->addScript('https://www.google.com/recaptcha/api.js');
		}
		
		// Add links and scripts
		if ($date_time_field) {
			$this->document->addLink('catalog/view/javascript/' . $this->name . '/picker.classic.min.css', 'stylesheet');
			$this->document->addScript('catalog/view/javascript/' . $this->name . '/picker.min.js');
		}
		
		if ($file_upload_field) {
			$this->document->addScript('catalog/view/javascript/' . $this->name . '/jquery.ui.widget.js');
			$this->document->addScript('catalog/view/javascript/' . $this->name . '/jquery.iframe-transport.js');
			$this->document->addScript('catalog/view/javascript/' . $this->name . '/jquery.fileupload.js');
		}
		
		if ($typeahead_field) {
			$this->document->addScript('catalog/view/javascript/' . $this->name . '/typeahead.min.js');
		}
		
		$this->document->addScript('catalog/view/javascript/' . $this->name . '/' . $this->name . '.js');
		
		// Render
		$theme = (version_compare(VERSION, '2.2', '<')) ? $this->config->get('config_template') : $this->config->get('config_theme');
		$template = (file_exists(DIR_TEMPLATE . $theme . '/template/extension/' . $this->type . '/' . $this->name . '.twig')) ? $theme : 'default';
		$template_file = DIR_TEMPLATE . $template . '/template/extension/' . $this->type . '/' . $this->name . '.twig';
		
		if (version_compare(VERSION, '3.0', '>=')) {
			$override_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "theme WHERE theme = '" . $this->db->escape($theme) . "' AND route = 'extension/" . $this->type . "/" . $this->name . "'");
			if ($override_query->num_rows) {
				$cache_file = DIR_CACHE . $this->name . '.twig.' . strtotime($override_query->row['date_added']);
				
				if (!file_exists($cache_file)) {
					$old_files = glob(DIR_CACHE . $this->name . '.twig.*');
					foreach ($old_files as $old_file) unlink($old_file);
					file_put_contents($cache_file, html_entity_decode($override_query->row['code'], ENT_QUOTES, 'UTF-8'));
				}
				
				$template_file = $cache_file;
			}
		}
		
		if (is_file($template_file)) {
			extract($data);
			
			ob_start();
			require(class_exists('VQMod') ? VQMod::modCheck(modification($template_file)) : modification($template_file));
			$output = ob_get_clean();
			
			return $output;
		} else {
			return 'Error loading template file: ' . $template_file;
		}
	}
	
	//==============================================================================
	// replaceShortcodes()
	//==============================================================================
	private function replaceShortcodes($text, $settings) {
		// Replace general fields
		$language = (isset($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		$store_name = $this->config->get('config_name');
		if (is_array($store_name)) $store_name = array_shift($store_name);
		$page_url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		$replace = array(
			'[current_date]',
			'[current_time]',
			'[customer_ip]',
			'[form_name]',
			'[page_url]',
			'[store_address]',
			'[store_email]',
			'[store_fax]',
			'[store_name]',
			'[store_owner]',
			'[store_telephone]',
			'[store_url]',
			'[user_agent]',
		);
		
		$with = array(
			date($this->language->get('date_format_short')),
			date($this->language->get('time_format')),
			$this->db->escape($this->request->server['REMOTE_ADDR']),
			$settings['heading_' . $language],
			$page_url,
			$this->config->get('config_address'),
			$this->config->get('config_email'),
			$this->config->get('config_fax'),
			$store_name,
			$this->config->get('config_owner'),
			$this->config->get('config_telephone'),
			($this->config->get('config_url') ? $this->config->get('config_url') : HTTP_SERVER),
			(isset($this->request->server['HTTP_USER_AGENT']) ? $this->request->server['HTTP_USER_AGENT'] : ''),
		);
		
		$text = str_replace($replace, $with, $text);
		
		// Replace customer fields
		if ($this->customer->isLogged()) {
			$customer = $this->db->query("SELECT *, a.custom_field AS address_custom_field FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "address a ON (c.address_id = a.address_id) WHERE c.customer_id = " . (int)$this->customer->getId())->row;
			
			foreach ($customer as $key => $value) {
				$text = str_replace('[customer_' . $key . ']', $value, $text);
			}
			
			$text = str_replace('[customer_name]', $customer['firstname'] . ' ' . $customer['lastname'], $text);
			
			if (!empty($customer['country_id'])) {
				$country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$customer['country_id'])->row;
				$text = str_replace('[customer_country]', $country['name'], $text);
			}
			
			if (!empty($customer['zone_id'])) {
				$zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$customer['zone_id'])->row;
				$text = str_replace('[customer_zone]', $zone['name'], $text);
			}
		}
		
		// Replace product fields
		foreach ($this->request->get as $key => $value) {
			if (is_array($value)) continue;
			
			$text = str_replace('[' . $key . ']', urldecode($value), $text);
			if ($key != 'product_id') continue;
			
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($value);
			if (!$product_info) continue;
			
			foreach ($product_info as $k => $v) {
				$text = str_replace('[product_' . $k . ']', $v, $text);
			}
		}
		
		// Replace [cart_contents]
		$products = $this->cart->getProducts();
		
		if (!empty($products)) {
			$cart_contents = '<table><tr><td style="white-space: nowrap"><strong>Cart Contents:</strong></td> <td style="padding-left: 10px; text-align: right;">';
			
			foreach ($products as $product) {
				$options_text = '';
				if (!empty($product['option'])) {
					$options = array();
					foreach ($product['option'] as $option) {
						$options[] = $option['name'] . ': ' . $option['value'];
					}
					$options_text = '(' . implode(', ', $options) . ')';
				}
				
				$cart_contents .= '- ' . $product['name'] . $options_text . ' x ' . $product['quantity'] . ': ' . $this->currency->format($product['total'], $this->session->data['currency']) . '<br />' . "\n";
			}
			
			if ($this->cart->countProducts() > 1) {
				$cart_contents .= '<strong>Total: ' . $this->currency->format($this->cart->getSubTotal(), $this->session->data['currency']) . '</strong>';
			}
			
			$cart_contents .= '</td></tr></table>' . "\n";
			
			$text = str_replace('[cart_contents]', $cart_contents, $text);
		}
		
		// Return modified text
		$text = preg_replace('/\[.*?\]/', '', $text);
		return html_entity_decode(trim($text), ENT_QUOTES, 'UTF-8');
	}
	
	//==============================================================================
	// upload()
	//==============================================================================
	public function upload() {
		$this->load->model((version_compare(VERSION, '3.0', '<') ? 'extension' : 'setting') . '/module');
		$form = $this->{'model_' . (version_compare(VERSION, '3.0', '<') ? 'extension' : 'setting') . '_module'}->getModule($this->request->get['module_id']);
		
		$json = array();
		$filename = '(no filename)';
		$language = (isset($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
			if ((strlen($filename) < 3) || (strlen($filename) > 128)) {
				$json['error'] = $form['error_file_name_' . $language];
			}
			$allowed = explode(',', preg_replace('/[\.\s+]/', '', strtolower($form['file_extensions'])));
			if (!in_array(strtolower(substr($filename, strrpos($filename, '.') + 1)), $allowed)) {
				$json['error'] = $form['error_file_ext_' . $language];
       		}
			if ($this->request->files['file']['size'] > $form['file_size']*1000) {
				$json['error'] = $form['error_file_size_' . $language];
			}
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $form['error_file_upload_' . $language];
			}
		} else {
			$json['error'] = $form['error_file_upload_' . $language];
		}
		
		if (empty($json)) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = trim(basename($filename) . '.' . md5(mt_rand()));
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
				if (version_compare(VERSION, '2.1', '<')) {
					$this->load->library('encryption');
				}
				if (version_compare(VERSION, '3.0', '<')) {
					$encryption = new Encryption($this->config->get('config_encryption'));
					$json['file'] = $encryption->encrypt($file);
				} else {
					$json['file'] = $this->encryption->encrypt($this->config->get('config_encryption'), $file);
				}
				$json['name'] = str_replace(strrchr(basename($file), '.'), '', basename($file)); 
			}
		} else {
			$json['name'] = $filename;
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	//==============================================================================
	// submit()
	//==============================================================================
	public function submit() {
		$this->load->model((version_compare(VERSION, '3.0', '<') ? 'extension' : 'setting') . '/module');
		$form = $this->{'model_' . (version_compare(VERSION, '3.0', '<') ? 'extension' : 'setting') . '_module'}->getModule($this->request->get['module_id']);
		
		if (!$form['status']) return;
		
		$language = (isset($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		$store_name = $this->config->get('config_name');
		if (is_array($store_name)) $store_name = array_shift($store_name);
		
		// Check captcha
		foreach ($form['fields'] as $field) {
			if ($field['type'] == 'captcha') {
				if (!empty($form['recaptcha_secret_key'])) {
					$secret_key = $form['recaptcha_secret_key'];
				} elseif (version_compare(VERSION, '2.1', '<')) {
					$secret_key = $this->config->get('config_google_captcha_secret');
				} elseif (version_compare(VERSION, '3.0', '<')) {
					$secret_key = $this->config->get('google_captcha_secret');
				} else {
					$secret_key = $this->config->get('captcha_google_secret');
				}
				
				$recaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) . '&response=' . $this->request->get['captcha'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']), true);
				
				if (!$recaptcha['success']) {
					echo $form['error_captcha_' . $language];
					return;
				}
			}
		}
		
		// Set up e-mail
		if (version_compare(VERSION, '2.0.2', '<')) {
			$mail = new Mail($this->config->get('config_mail'));
			$protocol_engine = $mail->protocol;
		} else {
			if (version_compare(VERSION, '3.0', '<')) {
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$protocol_engine = $this->config->get('config_mail_protocol');
			} else {
				$mail = new Mail($this->config->get('config_mail_engine'));
				$protocol_engine = $this->config->get('config_mail_engine');
			}
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		}
		
		// Format responses
		$responses = array();
		$customer_emails = array();
		$files = array();
		$admin_response_list = '';
		$customer_response_list = '';
		
		/* sort fields by Key
		$sort_field = array();
		foreach ($form['fields'] as $key => $value) $sort_field[$key] = $value['key'];
		array_multisort($sort_field, SORT_ASC, $form['fields']);
		*/
		
		$replace = array();
		$with = array();
		
		foreach ($form['fields'] as $field) {
			if (in_array($field['type'], array('captcha', 'html', 'submit'))) continue;
			
			$response = (isset($this->request->post[$field['key']])) ? $this->request->post[$field['key']] : '';
			//if (empty($response)) continue;
			$responses[$field['key']] = ($field['type'] == 'file') ? array() : $response;
			
			if ($field['type'] == 'email' && !empty($response)) {
				$customer_emails[] = trim($response);
				
				// MailChimp Integration connection
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'module_';
				if ($field['key'] == 'mailchimp' && $this->config->get($prefix . 'mailchimp_integration_status')) {
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->send(array('email' => $response, 'newsletter' => 1));
				}
			} elseif ($field['type'] == 'file' && !empty($response)) {
				$filename_array = array();
				foreach ($response as $encrypted_file) {
					if (version_compare(VERSION, '2.1', '<')) {
						$this->load->library('encryption');
					}
					if (version_compare(VERSION, '3.0', '<')) {
						$encryption = new Encryption($this->config->get('config_encryption'));
						$decrypted_file = $encryption->decrypt($encrypted_file);
					} else {
						$decrypted_file = $this->encryption->decrypt($this->config->get('config_encryption'), $encrypted_file);
					}
					
					$filename = str_replace(strrchr(basename($decrypted_file), '.'), '', basename($decrypted_file));
					
					$i = 1;
					while (file_exists(DIR_CACHE . $filename)) {
						$filename = $i . '-' . $filename;
						$i++;
					}
					
					$filename_array[] = $filename;
					$responses[$field['key']][] = $decrypted_file;
					
					if (file_exists(DIR_DOWNLOAD . $decrypted_file)) {
						copy(DIR_DOWNLOAD . $decrypted_file, DIR_CACHE . $filename);
						if (!$form['record_responses']) {
							unlink(DIR_DOWNLOAD . $decrypted_file);
						}
						
						$mail->addAttachment(DIR_CACHE . $filename);
						$files[] = DIR_CACHE . $filename;
					}
				}
				$response = $filename_array;
			}
			
			$response_string = (is_array($response)) ? nl2br(implode(', ', $response)) : nl2br($response);
			
			$replace[] = '[' . $field['key'] . ']';
			$with[] = $response_string;
			
			$field_title = strip_tags(html_entity_decode($field['title_' . $language], ENT_QUOTES, 'UTF-8'));
			$response_list_line = '<tr><td style="white-space: nowrap"><strong>' . $field_title . (strpos($field_title, ':') === false ? ':' : '') . '</strong></td> <td>' . $response_string . '</td></tr>' . "\n";
			$admin_response_list .= $response_list_line;
			if ($field['type'] != 'hidden' || !empty($field['email'])) {
				$customer_response_list .= $response_list_line;
			}
		}
		
		// Set cart contents
		$cart_contents = $this->replaceShortcodes('[cart_contents]', $form);
		$cart_contents = str_replace('Cart Contents:', '', $cart_contents);
		$responses['CartContents'] = strip_tags($cart_contents);
		
		// Record response into database
		if ($form['record_responses']) {
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "form_builder_response SET
				module_id = " . (int)$this->request->get['module_id'] . ",
				customer_id = " . (int)$this->customer->getId() . ",
				date_added = NOW(),
				ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
				response = '" . $this->db->escape(serialize($responses)) . "',
				readable_response = '" . $this->db->escape(strip_tags($admin_response_list)) . "'
			");
		}
		
		// Send out e-mails
		$admin_emails = array_map('trim', explode(',', $form['admin_email']));
		$first_admin = '';
		
		$html = html_entity_decode($form['admin_message_' . $language], ENT_QUOTES, 'UTF-8');
		$html = str_replace($replace, $with, $html);
		$html = str_replace('[form_responses]', '<table>' . $admin_response_list . '</table>', $html);
		$html = $this->replaceShortcodes($html, $form);
		
		$mail->setSender(!empty($customer_emails) ? $customer_emails[0] : str_replace(array(',', '&'), array('', 'and'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')));
		$mail->setSubject($this->replaceShortcodes(str_replace($replace, $with, $form['admin_subject_' . $language]), $form));
		$mail->setHtml($html);
		$mail->setText(strip_tags($html));
		
		foreach ($admin_emails as $email) {
			if (strpos($email, ':')) {
				$if_then = array_map('trim', explode('=', $email));
				$key_value = array_map('trim', explode(':', $if_then[0]));
				if (empty($responses[$key_value[0]]) || $responses[$key_value[0]] != $key_value[1]) {
					continue;
				}
				$email = $if_then[1];
			}
			
			if ($protocol_engine == 'smtp') {
				$mail->setFrom($this->config->get('config_email'));
			} else {
				$mail->setFrom($email);
			}
			
			$mail->setReplyTo(!empty($customer_emails) ? $customer_emails[0] : $email);
			$mail->setTo($email);
			$mail->send();
			
			if (empty($first_admin)) {
				$first_admin = $email;
			}
		}
		
		if (!empty($customer_emails) && $form['customer_email']) {
			$html = html_entity_decode($form['customer_message_' . $language], ENT_QUOTES, 'UTF-8');
			$html = str_replace($replace, $with, $html);
			$html = str_replace('[form_responses]', '<table>' . $customer_response_list . '</table>', $html);
			$html = $this->replaceShortcodes($html, $form);
			
			if ($protocol_engine == 'smtp') {
				$mail->setFrom($this->config->get('config_email'));
			} else {
				$mail->setFrom($first_admin);
			}
			
			$mail->setSender(str_replace(array(',', '&'), array('', 'and'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')));
			$mail->setSubject($this->replaceShortcodes(str_replace($replace, $with, $form['customer_subject_' . $language]), $form));
			$mail->setHtml($html);
			$mail->setText(strip_tags($html));
			
			foreach ($customer_emails as $email) {
				$mail->setTo($email);
				$mail->send();
			}
		}
		
		// Destroy files
		foreach ($files as $file) {
			if (file_exists($file)) unlink($file);
		}
		
		echo 'success';
	}
	
	//==============================================================================
	// typeahead()
	//==============================================================================
	public function typeahead() {
		$search = (strpos($this->request->get['q'], '[')) ? substr($this->request->get['q'], 0, strpos($this->request->get['q'], ' [')) : $this->request->get['q'];
		
		if ($this->request->get['type'] == 'all') {
			if (strpos($this->name, 'ultimate') === 0) {
				$tables = array('attribute_group_description', 'attribute_description', 'category_description', 'manufacturer', 'option_description', 'option_value_description', 'product_description');
			} else {
				$tables = array('category_description', 'manufacturer', 'product_description');
			}
		} elseif (in_array($this->request->get['type'], array('customer', 'manufacturer', 'zone'))) {
			$tables = array($this->request->get['type']);
		} else {
			$tables = array($this->request->get['type'] . '_description');
		}
		
		$results = array();
		foreach ($tables as $table) {
			if ($table == 'customer') {
				$query = $this->db->query("SELECT customer_id, CONCAT(firstname, ' ', lastname, ' (', email, ')') as name FROM " . DB_PREFIX . $table . " WHERE CONCAT(firstname, ' ', lastname, ' (', email, ')') LIKE '%" . $this->db->escape($search) . "%' ORDER BY name ASC LIMIT 0,100");
			} else {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . " WHERE name LIKE '%" . $this->db->escape($search) . "%' ORDER BY name ASC LIMIT 0,100");
			}
			$results = array_merge($results, $query->rows);
		}
		
		if (empty($results)) {
			$variations = array();
			for ($i = 0; $i < strlen($search); $i++) {
				$variations[] = $this->db->escape(substr_replace($search, '_', $i, 1));
				$variations[] = $this->db->escape(substr_replace($search, '', $i, 1));
				if ($i != strlen($search)-1) {
					$transpose = $search;
					$transpose[$i] = $search[$i+1];
					$transpose[$i+1] = $search[$i];
					$variations[] = $this->db->escape($transpose);
				}
			}
			foreach ($tables as $table) {
				if ($table == 'customer') {
					$query = $this->db->query("SELECT customer_id, CONCAT(firstname, ' ', lastname, ' (', email, ')') as name FROM " . DB_PREFIX . $table . " WHERE CONCAT(firstname, ' ', lastname, ' (', email, ')') LIKE '%" . implode("%' OR CONCAT(firstname, ' ', lastname, ' (', email, ')') LIKE '%", $variations) . "%' ORDER BY name ASC LIMIT 0,100");
				} else {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . " WHERE name LIKE '%" . implode("%' OR name LIKE '%", $variations) . "%' ORDER BY name ASC LIMIT 0,100");
				}
				$results = array_merge($results, $query->rows);
			}
		}
		
		$items = array();
		foreach ($results as $result) {
			if (key($result) == 'category_id') {
				$category_id = reset($result);
				$parent_exists = true;
				while ($parent_exists) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = (SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = " . (int)$category_id . " AND parent_id != " . (int)$category_id . ")");
					if (!empty($query->row['name'])) {
						$category_id = $query->row['category_id'];
						$result['name'] = $query->row['name'] . ' > ' . $result['name'];
					} else {
						$parent_exists = false;
					}
				}
			}
			//$items[] = html_entity_decode($result['name'], ENT_NOQUOTES, 'UTF-8') . ' [' . key($result) . ':' . reset($result) . ']';
			
			// extension-specific
			if ($this->request->get['type'] == 'product') {
				$product_model = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = " . (int)$result['product_id'])->row['model'];
				$result['name'] .= ' [' . $product_model . ']';
			}
			$items[] = html_entity_decode($result['name'], ENT_NOQUOTES, 'UTF-8');
			// end
		}
		
		natcasesort($items);
		echo '["' . implode('","', str_replace(array('"', '_id'), array('&quot;', ''), $items)) . '"]';
	}
}
?>