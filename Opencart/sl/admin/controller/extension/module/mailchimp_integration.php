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

class ControllerExtensionModuleMailchimpIntegration extends Controller {
	private $type = 'module';
	private $name = 'mailchimp_integration';
	
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
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->registry);
		
		$lists = $mailchimp_integration->getLists();
		
		unset($this->session->data['mailchimp_interest_groups']);
		unset($this->session->data['mailchimp_interests']);
		setcookie($this->name . '_popup', '', -1, '/');
		
		//------------------------------------------------------------------------------
		// Data Arrays
		//------------------------------------------------------------------------------
		$data['customer_group_array'] = array(0 => $data['text_guests']);
		$this->load->model((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer_group');
		foreach ($this->{'model_' . (version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '_customer_group'}->getCustomerGroups() as $customer_group) {
			$data['customer_group_array'][$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$data['language_array'] = array($this->config->get('config_language') => '');
		$data['language_flags'] = array();
		$this->load->model('localisation/language');
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['language_array'][$language['code']] = $language['name'];
			$data['language_flags'][$language['code']] = (version_compare(VERSION, '2.2', '<')) ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}
		
		if (!empty($data['saved']['apikey'])) {
			$data['mailchimp_lists'] = array(0 => $data['standard_select']);
			foreach ($lists as $list) {
				$data['mailchimp_lists'][$list['id']] = $list['name'];
			}
		}
		if (empty($data['saved']['apikey']) || !empty($data['mailchimp_lists']['error'])) {
			$data['mailchimp_lists'] = array(0 => $data['text_enter_an_api_key']);
		}
		
		$module_layouts = array();
		$layout_modules = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module lm LEFT JOIN " . DB_PREFIX . "layout l ON (l.layout_id = lm.layout_id) WHERE lm.code = '" . $this->db->escape($this->name) . "'")->rows;
		foreach ($layout_modules as $layout_module) {
			$module_layouts[] = '<a href="index.php?route=design/layout/edit&layout_id=' . $layout_module['layout_id'] . '&token=' . $data['token'] . '">' . $layout_module['name'] . '</a>';
		}
		
		// Pro-specific
		$mailchimp_lists = $data['mailchimp_lists'];
		array_shift($mailchimp_lists);
		
		$selected_lists = array();
		foreach ($data['saved'] as $key => $value) {
			if ($key == 'listid' || preg_match('/mapping_(\d+)_list/', $key)) {
				$selected_lists[] = $value;
			}
		}
		
		$data['currency_array'] = array($this->config->get('config_currency') => '');
		$this->load->model('localisation/currency');
		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			$data['currency_array'][$currency['code']] = $currency['code'];
		}
		
		$data['geo_zone_array'] = array(0 => $data['text_everywhere_else']);
		$this->load->model('localisation/geo_zone');
		foreach ($this->model_localisation_geo_zone->getGeoZones() as $geo_zone) {
			$data['geo_zone_array'][$geo_zone['geo_zone_id']] = $geo_zone['name'];
		}
		
		$data['order_status_array'] = array();
		$this->load->model('localisation/order_status');
		foreach ($this->model_localisation_order_status->getOrderStatuses() as $order_status) {
			$data['order_status_array'][$order_status['order_status_id']] = $order_status['name'];
		}
		
		$data['store_array'] = array(0 => $this->config->get('config_name'));
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		foreach ($store_query->rows as $store) {
			$data['store_array'][$store['store_id']] = $store['name'];
		}
		
		$data['rule_options'] = array(
			'location_criteria'	=> array('city', 'geo_zone', 'postcode'),
			'order_criteria'	=> array('currency', 'customer_group', 'language', 'store'),
		);
		
		if (!empty($data['saved']['apikey'])) {
			foreach ($data['mailchimp_lists'] as $list_id => $list_name) {
				if (!$list_id || (!empty($selected_lists) && !in_array($list_id, $selected_lists))) continue;
				$data['merge_fields'][$list_id] = $mailchimp_integration->getMergeFields($list_id);
				$data['interest_groups'][$list_id] = $mailchimp_integration->getInterestGroups($list_id);
			}
		}
		
		$customer_fields = array('' => $data['text_leave_blank']);
		foreach (array('address', 'customer') as $table) {
			$customer_fields[$table] = '';
			$columns = array();
			foreach ($this->db->query("DESCRIBE " . DB_PREFIX . $table)->rows as $column) {
				$columns[$table . ':' . $column['Field']] = $column['Field'];
				if (in_array($column['Field'], array('customer_group_id', 'country_id', 'zone_id'))) {
					$columns[$table . ':' . str_replace('_id', '_name', $column['Field'])] = str_replace('_id', '_name', $column['Field']);
				}
			}
			asort($columns);
			$customer_fields = array_merge($customer_fields, $columns);
		}
		
		$customer_fields['custom_field'] = '';
		$custom_fields = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_description WHERE language_id = " . (int)$this->config->get('config_language_id'))->rows;
		foreach ($custom_fields as $custom_field) {
			$customer_fields['custom_field:' . $custom_field['custom_field_id']] = $custom_field['name'];
		}
		unset($custom_field);
		
		//------------------------------------------------------------------------------
		// Extension Settings
		//------------------------------------------------------------------------------
		$data['settings'] = array();
		
		if (empty($data['saved'])) {
			$data['save_type'] = 'reload';
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '
					<div id="apikey-modal" class="modal fade" data-backdrop="static">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">' . $data['entry_apikey'] . '</h4>
								</div>
								<div class="modal-body">
									<input type="text" class="form-control " name="apikey" style="width: 300px !important">
								</div>
								<div class="modal-footer">
									<a id="save-button" onclick="saveSettings($(this))" class="btn btn-primary" style="color: white"><i class="fa fa-floppy-o pad-right-sm"></i> ' . $data['button_save'] . '</a>
								</div>
							</div>
						</div>
					</div>
					<script>
						$(document).ready(function(){
							$("#apikey-modal").modal("show");
						});
					</script>
				',
			);
		}
		
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '
				<div id="syncing">' . $data['text_syncing'] . '</div>
				
				<style type="text/css">
					#syncing {
						display: none;
						background: #000;
						opacity: 0.5;
						color: #FFF;
						font-size: 100px;
						text-align: center;
						position: fixed;
						top: 0;
						left: 0;
						height: 100%;
						width: 100%;
						padding-top: 10%;
						z-index: 10000;
					}
				</style>
				
				<script>
					$.get("index.php?route=extension/' . $this->type . '/' . $this->name . '/generateBackgroundData&token=' . $data['token'] . '", function(data) {
						console.log(data);
					});
				</script>
			',
		);
		
		$data['settings'][] = array(
			'type'		=> 'tabs',
			'tabs'		=> array('extension_settings', 'list_settings', 'merge_fields', 'interest_groups', 'ecommerce', 'module_settings', 'testing_mode'),
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center">' . $data['help_extension_settings'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'extension_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'status',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
		);
		if (!empty($data['saved'])) {
			$data['settings'][] = array(
				'key'		=> 'apikey',
				'type'		=> 'text',
				'attributes'=> array('style' => 'width: 300px !important'),
			);
		}
		$data['settings'][] = array(
			'key'		=> 'double_optin',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'webhooks',
			'type'		=> 'checkboxes',
			'options'	=> array(
				'subscribe'		=> $data['text_subscribes'],
				'unsubscribe'	=> $data['text_unsubscribes'],
				'profile'		=> $data['text_profile_updates'],
				'cleaned'		=> $data['text_cleaned_addresses'],
			),
		);
		
		$customer_groups = $data['customer_group_array'];
		$customer_groups[0] = $data['text_no_change'];
		$data['settings'][] = array(
			'key'		=> 'subscribed_group',
			'type'		=> 'select',
			'options'	=> $customer_groups,
		);
		$data['settings'][] = array(
			'key'		=> 'unsubscribed_group',
			'type'		=> 'select',
			'options'	=> $customer_groups,
		);
		
		$data['settings'][] = array(
			'key'		=> 'manual_sync',
			'type'		=> 'html',
			'content'	=> '
				' . $data['text_starting_customer_id'] . ' <input type="text" id="starting-customer-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				' . $data['text_ending_customer_id'] . ' <input type="text" id="ending-customer-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				<a class="btn btn-primary" onclick="sync(\'customer\')">' . $data['button_sync_subscribers'] . '</a>
				<script type="text/javascript">
					function sync(syncType) {
						if (confirm("' . $data['text_sync_note'] . '")) {
							$("#syncing").fadeIn();
							
							var start = $("#starting-" + syncType + "-id").val();
							var end = $("#ending-" + syncType + "-id").val();
							
							$.ajax({
								url: "index.php?route=extension/' . $this->type . '/' . $this->name . '/sync&token=' . $data['token'] . '&type=" + syncType + "&start=" + start + "&end=" + end,
								success: function(data) {
									alert(data);
									$("#syncing").fadeOut();
								},
								error: function(xhr, status, error) {
									alert(xhr.responseText ? xhr.responseText : error);
									$("#syncing").fadeOut();
								}
							});
						}
					}
				</script>
			',
		);
		
		//------------------------------------------------------------------------------
		// Customer Creation Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'customer_creation_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'autocreate',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_no'], 1 => $data['text_yes_disabled'], 2 => $data['text_yes_enabled']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'autocreate_lists',
			'type'		=> 'checkboxes',
			'options'	=> $mailchimp_lists,
		);
		$data['settings'][] = array(
			'key'		=> 'email_password',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'emailtext_subject',
			'type'		=> 'multilingual_text',
			'default'	=> '[store]: Customer Account Created',
		);
		$data['settings'][] = array(
			'key'		=> 'emailtext_body',
			'type'		=> 'multilingual_textarea',
			'default'	=> "Your customer account has been successfully created. Your new password is:\n<br /><br />\n[password]\n<br /><br />\nThanks for choosing [store]!",
			'attributes'=> array('style' => 'height: 120px !important'),
		);
		
		//------------------------------------------------------------------------------
		// List Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'list_settings',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'key'		=> 'list_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'listid',
			'type'		=> 'select',
			'options'	=> $data['mailchimp_lists'],
		);
		
		// List Mappings
		$data['settings'][] = array(
			'key'		=> 'list_mapping',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['help_list_mapping'] . '</div>',
		);
		
		$table = 'mapping';
		$sortby = 'list';
		$data['settings'][] = array(
			'key'		=> $table,
			'type'		=> 'table_start',
			'columns'	=> array('action', 'list', 'rules'),
		);
		
		foreach ($this->getTableRowNumbers($data, $table, $sortby) as $num => $rules) {
			$prefix = $table . '_' . $num . '_';
			$data['settings'][] = array(
				'type'		=> 'row_start',
			);
			$data['settings'][] = array(
				'key'		=> 'delete',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'list',
				'type'		=> 'select',
				'options'	=> $data['mailchimp_lists'],
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'rule',
				'type'		=> 'rule',
				'rules'		=> $rules,
			);
			$data['settings'][] = array(
				'type'		=> 'row_end',
			);
		}
		
		$data['settings'][] = array(
			'type'		=> 'table_end',
			'buttons'	=> 'add_row',
			'text'		=> 'button_add_mapping',
		);
		
		//------------------------------------------------------------------------------
		// Merge Fields
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'merge_fields',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center">' . $data['help_merge_fields'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'merge_fields',
			'type'		=> 'heading',
		);
		if (!empty($data['merge_fields'])) {
			foreach ($data['merge_fields'] as $list_id => $merge_fields) {
				$data['settings'][] = array(
					'type'		=> 'html',
					'content'	=> '<hr />',
				);
				foreach ($merge_fields as $merge) {
					if ($merge['tag'] == 'EMAIL') {
						continue;
					} elseif ($merge['tag'] == 'FNAME') {
						$default = 'customer:firstname';
					} elseif ($merge['tag'] == 'LNAME') {
						$default = 'customer:lastname';
					} elseif ($merge['tag'] == 'ADDRESS') {
						$default = 'customer:address_id';
					} elseif ($merge['tag'] == 'PHONE') {
						$default = 'customer:telephone';
					} else {
						$default = '';
					}
					$data['settings'][] = array(
						'key'		=> $list_id . '_' . $merge['tag'],
						'title'		=> $data['mailchimp_lists'][$list_id] . ' - ' . $merge['tag'] . ':',
						'type'		=> 'select',
						'options'	=> $customer_fields,
						'default'	=> $default,
					);
				}
			}
		}
		
		//------------------------------------------------------------------------------
		// Interest Groups
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'interest_groups',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center">' . $data['help_interestgroups'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'interest_groups',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'interest_groups',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'display_routes',
			'type'		=> 'textarea',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_interestgroups',
			'type'		=> 'multilingual_text',
			'default'	=> 'Please choose your interests below',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_updatebutton',
			'type'		=> 'multilingual_text',
			'default'	=> 'Update',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_updated',
			'type'		=> 'multilingual_text',
			'default'	=> 'Your interests have been successfully updated.',
		);
		if (!empty($data['interest_groups'])) {
			foreach ($data['interest_groups'] as $list_id => $interest_groups) {
				if (empty($interest_groups)) continue;
				$data['settings'][] = array(
					'type'		=> 'heading',
					'text'		=> '"' . $data['mailchimp_lists'][$list_id] . '" ' . $data['heading_interest_groups'],
				);
				$data['settings'][] = array(
					'type'		=> 'html',
					'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_interestgroup_text'] . '</div>',
				);
				foreach ($interest_groups as $interest_group) {
					$data['settings'][] = array(
						'type'		=> 'html',
						'content'	=> '<hr />',
					);
					$data['settings'][] = array(
						'key'		=> $list_id . '_' . $interest_group['id'],
						'title'		=> '<strong>"' . $interest_group['title'] . '" ' . $data['entry_group_title'] . '</strong>',
						'type'		=> 'multilingual_text',
						'default'	=> $interest_group['title'],
					);
					foreach ($interest_group['interests'] as $interest) {
						$data['settings'][] = array(
							'key'		=> $list_id . '_' . $interest_group['id'] . '_' . $interest['id'],
							'title'		=> '"' . $interest['name'] . '" ' . $data['entry_option'],
							'type'		=> 'multilingual_text',
							'default'	=> $interest['name'],
						);
					}
				}
			}
		}
		
		//------------------------------------------------------------------------------
		// E-commerce
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'ecommerce',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center">' . $data['help_ecommerce'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'ecommerce',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'ecommerce360',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
		);
		$data['settings'][] = array(
			'key'		=> 'sendcarts',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'ordertype',
			'type'		=> 'select',
			'options'	=> array('all' => $data['text_send_all_orders'], 'newsletter' => $data['text_send_newsletter_orders']),
			'default'	=> 'all',
		);
		$data['settings'][] = array(
			'key'		=> 'cookietime',
			'type'		=> 'text',
			'class'		=> 'short',
			'default'	=> '30',
		);
		$data['settings'][] = array(
			'key'		=> 'vendor_field',
			'type'		=> 'select',
			'options'	=> array('manufacturer' => $data['text_manufacturer'], 'category' => $data['text_category']),
			'default'	=> 'manufacturer',
		);
		$data['settings'][] = array(
			'key'		=> 'product_prices',
			'type'		=> 'select',
			'options'	=> array('untaxed' => $data['text_untaxed_prices'], 'taxed' => $data['text_taxed_prices']),
			'default'	=> 'untaxed',
		);
		$data['settings'][] = array(
			'key'		=> 'past_orders_sync',
			'type'		=> 'html',
			'content'	=> '
				' . $data['text_starting_order_id'] . ' <input type="text" id="starting-order-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				' . $data['text_ending_order_id'] . ' <input type="text" id="ending-order-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				<a class="btn btn-primary" onclick="sync(\'order\')">' . $data['button_sync_orders'] . '</a>
			',
		);
		$data['settings'][] = array(
			'key'		=> 'products_sync',
			'type'		=> 'html',
			'content'	=> '
				' . $data['text_starting_product_id'] . ' <input type="text" id="starting-product-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				' . $data['text_ending_product_id'] . ' <input type="text" id="ending-product-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				<a class="btn btn-primary" onclick="sync(\'product\')">' . $data['button_sync_products'] . '</a>
			',
		);
		
		// Stores
		$data['settings'][] = array(
			'key'		=> 'stores',
			'type'		=> 'heading',
		);
		foreach ($data['store_array'] as $store_id => $store_name) {
			$data['settings'][] = array(
				'key'		=> 'store-' . $store_id . '-list',
				'type'		=> 'select',
				'options'	=> $data['mailchimp_lists'],
				'title'		=> $store_name . ': <div class="help-text">' . $data['help_stores'] . '</div>',
			);
		}
		
		// Order Statuses
		$data['settings'][] = array(
			'key'		=> 'order_statuses',
			'type'		=> 'heading',
		);
		
		$complete_status_id = $this->config->get('config_processing_status');
		$complete_status_id = $complete_status_id[0];
		
		$data['settings'][] = array(
			'key'		=> 'orderstatus',
			'type'		=> 'checkboxes',
			'options'	=> $data['order_status_array'],
			'default'	=> $complete_status_id,
		);
		$data['settings'][] = array(
			'key'		=> 'deletestatus',
			'type'		=> 'checkboxes',
			'options'	=> $data['order_status_array'],
		);
		
		$data['order_status_array'] = array(0 => $data['text_do_not_send']) + $data['order_status_array'];
		
		$data['settings'][] = array(
			'key'		=> 'orderstatus_refunded',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
			'default'	=> 11,
		);
		$data['settings'][] = array(
			'key'		=> 'orderstatus_cancelled',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
			'default'	=> 7,
		);
		$data['settings'][] = array(
			'key'		=> 'orderstatus_shipped',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
			'default'	=> 3,
		);
		$data['settings'][] = array(
			'key'		=> 'orderstatus_paid',
			'type'		=> 'select',
			'options'	=> $data['order_status_array'],
		);
		
		//------------------------------------------------------------------------------
		// Module Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'module_settings',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center">' . $data['help_module_settings'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'module_settings',
			'type'		=> 'heading',
		);
		
		$mailchimp_lists['allow_multiple'] = '<em>' . $data['text_allow_multiple_selections'] . '</em>';
		
		$data['settings'][] = array(
			'key'		=> 'modules_lists',
			'type'		=> 'checkboxes',
			'options'	=> $mailchimp_lists,
		);
		foreach (array('firstname', 'lastname', 'telephone', 'address', 'city', 'postcode') as $field) {
			$data['settings'][] = array(
				'key'		=> 'modules_' . $field,
				'type'		=> 'select',
				'options'	=> array('hide' => $data['text_hide'], 'optional' => $data['text_optional'], 'required' => $data['text_required']),
			);
		}
		$data['settings'][] = array(
			'key'		=> 'modules_zone',
			'type'		=> 'select',
			'options'	=> array('hide' => $data['text_hide'], 'show' => $data['text_show']),
		);
		$data['settings'][] = array(
			'key'		=> 'modules_country',
			'type'		=> 'select',
			'options'	=> array('hide' => $data['text_hide'], 'show' => $data['text_show']),
		);
		
		$data['settings'][] = array(
			'key'		=> 'modules_redirect',
			'type'		=> 'text',
		);
		$data['settings'][] = array(
			'key'		=> 'modules_popup',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_no'], 'manual' => $data['text_yes_trigger_manually'], 'auto' => $data['text_yes_trigger_automatically']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'modules_popup_delay',
			'type'		=> 'text',
			'class'		=> 'short',
		);
		$data['settings'][] = array(
			'key'		=> 'modules_popup_x',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'modules_popup_cookie',
			'type'		=> 'text',
			'class'		=> 'short',
		);
		
		// Module Text
		$data['settings'][] = array(
			'key'		=> 'module_text',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_heading',
			'type'		=> 'multilingual_text',
			'default'	=> 'Newsletter',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_top',
			'type'		=> 'multilingual_text',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_list',
			'type'		=> 'multilingual_text',
			'default'	=> 'List:',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_button',
			'type'		=> 'multilingual_text',
			'default'	=> 'Subscribe',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_emptyfield',
			'type'		=> 'multilingual_text',
			'default'	=> 'Please fill in the required fields!',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_invalidemail',
			'type'		=> 'multilingual_text',
			'default'	=> 'Please use a valid email address!',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_success',
			'type'		=> 'multilingual_text',
			'default'	=> 'Success! Please click the confirmation link in the e-mail sent to you.',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_error',
			'type'		=> 'multilingual_text',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_subscribed',
			'type'		=> 'multilingual_text',
			'default'	=> 'You are subscribed as [email]. Edit your newsletter preferences <a href="index.php?route=account/newsletter">here</a>.',
		);
		
		// Module Locations
		$data['settings'][] = array(
			'key'		=> 'module_locations',
			'type'		=> 'heading',
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
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled'], 'debug' => $data['text_enabled_with_full_logging']),
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
	// Log functions
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
	public function generateBackgroundData() {
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		if (!$this->hasPermission('modify') || !$this->config->get($prefix . $this->name . '_apikey')) {
			return;
		}
		
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->registry);
		
		$lists = $mailchimp_integration->getLists();
		$mailchimp_integration->createWebhooks($lists);
		
		if ($this->config->get($prefix . $this->name . '_ecommerce360')) {
			$mailchimp_integration->createStores($lists);
		}
	}
	
	public function sync() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		
		if (!$this->config->get($prefix . $this->name . '_apikey') || !$this->config->get($prefix . $this->name . '_listid')) {
			$language = $this->loadLanguage($this->type . '/' . $this->name);
			echo $language['text_sync_error'];
			return;
		}
		
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->registry);
		echo $mailchimp_integration->{'sync'.ucwords($this->request->get['type']).'s'}($this->request->get['start'], $this->request->get['end']);
	}
}
?>