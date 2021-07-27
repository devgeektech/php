<?php
require_once DIR_SYSTEM . 'library/customergroupjproduct/customergroupjproduct.php';
class ControllerCustomerGroupJProductCustomergroupJProduct extends Controller {
	private $error = array();
	use customerGroupJProduct;

	public function __construct($registry) {
		parent :: __construct($registry);
		$this->initCustomerGroupJProduct();
		$this->buildCustomerGroupJProductTables();

		$this->load->language('customergroupjproduct/customergroupjproduct');
		$this->load->model('customergroupjproduct/customergroupjproduct');
		$this->load->model('setting/setting');
		$this->model_customergroupjproduct_customergroupjproduct->CreateGroupProductTable();
	}

	public function getAdminMenu() {
		$this->load->language('customergroupjproduct/customergroupjproduct_menu');
		$menu = array();
		if (VERSION <= '2.2.0.0') {
			$menu = array(
				'id'       => 'menu-customergroupjproduct',
				'icon'	   => 'fa-users',
				'name'	   => $this->language->get('text_customergroupjproduct'),
				'href'     => $this->url->link('customergroupjproduct/customergroupjproduct', $this->JocToken . '=' . $this->session->data[$this->JocToken], true),
				'children' => array()
			);
		} else {
			if ($this->user->hasPermission('access', 'customergroupjproduct/customergroupjproduct')) {
				$menu = array(
					'id'       => 'menu-customergroupjproduct',
					'icon'	   => 'fa-users',
					'name'	   => $this->language->get('text_customergroupjproduct'),
					'href'     => $this->url->link('customergroupjproduct/customergroupjproduct', $this->JocToken . '=' . $this->session->data[$this->JocToken], true),
					'children' => array()
				);
			}
		}
		return $menu;
	}

	protected function categoryName($category_info) {
		$name = '';
		if (!empty($category_info['path'])) {
			$name .= $category_info['path'] .' &nbsp;&nbsp;&gt;&nbsp;&nbsp; ';
		}
		$name .= $category_info['name'];
		return $name;

	}

	public function index() {

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/customergroupjproduct/stylesheet.css');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['customergroupjproduct_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		// import export
		$data['text_xls'] = $this->language->get('text_xls');
		$data['text_xlsx'] = $this->language->get('text_xlsx');
		$data['text_csv'] = $this->language->get('text_csv');
		// categories list
		$data['text_title_categories_export'] = $this->language->get('text_title_categories_export');
		$data['text_title_categories_import'] = $this->language->get('text_title_categories_import');
		// categories list
		// products list
		$data['text_title_products_export'] = $this->language->get('text_title_products_export');
		$data['text_title_products_import'] = $this->language->get('text_title_products_import');
		// products list
		// import export


		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_all_categories'] = $this->language->get('entry_all_categories');
		$data['entry_all_products'] = $this->language->get('entry_all_products');
		$data['entry_status'] = $this->language->get('entry_status');

		// products list
		$data['entry_product_name'] = $this->language->get('entry_product_name');
		$data['entry_product_model'] = $this->language->get('entry_product_model');
		// products list
		// categories list
		$data['entry_category_name'] = $this->language->get('entry_category_name');
		// categories list

		// import export
		// categories list
		$data['entry_category_ids'] = $this->language->get('entry_category_ids');
		$data['entry_category_status'] = $this->language->get('entry_category_status');
		// categories list

		// products list
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_product_ids'] = $this->language->get('entry_product_ids');
		$data['entry_product_status'] = $this->language->get('entry_product_status');
		$data['entry_export_products'] = $this->language->get('entry_export_products');
		$data['entry_export_manufacturer'] = $this->language->get('entry_export_manufacturer');
		// products list

		$data['entry_format'] = $this->language->get('entry_format');
		$data['entry_start_end_limit'] = $this->language->get('entry_start_end_limit');

		$data['entry_export_categories'] = $this->language->get('entry_export_categories');
		// import export


		$data['help_product'] = $this->language->get('help_product');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_all_categories'] = $this->language->get('help_all_categories');
		$data['help_all_products'] = $this->language->get('help_all_products');
		// import export
		// categories list
		$data['help_category_ids'] = $this->language->get('help_category_ids');
		// categories list
		// products list
		$data['help_product_ids'] = $this->language->get('help_product_ids');
		// products list
		$data['help_start_end_limit'] = $this->language->get('help_start_end_limit');
		// import export

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_support'] = $this->language->get('tab_support');
		$data['tab_products'] = $this->language->get('tab_products');
		$data['tab_categories'] = $this->language->get('tab_categories');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_data'] = $this->language->get('button_save_data');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_clear'] = $this->language->get('button_clear');
		// products list & categories list
		$data['button_quick_update'] = $this->language->get('button_quick_update');
		// products list & categories list

		// import export
		$data['button_close'] = $this->language->get('button_close');
		$data['button_import'] = $this->language->get('button_import');
		$data['button_export'] = $this->language->get('button_export');
		$data['button_download'] = $this->language->get('button_download');
		$data['button_select_file'] = $this->language->get('button_select_file');
		$data['button_save_export_settings'] = $this->language->get('button_save_export_settings');
		// import export


