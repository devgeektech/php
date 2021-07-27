<?php

trait customerGroupJProduct {

	private $JocToken = 'token';
	private $JocSSL = true;
	private $JExtensionPagePath = 'extension/extension';
	private $JExtensionPath = 'extension/';

	public function initCustomerGroupJProduct() {
		if(VERSION < '2.2.0.0') {
			$this->JocSSL = 'ssl';
		}

		if(VERSION <= '2.2.0.0') {
			$this->JExtensionPath = '';
		}

		if(VERSION >= '3.0.0.0') {
			$this->JocToken = 'user_token';
			$this->JExtensionPagePath = 'marketplace/extension';
		}

	}

	public function var_summernote() {
		return '';
	}

	public function summernote_editor(&$data) {
		$data['summernote'] = $this->var_summernote();
		$data['summernote_editor'] = '';//$this->loadView('extension/jcustomergroupprice_editor', $data);

	}

	/*
	$options = array(
		'href' => array('find' => array('store_id' => '[STORE_ID]', 'name' => '[STORE_NAME]', 'url' => '[STORE_URL]', 'ssl' => '[STORE_SSL]') , 'str' => $this->url->link('design/jcategoryreview', $this->JocToken . '=' . $this->session->data[$this->JocToken] .'&store_id=[STORE_ID]', true) )
	)
	*/
	public function getStores($options = array()) {

		$this->load->model('setting/store');
		$stores_ = $this->model_setting_store->getStores();

		$stores = array();
		$stores[0] = array(
			'name' => strip_tags($this->language->get('text_default')),
			'store_id' => '0',
			'url' => HTTP_CATALOG,
			'ssl' => HTTPS_CATALOG
		);

		foreach ($options as $key => $value) {
			foreach ($value['find'] as $find => $short_code) {
				$value['str'] = str_replace($short_code, (isset($stores[0][$find]) ? $stores[0][$find] : '' ) , $value['str']);
			}
			$stores[0][$key] = $value['str'];
		}

		foreach ($stores_ as $store) {
			foreach ($options as $key => $value) {
				foreach ($value['find'] as $find => $short_code) {
					$value['str'] = str_replace($short_code, (isset($store[$find]) ? $store[$find] : ''), $value['str']);
				}
				$store[$key] = $value['str'];
			}
			$stores[$store['store_id']] = $store;
		}
		return $stores;
	}

	public function loadCustomerGroupsModel() {
		if(VERSION < '2.2.0.0') {
			$this->load->model('sale/customer_group');
			$model_customer_group = 'model_sale_customer_group';
		} else {
			$this->load->model('customer/customer_group');
			$model_customer_group = 'model_customer_customer_group';
		}
		return $model_customer_group;
	}

	public function getCustomerGroups() {
		$model_customer_group = $this->loadCustomerGroupsModel();
		return $this->{$model_customer_group}->getCustomerGroups();
	}

	public function getLanguages() {
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		if(VERSION >= '2.2.0.0') {
			foreach ($languages as &$language) {
				$language['lang_flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
			}
		} else {
			foreach ($languages as &$language) {
				$language['lang_flag'] = 'view/image/flags/'.$language['image'].'';
			}
		}
		return $languages;
	}

	public function loadView($path, &$data, $twig=false) {
		if(VERSION >= '3.0.0.0' && !$twig) {
			$old_template = $this->config->get('template_engine');
			$this->config->set('template_engine', 'template');
		}
		$view = $this->load->view($this->viewPath($path), $data);
		if(VERSION >= '3.0.0.0' && !$twig) {
			$this->config->set('template_engine', $old_template);
		}
		return $view;
	}

	public function viewPath($path) {
		$path_info = pathinfo($path);

		$npath = $path_info['dirname'] . '/'. $path_info['filename'];
		if(VERSION <= '2.3.0.2') {
			$npath.= '.tpl';
		}
		return $npath;
	}

	public function buildCustomerGroupJProductTables() {

	}
}
