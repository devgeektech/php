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
	
	public function index() {
		$data = array(
			'type'			=> $this->type,
			'name'			=> $this->name,
			'autobackup'	=> false,
			'save_type'		=> 'keepediting',
			'permission'	=> $this->hasPermission('modify'),
		);
		
		$this->loadSettings($data);
		
		// extension-specific
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "form_builder_response` (
			`form_builder_response_id` INT(11) NOT NULL AUTO_INCREMENT,
			`module_id` INT(11) NOT NULL,
			`date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`customer_id` INT(11) NOT NULL DEFAULT '0',
			`ip` VARCHAR(40) COLLATE utf8_bin NOT NULL,
			`response` MEDIUMTEXT COLLATE utf8_bin NOT NULL,
			`readable_response` MEDIUMTEXT COLLATE utf8_bin NOT NULL,
			PRIMARY KEY (`form_builder_response_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");
		
		$module_table = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "module WHERE Field = 'setting'");
		if (strtoupper($module_table->row['Type']) == 'TEXT') {
			$this->db->query("ALTER TABLE " . DB_PREFIX . "module MODIFY `setting` MEDIUMTEXT NOT NULL");
		}
		
		//------------------------------------------------------------------------------
		// Modules
		//------------------------------------------------------------------------------
		$modules = array();
		$module_info = array();
		$module_id = 0;
		
		$this->load->model((version_compare(VERSION, '3.0', '<') ? 'extension' : 'setting') . '/module');
		
		if (isset($this->request->get['module_id'])) {
			$module_info = $this->{'model_' . (version_compare(VERSION, '3.0', '<') ? 'extension' : 'setting') . '_module'}->getModule($this->request->get['module_id']);
			$module_info['module_id'] = $this->request->get['module_id'];
			$module_layouts = array();
			
			if (!empty($module_info['module_id'])) {
				$layout_modules = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module lm LEFT JOIN " . DB_PREFIX . "layout l ON (l.layout_id = lm.layout_id) WHERE lm.code = '" . $this->db->escape($this->name . "." . $module_info['module_id']) . "'")->rows;
				foreach ($layout_modules as $layout_module) {
					$module_layouts[] = '<a href="index.php?route=design/layout/edit&layout_id=' . $layout_module['layout_id'] . '&token=' . $data['token'] . '">' . $layout_module['name'] . '</a>';
				}
			}
		} else {
			foreach ($this->{'model_' . (version_compare(VERSION, '3.0', '<') ? 'extension' : 'setting') . '_module'}->getModulesByCode($this->name) as $module) {
				$modules[$module['module_id']] = $module['name'];
			}
		}
		
		//------------------------------------------------------------------------------
		// Data Arrays
		//------------------------------------------------------------------------------
		$data['store_array'] = array(0 => $this->config->get('config_name'));
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		foreach ($store_query->rows as $store) {
			$data['store_array'][$store['store_id']] = $store['name'];
		}
		
		$data['language_array'] = array($this->config->get('config_language') => '');
		$data['language_flags'] = array();
		$this->load->model('localisation/language');
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['language_array'][$language['code']] = $language['name'];
			$data['language_flags'][$language['code']] = (version_compare(VERSION, '2.2', '<')) ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}
		
		$data['customer_group_array'] = array(0 => $data['text_guests']);
		$this->load->model((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer_group');
		foreach ($this->{'model_' . (version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '_customer_group'}->getCustomerGroups() as $customer_group) {
			$data['customer_group_array'][$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$data['currency_array'] = array($this->config->get('config_currency') => '');
		$this->load->model('localisation/currency');
		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			$data['currency_array'][$currency['code']] = $currency['code'];
		}
		
		//------------------------------------------------------------------------------
		// Extension Settings
		//------------------------------------------------------------------------------
		$data['settings'] = array();
		
		$data['settings'][] = array(
			'key'		=> 'status',
			'type'		=> 'hidden',
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'tooltips',
			'type'		=> 'hidden',
			'default'	=> 0,
		);
		
		if (!isset($this->request->get['module_id'])) {
			
			$data['save_type'] = 'none';
			
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<div class="text-info text-center pad-bottom">' . $data['help_module_locations'] . ' <a href="index.php?route=design/layout&token=' . $data['token'] . '">' . (version_compare(VERSION, '2.1', '<') ? ' System >' : '') . ' Design > Layouts</a></div>',
			);
			$data['settings'][] = array(
				'key'		=> 'form_list',
				'type'		=> 'heading',
			);
			$data['settings'][] = array(
				'key'		=> 'module_list',
				'type'		=> 'table_start',
				'columns'	=> array('module_name', 'edit_module', 'copy_module', 'delete_module'),
			);
			foreach ($modules as $module_id => $module_name) {
				$data['settings'][] = array(
					'type'		=> 'row_start',
				);
				$data['settings'][] = array(
					'key'		=> 'module_link',
					'type'		=> 'button',
					'module_id'	=> $module_id,
					'text'		=> $module_name,
				);
				$data['settings'][] = array(
					'type'		=> 'column',
				);
				$data['settings'][] = array(
					'key'		=> 'edit_module',
					'type'		=> 'button',
					'module_id'	=> $module_id,
				);
				$data['settings'][] = array(
					'type'		=> 'column',
				);
				$data['settings'][] = array(
					'key'		=> 'copy_module',
					'type'		=> 'button',
					'module_id'	=> $module_id,
				);
				$data['settings'][] = array(
					'type'		=> 'column',
				);
				$data['settings'][] = array(
					'key'		=> 'delete_module',
					'type'		=> 'button',
					'module_id'	=> $module_id,
				);
				$data['settings'][] = array(
					'type'		=> 'row_end',
				);
			}
			$data['settings'][] = array(
				'type'		=> 'table_end',
			);
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<a class="btn btn-primary" href="index.php?route=extension/' . $this->type . '/' . $this->name . '&module_id=0&token=' . $data['token'] . '"><i class="fa fa-plus pad-right"></i> ' . $data['button_create_new_form'] . '</a>',
			);
			
		} else {
			
			//------------------------------------------------------------------------------
			// Module Editing Page
			//------------------------------------------------------------------------------
			$data['exit'] = $this->url->link('extension/' . $this->type . '/' . $this->name . '&token=' . $data['token'], '', 'SSL');
			$data['module_id'] = $this->request->get['module_id'];
			
			$module_prefix = 'module_' . $data['module_id'] . '_';
			
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'module_id',
				'type'		=> 'hidden',
				'default'	=> $data['module_id'],
			);
			
			if ($data['module_id'] == 0) {
				$data['settings'][] = array(
					'key'		=> 'create_new_form',
					'type'		=> 'heading',
					'buttons'	=> '<a class="btn btn-default" onclick="$(\'#help-shortcodes\').slideToggle()">Show / Hide Shortcodes</a>',
				);
			} else {
				$data['settings'][] = array(
					'key'		=> 'edit',
					'type'		=> 'heading',
					'text'		=> $data['heading_edit'] . ' "' . (!empty($module_info['name']) ? $module_info['name'] : '(no name)') . '"',
					'buttons'	=> '<a class="btn btn-default" onclick="$(\'#help-shortcodes\').slideToggle()">Show / Hide Shortcodes</a>',
				);
				foreach ($module_info as $key => $value) {
					$data['saved'][$module_prefix . $key] = $value;
				}
			}
			
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> $data['help_shortcodes'],
			);
			
			//------------------------------------------------------------------------------
			// Form Report
			//------------------------------------------------------------------------------
			$response_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_builder_response WHERE module_id = " . (int)$data['module_id'] . " ORDER BY form_builder_response_id DESC");
			if ($response_query->num_rows) {
				
				$data['settings'][] = array(
					'type'		=> 'tabs',
					'tabs'		=> array('form_report', 'general_settings', 'display_settings', 'form_fields', 'error_messages', 'email_settings', 'restrictions'),
				);
				$data['settings'][] = array(
					'type'		=> 'html',
					'content'	=> '
						<div class="well">
							<a class="btn btn-success" onclick="delimiter = prompt(\'' . $data['text_enter_a_delimiter'] . '\', \',\'); if (delimiter) location = \'index.php?route=extension/' . $this->type . '/' . $this->name . '/exportReport&form=' . $data['module_id'] . '&delimiter=\' + delimiter + \'&token=' . $data['token'] . '\'"><i class="fa fa-download pad-right-sm"></i> ' . $data['button_export_report'] . '</a>
							&nbsp;
							<a class="btn btn-success" href="index.php?route=extension/' . $this->type . '/' . $this->name . '/exportReport&form=' . $data['module_id'] . '&summary=true&token=' . $data['token'] . '"><i class="fa fa-download pad-right-sm"></i> ' . $data['button_export_summary'] . '</a>
							<div style="float: right">
								<a class="btn btn-primary" onclick="toggleBlankResponses()"><i class="fa fa-eye-slash pad-right-sm"></i> ' . $data['button_toggle_blank_responses'] . '</a>
								&nbsp;
								<a class="btn btn-primary" onclick="$(\'#form-responses, [data-summary]\').fadeToggle()"><i class="fa fa-refresh pad-right-sm"></i> ' . $data['button_toggle_report_summary'] . '</a>
								&nbsp;
							</div>
						</div>
						
						<style type="text/css">
							#form-responses td {
								vertical-align: middle !important;
							}
							#form-responses .file-links {
								margin-bottom: 5px;
							}
							.response-table td:first-child {
								font-weight: bold;
							}
							.response-table tr:not(:last-child) td {
								border-bottom: 1px dashed #CCC;
							}
							[data-summary] {
								display: none;
							}
							[data-summary] thead {
								display: none;
							}
							.form_summary:first-child td {
								border-top: none;
							}
							.form_summary td {
								width: 50%;
							}
						</style>
						
						<script type="text/javascript">
							function toggleBlankResponses() {
								$("#form-responses tbody td").each(function(){
									if ($(this).html() == "") $(this).parent().toggle();
								});
								$("[data-summary] tbody td").each(function(){
									if ($(this).text().trim() == "") $(this).parent().toggle();
								});
							}
							
							function deleteResponse(element, id) {
								element.attr("disabled", "disabled");
								$.get("index.php?route=extension/' . $this->type . '/' . $this->name . '/deleteResponse&id=" + id + "&token=' . $data['token'] . '", function(error) {
									if (error) {
										alert(error);
										element.removeAttr("disabled");
									} else {
										element.parent().parent().parent().remove();
									}
								});
							}
						</script>
					',
				);
				
				// Responses
				$summary = array();
				$title = 'title_' . $this->config->get('config_admin_language');
				
				$data['saved'][$module_prefix . 'fields'][] = array(
					'key'	=> 'CartContents',
					$title	=> 'Cart Contents',
					'type'	=> 'cart_contents',
				);
				
				$data['settings'][] = array(
					'key'		=> 'form_responses',
					'type'		=> 'table_start',
					'columns'	=> array('action', 'customer', 'date_added', 'ip_address', 'responses'),
					'attributes'=> array('id' => 'form-responses'),
				);
				
				foreach ($response_query->rows as $response) {
					$data['settings'][] = array(
						'type'		=> 'row_start',
					);
					$data['settings'][] = array(
						'type'		=> 'html',
						'content'	=> '<a class="btn btn-danger" onclick="if (confirm(\'' . $data['standard_confirm'] . '\')) deleteResponse($(this), ' . $response['form_builder_response_id'] . ')" data-help="' . $data['button_delete'] . '"><i class="fa fa-trash-o fa-lg fa-fw"></i></a>',
					);
					$data['settings'][] = array(
						'type'		=> 'column',
					);
					
					$customer = $data['text_guest'];
					if ($response['customer_id']) {
						$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$response['customer_id']);
						if ($customer_query->num_rows) {
							$customer_link = 'index.php?route=customer/customer/edit&customer_id=' . $customer_query->row['customer_id'] . '&token=' . $data['token'];
							$customer = '<a target="_blank" href="' . $customer_link . '">' . $customer_query->row['firstname'] . ' ' . $customer_query->row['lastname'] . '</a>';
						}
					}
					
					$data['settings'][] = array(
						'type'		=> 'html',
						'content'	=> $customer,
					);
					$data['settings'][] = array(
						'type'		=> 'column',
					);
					$data['settings'][] = array(
						'type'		=> 'html',
						'content'	=> $response['date_added'],
					);
					$data['settings'][] = array(
						'type'		=> 'column',
					);
					$data['settings'][] = array(
						'type'		=> 'html',
						'content'	=> '<a target="_blank" href="index.php?route=customer/customer&filter_ip=' . $response['ip'] . '&token=' . $data['token'] . '">' . $response['ip'] . '</a>',
					);
					$data['settings'][] = array(
						'type'		=> 'column',
					);
					
					$response_text = '';
					$responses = unserialize($response['response']);
					
					foreach ($responses as $key => $value) {
						foreach ($data['saved'][$module_prefix . 'fields'] as $field) {
							if ($key != $field['key'] || !isset($value)) continue;
							
							if ($field['type'] == 'file' && $value) {
								$file_links = '';
								foreach ($value as $file) {
									$file_links .= '<div class="file-links"><a href="index.php?route=extension/' . $this->type . '/' . $this->name . '/downloadFile&filename=' . $file . '&token=' . $data['token'] . '">' . str_replace(strrchr(basename($file), '.'), '', basename($file)) . '</a></div>';
								}
								$value = $file_links;
							}
							
							$blank = ($value == '' || $value == array()) ? ' class="blank-response"' : '';
							if (is_array($value)) $value = implode('; ', $value);
							$value = nl2br($value);
							$response_text .= '<tr' . $blank . '><td>' . html_entity_decode($field[$title], ENT_QUOTES, 'UTF-8') . '</td><td>' . $value . '</td></tr>';
							
							// Summary data
							if ($field['type'] == 'file') continue;
							
							$k = $field['title_' . $this->config->get('config_admin_language')];
							
							if (!isset($summary[$k][$value])) $summary[$k][$value] = 0;
							$summary[$k][$value]++;
						}
					}
					
					$data['settings'][] = array(
						'type'		=> 'html',
						'content'	=> '<table class="response-table table-condensed">' . $response_text . '</table>',
					);
					$data['settings'][] = array(
						'type'		=> 'row_end',
					);
				}
				$data['settings'][] = array(
					'type'		=> 'table_end',
					'buttons'	=> '',
				);				
				
				// Summary
				foreach ($summary as $key => $value_array) {
					arsort($value_array);
					
					$data['settings'][] = array(
						'type'		=> 'heading',
						'text'		=> html_entity_decode($key, ENT_QUOTES, 'UTF-8'),
						'attributes'=> array('data-summary' => 'true'),
					);
					$data['settings'][] = array(
						'key'		=> 'form_summary',
						'type'		=> 'table_start',
						'columns'	=> array('', ''),
						'attributes'=> array('data-summary' => 'true'),
					);
					foreach ($value_array as $value => $count) {
						$data['settings'][] = array(
							'type'		=> 'row_start',
						);
						$data['settings'][] = array(
							'type'		=> 'html',
							'content'	=> $value,
						);
						$data['settings'][] = array(
							'type'		=> 'column',
						);
						$data['settings'][] = array(
							'type'		=> 'html',
							'content'	=> $count,
						);
						$data['settings'][] = array(
							'type'		=> 'row_end',
						);
					}
					$data['settings'][] = array(
						'type'		=> 'table_end',
						'buttons'	=> '',
					);				
				}
				
				// General Settings tab
				$data['settings'][] = array(
					'key'		=> 'general_settings',
					'type'		=> 'tab',
				);
				
			} else {
				
				$data['settings'][] = array(
					'type'		=> 'tabs',
					'tabs'		=> array('general_settings', 'display_settings', 'form_fields', 'error_messages', 'email_settings', 'restrictions'),
				);
				
			}
			
			//------------------------------------------------------------------------------
			// General Settings
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'status',
				'type'		=> 'select',
				'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'name',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'record_responses',
				'type'		=> 'select',
				'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
				'default'	=> 1,
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'heading',
				'type'		=> 'multilingual_text',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'file_size',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'file_extensions',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'date_format',
				'type'		=> 'text',
				'default'	=> 'yyyy-mm-dd',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'time_format',
				'type'		=> 'text',
				'default'	=> 'h:i A',
			);
			
			if (version_compare(VERSION, '2.0.2', '<')) {
				$data['settings'][] = array(
					'key'		=> $module_prefix . 'recaptcha_site_key',
					'type'		=> 'text',
				);
				$data['settings'][] = array(
					'key'		=> $module_prefix . 'recaptcha_secret_key',
					'type'		=> 'text',
				);
			}
			
			//------------------------------------------------------------------------------
			// Display Settings
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'display_settings',
				'type'		=> 'tab',
			);
			
			$data['settings'][] = array(
				'type'		=> 'html',
				'title'		=> $data['entry_module_locations'],
				'content'	=> '
					<div style="margin-top: -7px">' . $data['help_module_locations'] . ' <a href="index.php?route=design/layout&token=' . $data['token'] . '">' . (version_compare(VERSION, '2.1', '<') ? ' System >' : '') . ' Design > Layouts</a>
					<br /><br />
					' . $data['help_assigned_layouts'] . ' ' . implode(', ', $module_layouts) . '</div>
				',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'positioning',
				'type'		=> 'select',
				'options'	=> array('block' => $data['text_block'], 'absolute' => $data['text_absolute']),
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'row_height',
				'type'		=> 'text',
				'default'	=> 50,
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'title_placement',
				'type'		=> 'select',
				'options'	=> array(
					'above'		=> $data['text_above'],
					'inline'	=> $data['text_inline'],
					'mobile'	=> $data['text_inline_for_mobile_devices'],
				),
			);
			
			$data['settings'][] = array(
				'key'		=> 'create_form_page',
				'type'		=> 'html',
				'content'	=> (empty($data['module_id'])) ? '<br /><em>' . $data['text_you_must_save_the_form'] . '</em>' : '
					<a class="btn btn-primary" onclick="createFormPage($(this))"><i class="fa fa-file-text pad-right-sm"></i> ' . $data['button_create_form_page'] . '</a>
					<script type="text/javascript">
						function createFormPage(element) {
							if (confirm("' . $data['standard_confirm'] . '")) {
								element.attr("disabled", "disabled");
								var formName = $("#input-module_' . $data['module_id'] . '_name").val();
								$.get("index.php?route=extension/' . $this->type . '/' . $this->name . '/createFormPage&name=" + formName + "&module_id=' . $data['module_id'] . '&token=' . $data['token'] . '",
									function (data) {
										if (data.split(":")[0] == "success") {
											$("#input-module_' . $data['module_id'] . '_layout_id").append("<option selected=\"selected\" value=\"" + data.split(":")[1] + "\">Form Layout: " + formName + "</option");
											$("#input-module_' . $data['module_id'] . '_position").val("content_bottom");
											$("#input-module_' . $data['module_id'] . '_sort_order").val("1");
											$("#input-module_' . $data['module_id'] . '_additional_css").append("\n#content > h1:first-child, .buttons { display: none; }");
											alert("' . $data['standard_success'] . '");
										} else {
											alert(data);
										}
										element.removeAttr("disabled");
									}
								);
							}
						}
					</script>
				',
			);
			
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'additional_css',
				'type'		=> 'textarea',
				'attributes'=> array('placeholder' => ($data['module_id']) ? $data['placeholder_additional_css'] . $data['module_id'] : ''),
			);

			//------------------------------------------------------------------------------
			// Form Fields
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'form_fields',
				'type'		=> 'tab',
			);
			
			ob_start();
			include_once(DIR_APPLICATION . 'view/template/extension/module/form_builder_pro_fields.twig');
			$fields = ob_get_contents();
			ob_end_clean();
			
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> $fields,
			);
			
			//------------------------------------------------------------------------------
			// Error Messages
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'error_messages',
				'type'		=> 'tab',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_required',
				'type'		=> 'multilingual_text',
				'default'	=> 'Please fill in all required fields',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_captcha',
				'type'		=> 'multilingual_text',
				'default'	=> 'Please verify that you are not a robot',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_invalid_email',
				'type'		=> 'multilingual_text',
				'default'	=> 'Please use a valid e-mail address format',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_email_mismatch',
				'type'		=> 'multilingual_text',
				'default'	=> 'E-mail address does not match confirmation',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_minlength',
				'type'		=> 'multilingual_text',
				'default'	=> 'Please enter at least [min] characters',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_file_name',
				'type'		=> 'multilingual_text',
				'default'	=> 'File name must be between 3 and 128 characters',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_file_size',
				'type'		=> 'multilingual_text',
				'default'	=> 'File size is too large',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_file_ext',
				'type'		=> 'multilingual_text',
				'default'	=> 'File extension is not allowed',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_file_limit',
				'type'		=> 'multilingual_text',
				'default'	=> 'No more files can be uploaded',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'error_file_upload',
				'type'		=> 'multilingual_text',
				'default'	=> 'File upload error',
			);

			//------------------------------------------------------------------------------
			// E-mail Settings
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'email_settings',
				'type'		=> 'tab',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'admin_email',
				'type'		=> 'text',
				'default'	=> $this->config->get('config_email'),
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'admin_subject',
				'type'		=> 'multilingual_text',
				'default'	=> '[store_name]: [form_name] response',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'admin_message',
				'type'		=> 'multilingual_textarea',
				'default'	=> "<p>You have received a response to your [form_name] form, with the following responses:</p>\n\n<p>[form_responses]</p>",
				'class'		=> 'summernote',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'customer_email',
				'type'		=> 'select',
				'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
				'default'	=> 1,
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'customer_subject',
				'type'		=> 'multilingual_text',
				'default'	=> '[store_name]: [form_name] submitted',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'customer_message',
				'type'		=> 'multilingual_textarea',
				'default'	=> "<p>Thank you for your submission! We will respond to your inquiry as soon as possible. A copy of your responses is included below. Thanks again!</p>\n\n<p>[store_name]<br />[store_url]</p>\n\n<p>[form_responses]</p>",
				'class'		=> 'summernote',
			);
			
			//------------------------------------------------------------------------------
			// Restrictions
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'restrictions',
				'type'		=> 'tab',
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'stores',
				'type'		=> 'checkboxes',
				'options'	=> $data['store_array'],
				'default'	=> array_keys($data['store_array']),
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'languages',
				'type'		=> 'checkboxes',
				'options'	=> $data['language_array'],
				'default'	=> array_keys($data['language_array']),
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'customer_groups',
				'type'		=> 'checkboxes',
				'options'	=> $data['customer_group_array'],
				'default'	=> array_keys($data['customer_group_array']),
			);
			$data['settings'][] = array(
				'key'		=> $module_prefix . 'currencies',
				'type'		=> 'checkboxes',
				'options'	=> $data['currency_array'],
				'default'	=> array_keys($data['currency_array']),
			);
			
		}
		
		//------------------------------------------------------------------------------
		// end settings
		//------------------------------------------------------------------------------
		
		$this->document->addStyle('view/javascript/' . $this->name . '/jquery.gridster.min.css');
		$this->document->addScript('view/javascript/' . $this->name . '/jquery.gridster.min.js');
		$this->document->addStyle('view/javascript/' . $this->name . '/summernote.css');
		$this->document->addScript('view/javascript/' . $this->name . '/summernote.min.js');
		
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
				$output = str_replace('&token=', '&user_token=', $output);
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
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($this->name) . "' AND `key` = '" . $this->db->escape($this->name . '_' . str_replace('[]', '', $this->request->get['setting'])) . "'");
	}
	
	//==============================================================================
	// Module functions
	//==============================================================================
	public function copyModule() {
		$module_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = " . (int)$this->request->get['module_id']);
		$module_settings = (version_compare(VERSION, '2.1', '<')) ? unserialize($module_query->row['setting']) : json_decode($module_query->row['setting'], true);
		$module_settings['name'] .= ' (Copy)';
		$this->db->query("INSERT INTO " . DB_PREFIX . "module SET `name` = '" . $this->db->escape($module_settings['name']) . "', `code` = '" . $this->db->escape($this->name) . "', setting = '" . $this->db->escape(version_compare(VERSION, '2.1', '<') ? serialize($module_settings) : json_encode($module_settings)) . "'");
	}
	
	public function deleteModule() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "module WHERE module_id = " . (int)$this->request->get['module_id']);
		
		// extension-specific
		$this->db->query("DELETE FROM " . DB_PREFIX . "form_builder_response WHERE module_id = " . (int)$this->request->get['module_id']);
		// end
	}
	
	//==============================================================================
	// Custom functions
	//==============================================================================
	public function deleteResponse() {
		if (empty($this->request->get['id'])) return 'No form_builder_response_id set';
		$this->db->query("DELETE FROM " . DB_PREFIX . "form_builder_response WHERE form_builder_response_id = " . (int)$this->request->get['id']);
	}
	
	public function downloadFile() {
		if (empty($this->request->get['filename'])) return;
		$file = DIR_DOWNLOAD . $this->request->get['filename'];
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="' . str_replace(strrchr(basename($file), '.'), '', basename($file)) . '"');
		header('Content-Length: ' . filesize($file));
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: application/octet-stream');
		header('Expires: 0');
		header('Pragma: public');
		readfile($file);
	}
	
	public function exportReport() {
		if (empty($this->request->get['form'])) return;
		
		$response_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_builder_response WHERE module_id = " . (int)$this->request->get['form'] . " ORDER BY form_builder_response_id DESC");
		if (!$response_query->num_rows) return;
		
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: text/csv');
		header('Expires: 0');
		header('Pragma: public');
		
		if (empty($this->request->get['summary'])) {
			header('Content-Disposition: attachment; filename="form_' . (int)$this->request->get['form'] . '_report.' . date('Y-n-d') . '.csv"');
			
			echo ' FORM_BUILDER_RESPONSE_ID, MODULE_ID, DATE_ADDED, CUSTOMER_ID, IP, RESPONSE' . "\n";
			
			foreach ($response_query->rows as $response) {
				unset($response['response']);
				$response['readable_response'] = str_replace("\r\n", ' ', $response['readable_response']);
				echo implode(',', str_replace(array(',', "\n"), array(';', $this->request->get['delimiter']), $response)) . "\n";
			}
		} else {
			header('Content-Disposition: attachment; filename="form_' . (int)$this->request->get['form'] . '_summary.' . date('Y-n-d') . '.csv"');
			
			$summary = array();
			
			foreach ($response_query->rows as $response) {
				$responses = unserialize($response['response']);
				foreach ($responses as $key => $value) {
					if (is_array($value)) $value = implode('; ', $value);
					
					if (!isset($summary[$key][$value])) $summary[$key][$value] = 0;
					$summary[$key][$value]++;
				}
			}
			
			echo ' FIELD_KEY, RESPONSE, COUNT' . "\n";
			
			foreach ($summary as $key => $value_array) {
				arsort($value_array);
				foreach ($value_array as $value => $count) {
					echo $key . ',' . $value . ',' . $count . "\n";
				}
			}
		}
	}
	
	public function createFormPage() {
		if (!$this->hasPermission('modify') ||
			!$this->user->hasPermission('modify', 'catalog/information') ||
			!$this->user->hasPermission('modify', 'design/layout')
		) {
			return;
		}
		
		$response = '';
		
		$layout_name = 'Form Layout: ' . $this->request->get['name'];
		$this->load->model('design/layout');
		$this->model_design_layout->addLayout(array(
			'name'			=> $layout_name,
			'layout_module'	=> array(
				array(
					'code'			=> $this->name . '.' . $this->request->get['module_id'],
					'position'		=> 'content_bottom',
					'sort_order'	=> 1,
				)
			),
		));
		$layout_id = $this->db->query("SELECT layout_id FROM " . DB_PREFIX . "layout ORDER BY layout_id DESC")->row['layout_id'];
		
		$info_query = $this->db->query("DESCRIBE " . DB_PREFIX . "information");
		$extra_info_cols = array();
		foreach ($info_query->rows as $col) {
			if (in_array($col['Field'], array('information_id', 'bottom', 'sort_order', 'status'))) continue;
			$extra_info_cols[] = $col['Field'];
		}
		
		$info_description_query = $this->db->query("DESCRIBE " . DB_PREFIX . "information_description");
		$extra_description_cols = array();
		foreach ($info_description_query->rows as $col) {
			if (in_array($col['Field'], array('information_description_id', 'information_id', 'language_id', 'title', 'description', 'meta_title', 'meta_description', 'meta_keyword'))) continue;
			$extra_description_cols[] = $col['Field'];
		}
		
		$language_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
		$info_description = array();
		foreach ($language_query->rows as $language) {
			$info_description[$language['language_id']] = array(
				'title'			=> $this->request->get['name'],
				'description'	=> '   ',
				'meta_title'	=> $this->request->get['name'],
				'meta_description'	=> '',
				'meta_keyword'		=> '',
			);
			foreach ($extra_description_cols as $col) {
				$info_description[$language['language_id']][$col] = '';
			}
		}
		
		$info_store = array(0);
		$info_layout = array(0 => $layout_id);
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$info_store[] = $store['store_id'];
			$info_layout[$store['store_id']] = $layout_id;
		}
		
		$data = array(
			'sort_order'				=> 1,
			'bottom'					=> 1,
			'status'					=> 1,
			'information_description'	=> $info_description,
			'information_store'			=> $info_store,
			'information_layout'		=> $info_layout,
			'keyword'					=> strtolower(str_replace(' ', '-', $this->request->get['name'])),
		);
		foreach ($extra_info_cols as $col) {
			$data[$col] = '';
		}
		$this->load->model('catalog/information');
		$this->model_catalog_information->addInformation($data);
		
		echo 'success:' . $layout_id;
	}
}
?>