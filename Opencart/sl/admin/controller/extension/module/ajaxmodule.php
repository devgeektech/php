<?php
class ControllerExtensionModuleAjaxModule extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/ajaxmodule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_ajaxmodule', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/ajaxmodule', 'user_token=' . $this->session->data['user_token'], true)
		);

		$widget_code = '<script charset="utf-8" id="grabscript" type="text/javascript" src="'. HTTPS_CATALOG .'catalog/view/javascript/grabhtml.js?mdName=MODULENAME&mdCode=?"></script>
<div id="itemname_outer_html_wrapper"></div>
		
If you want to use this widget multiple times on same page then use "mdViewid" as paramenter with int value and same id given to div tag as below:

EXAMPLE :		
<script charset="utf-8" type="text/javascript" id="grabscript" src="'. HTTPS_CATALOG .'catalog/view/javascript/grabhtml.js?mdName=MODULENAME1,MODULENAME2&mdCode=CODE1,CODE2&mdViewid=1,2"></script>
<div id="itemname_outer_html_wrapper_1"></div>
<div id="itemname_outer_html_wrapper_2"></div>';
		
		
		$data['widget_code'] = htmlspecialchars($widget_code);
		$data['action'] = $this->url->link('extension/module/ajaxmodule', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_ajaxmodule_status'])) {
			$data['module_ajaxmodule_status'] = $this->request->post['module_ajaxmodule_status'];
		} else {
			$data['module_ajaxmodule_status'] = $this->config->get('module_ajaxmodule_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/ajaxmodule', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ajaxmodule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}