		// products list
		$data['legend_product_quick_update'] = $this->language->get('legend_product_quick_update');
		// products list
		// categories list
		$data['legend_category_quick_update'] = $this->language->get('legend_category_quick_update');
		// categories list

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['none'])) {
			$data['error_none'] = $this->error['none'];
		} else {
			$data['error_none'] = '';
		}

		if (isset($this->error['customer_group'])) {
			$data['error_customer_group'] = $this->error['customer_group'];
		} else {
			$data['error_customer_group'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->JocToken . '=' . $this->session->data[$this->JocToken], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customergroupjproduct/customergroupjproduct', $this->JocToken.'=' . $this->session->data[$this->JocToken] . $url, true)
		);

		$data['action'] = $this->url->link('customergroupjproduct/customergroupjproduct', $this->JocToken.'=' . $this->session->data[$this->JocToken] . $url, true);

		$data['token'] = $this->session->data[$this->JocToken];
		$data['joctoken'] = $this->JocToken;

		$data['customer_groups'] = $this->getCustomerGroups();

		$module_info = $this->model_setting_setting->getSetting('jcgpca_');

		if (isset($module_info['jcgpca_customer_group_ids'])) {
			$data['customer_group_ids'] = (array) $module_info['jcgpca_customer_group_ids'];
		} else {
			$data['customer_group_ids'] = array();
		}

		// Categories
		if (isset($module_info['jcgpca_all_categories'])) {
			$data['all_categories'] = $module_info['jcgpca_all_categories'];
		} else {
			$data['all_categories'] = '0';
		}

		if (isset($module_info['jcgpca_categories'])) {
			$categories = (array) $module_info['jcgpca_categories'];
		} else {
			$categories = array();
		}

		$data['categories'] = array();
		$this->load->model('catalog/category');

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			if ($category_info) {
				$data['categories'][] = array(
					'name' => $this->categoryName($category_info),
					'category_id' => $category_info['category_id']
				);
			}
		}

		// Prodcuts
		if (isset($module_info['jcgpca_all_products'])) {
			$data['all_products'] = $module_info['jcgpca_all_products'];
		} else {
			$data['all_products'] = '0';
		}

		if (isset($module_info['jcgpca_products'])) {
			$products = (array) $module_info['jcgpca_products'];
		} else {
			$products = array();
		}

		$data['products'] = array();
		$this->load->model('catalog/product');

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info) {
				$data['products'][] = array(
					'name' => $product_info['name'],
					'product_id' => $product_info['product_id']
				);
			}
		}


		// products list
		$data['filter_product_name'] = '';
		$data['filter_product_model'] = '';
		$data['filter_product_status'] = null;
		// products list
		// categories list
		$data['filter_category_name'] = '';
		$data['filter_category_status'] = null;
		// categories list

		// import export
		// products list
		$data['stores'] = $this->getStores();

		$module_info_products_export = $this->model_setting_setting->getSetting('jcgpca_products_export');

		if (isset($module_info_products_export['jcgpca_products_export_store_id'])) {
			$data['products_export_store_id'] = $module_info_products_export['jcgpca_products_export_store_id'];
		} else {
			$data['products_export_store_id'] = '';
		}

		if (isset($module_info_products_export['jcgpca_products_export_ids'])) {
			$data['products_export_ids'] = $module_info_products_export['jcgpca_products_export_ids'];
		} else {
			$data['products_export_ids'] = '';
		}

		if (isset($module_info_products_export['jcgpca_products_export_start_end_limit'])) {
			$data['products_export_start_end_limit'] = $module_info_products_export['jcgpca_products_export_start_end_limit'];
		} else {
			$data['products_export_start_end_limit'] = '';
		}

		if (isset($module_info_products_export['jcgpca_products_export_format'])) {
			$data['products_export_format'] = $module_info_products_export['jcgpca_products_export_format'];
		} else {
			$data['products_export_format'] = 'xls';
		}


		if (isset($module_info_products_export['jcgpca_products_export_status'])) {
			$data['products_export_status'] = $module_info_products_export['jcgpca_products_export_status'];
		} else {
			$data['products_export_status'] = '';
		}

		if (isset($module_info_products_export['jcgpca_products_export_products'])) {
			$products_export_products = (array)$module_info_products_export['jcgpca_products_export_products'];
		} else {
			$products_export_products = array();
		}

		$data['products_export_products'] = array();
		$this->load->model('catalog/product');
		foreach ($products_export_products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info) {
				$data['products_export_products'][] = array(
					'product_id' => $product_info['product_id'],
					'name' => $product_info['name'],
				);
			}
		}

		if (isset($module_info_products_export['jcgpca_products_export_category_products'])) {
			$products_export_category_products = (array)$module_info_products_export['jcgpca_products_export_category_products'];
		} else {
			$products_export_category_products = array();
		}

		$data['products_export_category_products'] = array();
		$this->load->model('catalog/category');
		foreach ($products_export_category_products as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			if ($category_info) {
				$data['products_export_category_products'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => $this->categoryName($category_info),
				);
			}
		}

		if (isset($module_info_products_export['jcgpca_products_export_manufacturer_products'])) {
			$products_export_manufacturer_products = (array)$module_info_products_export['jcgpca_products_export_manufacturer_products'];
		} else {
			$products_export_manufacturer_products = array();
		}

		$data['products_export_manufacturer_products'] = array();
		$this->load->model('catalog/manufacturer');
		foreach ($products_export_manufacturer_products as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			if ($manufacturer_info) {
				$data['products_export_manufacturer_products'][] = array(
					'manufacturer_id' => $manufacturer_info['manufacturer_id'],
					'name' => $manufacturer_info['name'],
				);
			}
		}

		$data['samplefile_products'] = $this->url->link('customergroupjproduct/customergroupjproduct/sampleFileDownload', $this->JocToken . '='. $this->session->data[$this->JocToken] . '&type=products', true);
		// products list

		// categories list
		$module_info_categories_export = $this->model_setting_setting->getSetting('jcgpca_categories_export');

		if (isset($module_info_categories_export['jcgpca_categories_export_ids'])) {
			$data['categories_export_ids'] = $module_info_categories_export['jcgpca_categories_export_ids'];
		} else {
			$data['categories_export_ids'] = '';
		}

		if (isset($module_info_categories_export['jcgpca_categories_export_start_end_limit'])) {
			$data['categories_export_start_end_limit'] = $module_info_categories_export['jcgpca_categories_export_start_end_limit'];
		} else {
			$data['categories_export_start_end_limit'] = '';
		}

		if (isset($module_info_categories_export['jcgpca_categories_export_format'])) {
			$data['categories_export_format'] = $module_info_categories_export['jcgpca_categories_export_format'];
		} else {
			$data['categories_export_format'] = 'xls';
		}


		if (isset($module_info_categories_export['jcgpca_categories_export_status'])) {
			$data['categories_export_status'] = $module_info_categories_export['jcgpca_categories_export_status'];
		} else {
			$data['categories_export_status'] = '';
		}

		if (isset($module_info_categories_export['jcgpca_categories_export_categories'])) {
			$categories_export_categories = (array)$module_info_categories_export['jcgpca_categories_export_categories'];
		} else {
			$categories_export_categories = array();
		}

		$data['categories_export_categories'] = array();
		$this->load->model('catalog/category');
		foreach ($categories_export_categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			if ($category_info) {
				$data['categories_export_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => $this->categoryName($category_info),
				);
			}
		}

		$data['samplefile_categories'] = $this->url->link('customergroupjproduct/customergroupjproduct/sampleFileDownload', $this->JocToken . '='. $this->session->data[$this->JocToken] . '&type=categories', true);
		// categories list
		// import export
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->loadView('customergroupjproduct/customergroupjproduct', $data));
	}
	// products list
	public function productsList() {

		$this->load->model('catalog/product');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['selected'] = array();

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['button_update'] = $this->language->get('button_update');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_customer_groups'] = $this->language->get('column_customer_groups');
		$data['column_action'] = $this->language->get('column_action');

		$data['token'] = $this->session->data[$this->JocToken];
		$data['joctoken'] = $this->JocToken;

		$data['customer_groups'] = $this->getCustomerGroups();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$data['products'] = array();
		$this->load->model('tool/image');

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$product_customer_group = $this->model_catalog_product->getProductCustomerGroups($result['product_id']);

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'customer_groups'      => $product_customer_group,
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
			);
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('customergroupjproduct/customergroupjproduct/productsList', $this->JocToken.'=' . $this->session->data[$this->JocToken] . '&sort=pd.name' . $url, true);
		$data['sort_model'] = $this->url->link('customergroupjproduct/customergroupjproduct/productsList', $this->JocToken.'=' . $this->session->data[$this->JocToken] . '&sort=p.model' . $url, true);
		$data['sort_status'] = $this->url->link('customergroupjproduct/customergroupjproduct/productsList', $this->JocToken.'=' . $this->session->data[$this->JocToken] . '&sort=p.status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customergroupjproduct/customergroupjproduct/productsList', $this->JocToken.'=' . $this->session->data[$this->JocToken]  . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;


		$this->response->setOutput($this->loadView('customergroupjproduct/products_list', $data));
	}

	public function quickUpdateProducts() {
		$json = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateQuickUpdateProducts()) {
			// print_r($this->request->post);die;
			$this->model_customergroupjproduct_customergroupjproduct->addProductCustomergroup($this->request->post);
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateQuickUpdateProducts() {
		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// if (!isset($this->request->post['customer_group_ids'])) {
		// 	$this->error['customer_group'] = $this->language->get('error_customer_group');
		// }

		if (empty($this->request->post['products'])) {
			$this->error['none'] = $this->language->get('error_products');
		}

		/*if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}*/

		return !$this->error;
	}
	// products list

	// categories list
	public function categoriesList() {

		$this->load->model('catalog/category');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['selected'] = array();

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['button_update'] = $this->language->get('button_update');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_customer_groups'] = $this->language->get('column_customer_groups');
		$data['column_action'] = $this->language->get('column_action');

		$data['token'] = $this->session->data[$this->JocToken];
		$data['joctoken'] = $this->JocToken;

		$data['customer_groups'] = $this->getCustomerGroups();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$data['categories'] = array();
		$this->load->model('tool/image');

		$product_total = $this->model_catalog_category->getTotalCategories($filter_data);

		$results = $this->model_catalog_category->getCategories($filter_data);

		foreach ($results as $result) {

			$category_info = $this->model_catalog_category->getCategory($result['category_id']);

			if (is_file(DIR_IMAGE . $category_info['image'])) {
				$image = $this->model_tool_image->resize($category_info['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$category_customer_group = $this->model_catalog_category->getCategoryCustomerGroups($category_info['category_id']);

			$data['categories'][] = array(
				'category_id' => $category_info['category_id'],
				'name'       => $this->categoryName($category_info),
				'image'      => $image,
				'customer_groups'      => $category_customer_group,
				'status'     => $category_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
			);
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('customergroupjproduct/customergroupjproduct/categoriesList', $this->JocToken.'=' . $this->session->data[$this->JocToken] . '&sort=name' . $url, true);
		$data['sort_status'] = $this->url->link('customergroupjproduct/customergroupjproduct/categoriesList', $this->JocToken.'=' . $this->session->data[$this->JocToken] . '&sort=c.status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customergroupjproduct/customergroupjproduct/categoriesList', $this->JocToken.'=' . $this->session->data[$this->JocToken]  . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;


		$this->response->setOutput($this->loadView('customergroupjproduct/categories_list', $data));
	}

	public function quickUpdateCategories() {
		$json = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateQuickUpdateCategories()) {
			// print_r($this->request->post);die;
			$this->model_customergroupjproduct_customergroupjproduct->addProductCustomergroup($this->request->post);
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateQuickUpdateCategories() {
		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// if (!isset($this->request->post['customer_group_ids'])) {
		// 	$this->error['customer_group'] = $this->language->get('error_customer_group');
		// }

		if (empty($this->request->post['categories'])) {
			$this->error['none'] = $this->language->get('error_categories');
		}

		/*if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}*/

		return !$this->error;
	}
	// categories list
	// import export
	// products list
	protected function validateSaveProductsExportData() {
		$this->load->language('customergroupjproduct/customergroupjproduct');
		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	public function saveProductsExportData() {
		$json = array();
		$this->load->language('customergroupjproduct/customergroupjproduct');

		$this->load->model('setting/setting');
		if (!isset($this->request->get['j']) || (isset($this->request->get['j']) && $this->request->get['j']!=1)) {
			return '';
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSaveProductsExportData()) {

		 	$this->load->model('setting/setting');

		 	foreach ($this->request->post as $key => $value) {
		 		$this->request->post['jcgpca_products_export'. str_replace("products_export_", '_', $key) ] = $value;
		 		unset($this->request->post[$key]);
		 	}

			$this->model_setting_setting->editSetting('jcgpca_products_export', $this->request->post);

		 	$json['success'] = $this->language->get('text_products_export_data_success');

		} else {
			$json['error'] = $this->error;
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function doProductsImport() {
		$this->load->language('customergroupjproduct/customergroupjproduct');
		$this->load->model('customergroupjproduct/export');
		$this->load->model('setting/store');
		$json = array();

		// echo "_POST data";
		// echo "\n";
		// print_r($this->request->post);
		// echo "\n";echo "\n";

		// echo "_GET data";
		// echo "\n";
		// print_r($this->request->get);
		// echo "\n";echo "\n";

		// echo "_FILES data";
		// echo "\n";
		// print_r($this->request->files);
		// echo "\n";echo "\n";
		// die;

		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$json) {
			require_once(DIR_SYSTEM . 'library/customergroupjproduct/PHPExcel.php');
			if(empty($this->request->files['jc_productsfile'])) {
				$json['error']['file'] = $this->language->get('error_file');
				$json['error']['warning'] = $this->language->get('error_file');
			}
			// Check to see if any PHP files are trying to be uploaded
			if(!empty($this->request->files['jc_productsfile'])) {
				$content = file_get_contents($this->request->files['jc_productsfile']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error']['file'] = $this->language->get('error_filetype');
					$json['error']['warning'] = $this->language->get('error_filetype');
				}
				// Return any upload error
				if ($this->request->files['jc_productsfile']['error'] != UPLOAD_ERR_OK) {
					$json['error']['file'] = $this->language->get('error_upload_' . $this->request->files['jc_productsfile']['error']);
					$json['error']['warning'] = $this->language->get('error_upload_' . $this->request->files['jc_productsfile']['error']);
				}
			}
			if(!$json && $this->request->files) {
				$file = basename($this->request->files['jc_productsfile']['name']);
				move_uploaded_file($this->request->files['jc_productsfile']['tmp_name'], $file);
				$inputFileName = $file;

				$extension = pathinfo($inputFileName);

				$extension['extension'] = strtolower(strtoupper($extension['extension']));

				if(!in_array($extension['extension'], array('xls','xlsx','csv'))) {
					$json['error']['file'] = $this->language->get('error_format_diff');
					$json['error']['warning'] = $this->language->get('error_format_diff');
				}

				if($extension['extension']=='xlsx' || $extension['extension']=='xls' || $extension['extension']=='csv') {
					try{
						$inputFileType = $extension['extension'];

						if($extension['extension']=='csv') {
							$objReader  = PHPExcel_IOFactory::createReader('CSV');
							$objPHPExcel = $objReader->load($inputFileName);
						}else{
							$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
						}
					}catch(Exception $e){
						$json['error']['warning'] = $this->language->get('error_loading_file') .'"'. pathinfo($inputFileName,PATHINFO_BASENAME) .'": '.$e->getMessage();
					}
				}
			}
			if(!$json) {
				$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$i=0;
				$updated = 0;
				$missing = 0;
				$missings = array();
				if(count($allDataInSheet) > 1) {
					foreach($allDataInSheet as $default_value) {
						$value = $this->clean($default_value);
						// Column Names
						if($i == '0') {
						}
						// Column Values
						if($i != '0') {
							$product_id = (isset($value['A']) ? (int)$value['A'] : '');
							$product_name = (isset($value['B']) ? $value['B'] : '');
							$customer_group_id = (isset($value['C']) ? $value['C'] : '');
							$customer_group_name = (isset($value['D']) ? $value['D'] : '');

							// Insert Data
							$insert_data = array();
							$insert_data['product_id']		= (int)$product_id;
							$insert_data['customer_group_id'] = (int)$customer_group_id;
							$result = $this->model_customergroupjproduct_export->hasProductCustomerGroup($insert_data);
							if($result) {
								$this->model_customergroupjproduct_customergroupjproduct->
								$this->model_customergroupjproduct_export->editProductCustomerGroup($insert_data);
								$updated++;
							}
						}
						$i++;
					}
					$text_success  = $this->language->get('text_success');
					$json['success'] = $text_success;
				} else {
					// $json['error']['warning'] = $this->language->get('text_no_result');
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function doProductsExport() {
		$this->load->language('customergroupjproduct/customergroupjproduct');

		$this->load->model('customergroupjproduct/export');
		$this->load->model('setting/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$products = array();
		if (!$json) {

			$language_id = (int)$this->config->get('config_language_id');

			$store_id = 0;
			if(!empty($this->request->post['products_export_store_id']) && $this->request->post['products_export_store_id'] != '') {
				$store_id = $this->request->post['products_export_store_id'];
			}
			$format = 'xls';
			if(!empty($this->request->post['products_export_format'])) {
				$format = $this->request->post['products_export_format'];
			}
			$product_status = '';
			if(!empty($this->request->post['products_export_status'])) {
				$product_status = $this->request->post['products_export_status'];
			}
			$export_with_customergroup = 'BOTH';
			if(!empty($this->request->post['products_export_with_customergroup'])) {
				$export_with_customergroup = $this->request->post['products_export_with_customergroup'];
			}

			$product_ids = array();
			if(!empty($this->request->post['products_export_ids'])) {
				$productids = explode(",", $this->request->post['products_export_ids']);
				foreach ($productids as $key => &$value) {
					$value = trim($value);
					if (!(int)$value) {
						unset($productids[$key]);
					}
				}
				$product_ids = array_merge($product_ids, $productids);
			}

			// products_export_products
			if(!empty($this->request->post['products_export_products'])) {
				foreach ($this->request->post['products_export_products'] as $key => &$value) {
					$value = trim($value);
					if (!(int)$value) {
						unset($this->request->post['products_export_products'][$key]);
					}
				}
				// if (!count($this->request->post['products_export_products'])) {
				// 	unset($this->request->post['products_export_products']);
				// }
			}

			if(!empty($this->request->post['products_export_products'])) {
				$product_ids = array_merge($product_ids, $this->request->post['products_export_products']);
			}

			// products_export_category_products
			if(!empty($this->request->post['products_export_category_products'])) {
				foreach ($this->request->post['products_export_category_products'] as $key => &$value) {
					$value = trim($value);
					if (!(int)$value) {
						unset($this->request->post['products_export_category_products'][$key]);
					}
				}
				// if (!count($this->request->post['products_export_category_products'])) {
				// 	unset($this->request->post['products_export_category_products']);
				// }
			}
			if (!empty($this->request->post['products_export_category_products'])) {
				$categories_products = $this->model_customergroupjproduct_export->getProductsByCategoryIds($this->request->post['products_export_category_products']);
				foreach ($categories_products as $key => $value) {
					$product_ids[] = $value['product_id'];
				}
			}

			// products_export_manufacturer_products
			if(!empty($this->request->post['products_export_manufacturer_products'])) {
				foreach ($this->request->post['products_export_manufacturer_products'] as $key => &$value) {
					$value = trim($value);
					if (!(int)$value) {
						unset($this->request->post['products_export_manufacturer_products'][$key]);
					}
				}
				// if (!count($this->request->post['products_export_manufacturer_products'])) {
				// 	unset($this->request->post['products_export_manufacturer_products']);
				// }
			}
			if (!empty($this->request->post['products_export_manufacturer_products'])) {
				$manufacturers_products = $this->model_customergroupjproduct_export->getProductsByManufacturerIds($this->request->post['products_export_manufacturer_products']);
				foreach ($manufacturers_products as $key => $value) {
					$product_ids[] = $value['product_id'];
				}
			}


			$start = '';
			$limit = '';
			if(isset($this->request->post['products_export_start_end_limit'])) {
				$limits = array_slice(explode(",", $this->request->post['products_export_start_end_limit']), 0, 2);
				$start = (int)$limits[0];
				if (isset($limits[1])) {
					$limit = (int)$limits[1];
				}
			}

			$filter_data = array(
				'store_id' => $store_id,
				'language_id' => $language_id,
				'status' => $product_status,
				'product_ids' => array_unique($product_ids),
				'start' => $start,
				'limit' => $limit,
			);

			$products = $this->model_customergroupjproduct_export->getProducts($filter_data);


			$cutomer_groups = $this->getCustomerGroups();

			$sort = array();
			foreach ($cutomer_groups as $key => $value) {
				$sort[$key] = $value['customer_group_id'];
			}
			array_multisort($sort, SORT_ASC, $cutomer_groups);
			$cutomergroups = array();
			foreach ($cutomer_groups as $key => $value) {
				$cutomergroups[$value['customer_group_id']] = $value['name'];
			}

			if (empty($products)) {
				$json['error'] = $this->language->get('error_no_products');
			}

		}
		if (!$json) {

			require_once(DIR_SYSTEM . 'library/customergroupjproduct/PHPExcel.php');

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("JADEAGILE");
			$objPHPExcel->getProperties()->setSubject("Customer Group Products/Categories");

			$objPHPExcel->getActiveSheet()->setTitle($this->language->get('products_export_title'));

			$i = 1;
			$char = 'A';



			$objPHPExcel->getActiveSheet()->getStyle('1')->getFill()->applyFromArray(array(
				'type'				=> PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' 	=> array(
					'rgb' 				=> '017FBE',
				),
			));

			$objPHPExcel->getActiveSheet()->getStyle('1')->applyFromArray(array(
				'font'  => array(
					'color' => array('rgb' => 'FFFFFF'),
					'bold'  => true,
				)
			));
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('products_export_product_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('products_export_product_name'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('products_export_customergroup_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('products_export_customergroup'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);

			// fill the data

			foreach($products as $result) {
				$product_cutomer_groups = $this->model_customergroupjproduct_export->getProductCustomerGroups($result['product_id']);

				$product_customer_groups_ids = array();
				foreach($product_cutomer_groups as $product_cutomer_group) {
					$product_customer_groups_ids[$product_cutomer_group['customer_group_id']] = $product_cutomer_group['product_id'];
				}
				if ($export_with_customergroup == 'BOTH') {
					foreach ($cutomer_groups as $cutomer_group) {
						if (!isset($product_customer_groups_ids[$cutomer_group['customer_group_id']])) {
							$product_cutomer_groups[] = array(
								'product_id' => $result['product_id'],
								'customer_group_id' => '',

							);
						}
					}
				}
				foreach($product_cutomer_groups as $product_cutomer_group) {
					$char_value = 'A'; $i++;
					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['product_id']);
					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));

					if(isset($cutomergroups[$product_cutomer_group['customer_group_id']])) {
						$customer_group = $cutomergroups[$product_cutomer_group['customer_group_id']];
					} else {
						$customer_group = '';
					}

					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $product_cutomer_group['customer_group_id']);

					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $customer_group);
				}
			}

			/***************************************/
	      	/*Available Customer Groups*/
	      	$objPHPExcel->getProperties()->setCreator("JADEAGILE");
			$objPHPExcel->getProperties()->setSubject("Customer Group Products/Categories");
			$objWorkSheet = $objPHPExcel->createSheet();
	      	$objWorkSheet->setTitle($this->language->get('customergroups_export_title'));

	      	$i = 1;
			$char = 'A';

			$objWorkSheet->getStyle('1')->getFill()->applyFromArray(array(
				'type'				=> PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' 	=> array(
				'rgb' 				=> '017FBE',
				),
			));

			$objWorkSheet->getStyle('1')->applyFromArray(array(
				'font'  => array(
				'color' => array('rgb' => 'FFFFFF'),
				'bold'  => true,
				)
			));
	      	$objWorkSheet->setCellValue($char .$i, $this->language->get('customergroups_export_customergroup_id'))->getColumnDimension($char)->setAutoSize(true); $objWorkSheet->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objWorkSheet->setCellValue($char .$i, $this->language->get('customergroups_export_customergroup'))->getColumnDimension($char)->setAutoSize(true); $objWorkSheet->getStyle($char++ .$i)->getAlignment()->setWrapText(true);

			foreach ($cutomer_groups as $cutomer_group) {
				$char_value = 'A'; $i++;
				$objWorkSheet->setCellValue($char_value++ .$i, $cutomer_group['customer_group_id']);
				$objWorkSheet->setCellValue($char_value++ .$i, html_entity_decode($cutomer_group['name'], ENT_QUOTES, 'UTF-8'));
			}


			$objPHPExcel->setActiveSheetIndex(0);
		    /*Find Format*/
			if($format == 'xls') {
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$file_name = 'JCustomerGroupProductsList.xls';
			}else if($format == 'xlsx') {
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$file_name = 'JCustomerGroupProductsList.xlsx';
			}else if($format == 'csv') {
				$file_name = 'JCustomerGroupProductsList.csv';
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
			}else{
				$file_name = '';
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			}

			$file_to_save = DIR_UPLOAD . $file_name;
			$objWriter->save(DIR_UPLOAD . $file_name);

			$json['href'] = str_replace('&amp;', '&', $this->url->link('customergroupjproduct/customergroupjproduct/fileDownload', $this->JocToken . '='. $this->session->data[$this->JocToken] .'&file_name='. $file_name .'&format='. $format, true));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	// products list
	// categories list
	protected function validateSaveCategoriesExportData() {
		$this->load->language('customergroupjproduct/customergroupjproduct');
		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	public function saveCategoriesExportData() {
		$json = array();
		$this->load->language('customergroupjproduct/customergroupjproduct');

		$this->load->model('setting/setting');
		if (!isset($this->request->get['j']) || (isset($this->request->get['j']) && $this->request->get['j']!=1)) {
			return '';
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSaveCategoriesExportData()) {

		 	$this->load->model('setting/setting');

		 	foreach ($this->request->post as $key => $value) {
		 		$this->request->post['jcgpca_categories_export'. str_replace("categories_export_", '_', $key) ] = $value;
		 		unset($this->request->post[$key]);
		 	}

			$this->model_setting_setting->editSetting('jcgpca_categories_export', $this->request->post);

		 	$json['success'] = $this->language->get('text_categories_export_data_success');
		} else {
			$json['error'] = $this->error;
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function doCategoriesImport() {
		$this->load->language('customergroupjproduct/customergroupjproduct');
		$this->load->model('customergroupjproduct/export');
		$this->load->model('setting/store');
		$json = array();

		// echo "_POST data";
		// echo "\n";
		// print_r($this->request->post);
		// echo "\n";echo "\n";

		// echo "_GET data";
		// echo "\n";
		// print_r($this->request->get);
		// echo "\n";echo "\n";

		// echo "_FILES data";
		// echo "\n";
		// print_r($this->request->files);
		// echo "\n";echo "\n";
		// die;

		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$json) {
			require_once(DIR_SYSTEM . 'library/customergroupjproduct/PHPExcel.php');
			if(empty($this->request->files['jc_categoriesfile'])) {
				$json['error']['file'] = $this->language->get('error_file');
				$json['error']['warning'] = $this->language->get('error_file');
			}
			// Check to see if any PHP files are trying to be uploaded
			if(!empty($this->request->files['jc_categoriesfile'])) {
				$content = file_get_contents($this->request->files['jc_categoriesfile']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error']['file'] = $this->language->get('error_filetype');
					$json['error']['warning'] = $this->language->get('error_filetype');
				}
				// Return any upload error
				if ($this->request->files['jc_categoriesfile']['error'] != UPLOAD_ERR_OK) {
					$json['error']['file'] = $this->language->get('error_upload_' . $this->request->files['jc_categoriesfile']['error']);
					$json['error']['warning'] = $this->language->get('error_upload_' . $this->request->files['jc_categoriesfile']['error']);
				}
			}
			if(!$json && $this->request->files) {
				$file = basename($this->request->files['jc_categoriesfile']['name']);
				move_uploaded_file($this->request->files['jc_categoriesfile']['tmp_name'], $file);
				$inputFileName = $file;

				$extension = pathinfo($inputFileName);

				$extension['extension'] = strtolower(strtoupper($extension['extension']));

				if(!in_array($extension['extension'], array('xls','xlsx','csv'))) {
					$json['error']['file'] = $this->language->get('error_format_diff');
					$json['error']['warning'] = $this->language->get('error_format_diff');
				}

				if($extension['extension']=='xlsx' || $extension['extension']=='xls' || $extension['extension']=='csv') {
					try{
						$inputFileType = $extension['extension'];

						if($extension['extension']=='csv') {
							$objReader  = PHPExcel_IOFactory::createReader('CSV');
							$objPHPExcel = $objReader->load($inputFileName);
						}else{
							$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
						}
					}catch(Exception $e){
						$json['error']['warning'] = $this->language->get('error_loading_file') .'"'. pathinfo($inputFileName,PATHINFO_BASENAME) .'": '.$e->getMessage();
					}
				}
			}
			if(!$json) {
				$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$i=0;
				$updated = 0;
				$missing = 0;
				$missings = array();
				if(count($allDataInSheet) > 1) {
					foreach($allDataInSheet as $default_value) {
						$value = $this->clean($default_value);
						// Column Names
						if($i == '0') {
						}
						// Column Values
						if($i != '0') {
							$category_id = (isset($value['A']) ? (int)$value['A'] : '');
							$category_name = (isset($value['B']) ? $value['B'] : '');
							$customer_group_id = (isset($value['C']) ? $value['C'] : '');
							$customer_group_name = (isset($value['D']) ? $value['D'] : '');

							// Insert Data
							$insert_data = array();
							$insert_data['category_id']		= (int)$category_id;
							$insert_data['customer_group_id'] = (int)$customer_group_id;

							$result = $this->model_customergroupjproduct_export->hasCategoryCustomerGroup($insert_data);
							if($result) {
								$this->model_customergroupjproduct_export->editCategoryCustomerGroup($insert_data);
								$updated++;
							}
						}
						$i++;
					}
					$text_success  = $this->language->get('text_success');
					$json['success'] = $text_success;
				} else {
					// $json['error']['warning'] = $this->language->get('text_no_result');
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function doCategoriesExport() {
		$this->load->language('customergroupjproduct/customergroupjproduct');

		$this->load->model('customergroupjproduct/export');
		$this->load->model('setting/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$categories = array();
		if (!$json) {

			$language_id = (int)$this->config->get('config_language_id');

			$store_id = 0;
			if(!empty($this->request->post['categories_export_store_id']) && $this->request->post['categories_export_store_id'] != '') {
				$store_id = (int)$this->request->post['categories_export_store_id'];
			}
			$format = 'xls';
			if(!empty($this->request->post['categories_export_format'])) {
				$format = $this->request->post['categories_export_format'];
			}
			$export_with_customergroup = 'BOTH';
			// $export_with_customergroup = 'ONLY';
			if(!empty($this->request->post['categories_export_with_customergroup'])) {
				$export_with_customergroup = $this->request->post['categories_export_with_customergroup'];
			}

			$category_status = '';
			if(!empty($this->request->post['categories_export_status'])) {
				$category_status = $this->request->post['categories_export_status'];
			}

			$category_ids = array();
			if(!empty($this->request->post['categories_export_ids'])) {
				$categoryids = explode(",", $this->request->post['categories_export_ids']);
				foreach ($categoryids as $key => &$value) {
					$value = trim($value);
					if (!(int)$value) {
						unset($categoryids[$key]);
					}
				}
				$category_ids = array_merge($category_ids, $categoryids);
			}

			// export_categories
			if(!empty($this->request->post['categories_export_categories'])) {
				foreach ($this->request->post['categories_export_categories'] as $key => &$value) {
					$value = trim($value);
					if (!(int)$value) {
						unset($this->request->post['categories_export_categories'][$key]);
					}
				}
				// if (!count($this->request->post['categories_export_categories'])) {
				// 	unset($this->request->post['categories_export_categories']);
				// }
			}
			if(!empty($this->request->post['categories_export_categories'])) {
				$category_ids = array_merge($category_ids, $this->request->post['categories_export_categories']);
			}

			$start = '';
			$limit = '';
			if(isset($this->request->post['categories_export_start_end_limit'])) {
				$limits = array_slice(explode(",", $this->request->post['categories_export_start_end_limit']), 0, 2);
				$start = (int)$limits[0];
				if (isset($limits[1])) {
					$limit = (int)$limits[1];
				}
			}

			$filter_data = array(
				'store_id' => $store_id,
				'language_id' => $language_id,
				'status' => $category_status,
				'category_ids' => array_unique($category_ids),
				'start' => $start,
				'limit' => $limit,
			);

			$categories = $this->model_customergroupjproduct_export->getCategories($filter_data);

			$cutomer_groups = $this->getCustomerGroups();

			$sort = array();
			foreach ($cutomer_groups as $key => $value) {
				$sort[$key] = $value['customer_group_id'];
			}
			array_multisort($sort, SORT_ASC, $cutomer_groups);
			$cutomergroups = array();
			foreach ($cutomer_groups as $key => $value) {
				$cutomergroups[$value['customer_group_id']] = $value['name'];
			}

			if (empty($categories)) {
				$json['error'] = $this->language->get('error_no_categories');
			}

		}
		if (!$json) {

			require_once(DIR_SYSTEM . 'library/customergroupjproduct/PHPExcel.php');

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setCreator("JADEAGILE");
			$objPHPExcel->getProperties()->setSubject("Customer Group Products/Categories");
			$objPHPExcel->getActiveSheet()->setTitle($this->language->get('categories_export_title'));


			$i = 1;
			$char = 'A';

			$objPHPExcel->getActiveSheet()->getStyle('1')->getFill()->applyFromArray(array(
				'type'				=> PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' 	=> array(
					'rgb' 				=> '017FBE',
				),
			));

			$objPHPExcel->getActiveSheet()->getStyle('1')->applyFromArray(array(
				'font'  => array(
					'color' => array('rgb' => 'FFFFFF'),
					'bold'  => true,
				)
			));
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('categories_export_category_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('categories_export_category_name'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('categories_export_customergroup_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('categories_export_customergroup'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);

			// fill the data

			foreach($categories as $result) {
				$category_cutomer_groups = $this->model_customergroupjproduct_export->getCategoryCustomerGroups($result['category_id']);

				$category_customer_groups_ids = array();
				foreach($category_cutomer_groups as $category_cutomer_group) {
					$category_customer_groups_ids[$category_cutomer_group['customer_group_id']] = $category_cutomer_group['category_id'];
				}

				if ($export_with_customergroup == 'BOTH') {
					foreach ($cutomer_groups as $cutomer_group) {
						if(!isset($category_customer_groups_ids[$cutomer_group['customer_group_id']])) {
							$category_cutomer_groups[] = array(
								'category_id' => $result['category_id'],
								'customer_group_id' => '',
							);
						}
					}
				}
				// echo "\n\n";
				// print_r($category_cutomer_groups);
				// print_r($category_customer_groups_ids);
				// echo "\n\n";
				// print_r($result);
				// echo "\n\n";


				foreach($category_cutomer_groups as $category_cutomer_group) {
					$char_value = 'A'; $i++;
					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['category_id']);
					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));

					if(isset($cutomergroups[$category_cutomer_group['customer_group_id']])) {
						$customer_group = $cutomergroups[$category_cutomer_group['customer_group_id']];
					} else {
						$customer_group = '';
					}

					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $category_cutomer_group['customer_group_id']);
					$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $customer_group);
				}
			}

			/***************************************/
	      	/*Available Customer Groups*/
	      	$objPHPExcel->getProperties()->setCreator("JADEAGILE");
			$objPHPExcel->getProperties()->setSubject("Customer Group Products/Categories");
			$objWorkSheet = $objPHPExcel->createSheet();
	      	$objWorkSheet->setTitle($this->language->get('customergroups_export_title'));
	      	$i = 1;
			$char = 'A';

			$objWorkSheet->getStyle('1')->getFill()->applyFromArray(array(
				'type'				=> PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' 	=> array(
				'rgb' 				=> '017FBE',
				),
			));

			$objWorkSheet->getStyle('1')->applyFromArray(array(
				'font'  => array(
				'color' => array('rgb' => 'FFFFFF'),
				'bold'  => true,
				)
			));
	      	$objWorkSheet->setCellValue($char .$i, $this->language->get('customergroups_export_customergroup_id'))->getColumnDimension($char)->setAutoSize(true); $objWorkSheet->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
			$objWorkSheet->setCellValue($char .$i, $this->language->get('customergroups_export_customergroup'))->getColumnDimension($char)->setAutoSize(true); $objWorkSheet->getStyle($char++ .$i)->getAlignment()->setWrapText(true);

			foreach ($cutomer_groups as $cutomer_group) {
				$char_value = 'A'; $i++;
				$objWorkSheet->setCellValue($char_value++ .$i, $cutomer_group['customer_group_id']);
				$objWorkSheet->setCellValue($char_value++ .$i, html_entity_decode($cutomer_group['name'], ENT_QUOTES, 'UTF-8'));
			}

			$objPHPExcel->setActiveSheetIndex(0);
		    /*Find Format*/
			if($format == 'xls') {
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$file_name = 'JCustomerGroupCategoriesList.xls';
			}else if($format == 'xlsx') {
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$file_name = 'JCustomerGroupCategoriesList.xlsx';
			}else if($format == 'csv') {
				$file_name = 'JCustomerGroupCategoriesList.csv';
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
			}else{
				$file_name = '';
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			}

			$file_to_save = DIR_UPLOAD . $file_name;
			$objWriter->save(DIR_UPLOAD . $file_name);

			$json['href'] = str_replace('&amp;', '&', $this->url->link('customergroupjproduct/customergroupjproduct/fileDownload', $this->JocToken . '='. $this->session->data[$this->JocToken] .'&file_name='. $file_name .'&format='. $format, true));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	// categories list
	public function fileDownload() {
		$file_to_save = DIR_UPLOAD . $this->request->get['file_name'];
		if (file_exists($file_to_save)) {
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $this->request->get['file_name'] .'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '. filesize($file_to_save));
			header('Cache-Control: max-age=0');
			header('Accept-Ranges: bytes');
			readfile($file_to_save);

			unlink($file_to_save);
		} else {
			$this->load->language('customergroupjproduct/customergroupjproduct');
			$this->session->data['warning'] = sprintf($this->language->get('error_exportedfile_missing'), $this->request->get['file_name']);
			$this->response->redirect($this->url->link('customergroupjproduct/customergroupjproduct', $this->JocToken.'=' . $this->session->data[$this->JocToken], true));
		}
	}
	public function sampleFileDownload() {
		if (!isset($this->request->get['type']) || (isset($this->request->get['type']) && !in_array($this->request->get['type'], array('products', 'categories')))) {
			$this->load->language('customergroupjproduct/customergroupjproduct');
			$this->session->data['warning'] = $this->language->get('error_invalid_url');
			$this->response->redirect($this->url->link('customergroupjproduct/customergroupjproduct', $this->JocToken.'=' . $this->session->data[$this->JocToken], true));
		}

		$file_name = 'customergroupjproduct_'. $this->request->get['type'] .'_demo_file.xls';
		$file_to_save = DIR_IMAGE . 'customergroupjproduct/' . $file_name;

		if (file_exists($file_to_save)) {
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $file_name .'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '. filesize($file_to_save));
			header('Cache-Control: max-age=0');
			header('Accept-Ranges: bytes');
			readfile($file_to_save);

			unlink($file_to_save);

		} else {
			$this->load->language('customergroupjproduct/customergroupjproduct');
			$this->session->data['warning'] = sprintf($this->language->get('error_samplefile_missing'), $this->language->get('file_'.$this->request->get['type']) );
			$this->response->redirect($this->url->link('customergroupjproduct/customergroupjproduct', $this->JocToken.'=' . $this->session->data[$this->JocToken], true));
		}
	}

	protected function clean($data) {
		if (is_array($data)) {
		   foreach ($data as $key => $value) {
		    unset($data[$key]);
		    $data[$this->clean($key)] = $this->clean($value);
		   }
	  	} else {
			if (ini_get('magic_quotes_gpc')) {
				$data = htmlspecialchars(stripslashes($data), ENT_COMPAT, 'UTF-8');
			} else {
		   		$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		   	}
		}
		return $data;
	}
	// import export
	public function assign() {
		$json = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customergroupjproduct_customergroupjproduct->addProductCustomergroup($this->request->post);
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	public function saveData() {
		$json = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();

			$data['jcgpca_customer_group_ids'] = $this->request->post['customer_group_ids'];
			$data['jcgpca_categories'] = isset($this->request->post['categories']) ? $this->request->post['categories'] : array();
			$data['jcgpca_products'] = isset($this->request->post['products']) ? $this->request->post['products'] : array();
			$data['jcgpca_all_categories'] = $this->request->post['all_categories'];
			$data['jcgpca_all_products'] = $this->request->post['all_products'];

			$this->model_setting_setting->editSetting('jcgpca_', $data);

			$json['success'] = $this->language->get('text_save_success');
		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customergroupjproduct/customergroupjproduct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->post['customer_group_ids'])) {
			$this->error['customer_group'] = $this->language->get('error_customer_group');
		}

		if((!$this->request->post['all_categories'] && empty($this->request->post['categories'])) && (!$this->request->post['all_products'] && empty($this->request->post['products']))) {
			$this->error['none'] = $this->language->get('error_none');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}
