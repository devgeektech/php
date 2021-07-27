<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionModuleDVisualDesignerLanding extends Controller
{
    private $codename = 'd_visual_designer_landing';
    private $route = 'extension/module/d_visual_designer_landing';
    private $extension = '';
    private $store_id = 0;
    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model($this->route);
        $this->load->language($this->route);

        $this->d_shopunity       = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_shopunity.json'));
        $this->d_opencart_patch  = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_opencart_patch.json'));
        $this->d_twig_manager    = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_twig_manager.json'));
        $this->d_event_manager   = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_event_manager.json'));
        $this->d_visual_designer = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_visual_designer.json'));
        $this->d_seo_module      = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_seo_module.json'));
        $this->extension         = json_decode(file_get_contents(DIR_SYSTEM . 'library/d_shopunity/extension/' . $this->codename . '.json'), true);
        $this->store_id          = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
        $this->d_admin_style = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_admin_style.json'));
    }

    public function index()
    {
        if ($this->d_twig_manager) {
            $this->load->model('extension/module/d_twig_manager');
            $this->model_extension_module_d_twig_manager->installCompatibility();
        }

        if ($this->d_event_manager) {
            $this->load->model('extension/module/d_event_manager');
            $this->model_extension_module_d_event_manager->installCompatibility();
            
            //remove in 3.1.0
            $this->installEvents();
        }

        if ($this->d_shopunity) {
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
        }

        $this->getList();
    }

    public function add()
    {
        $this->load->model('extension/d_opencart_patch/url');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
            $page_id = $this->{'model_extension_module_' . $this->codename}->addPage($this->request->post);

            $variation_data = array(
                'status' => 1,
                'sort_order' => 0,
                'description' => array()
            );

            $this->load->model('localisation/language');

            $languages = $this->model_localisation_language->getLanguages();

            foreach ($languages as $value) {
                $variation_data['description'][$value['language_id']] = array(
                    'description' => '');
            }

            $this->{'model_extension_module_' . $this->codename}->addVariation($page_id, $variation_data);

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route, $url));
        }

        $this->getForm();
    }

    public function edit()
    {

        $this->load->model('extension/d_opencart_patch/url');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
            $this->{'model_extension_module_' . $this->codename}->editPage($this->request->get['page_id'], $this->request->post);
            if (!empty($this->request->post['variation'])) {
                foreach ($this->request->post['variation'] as $variation_id => $status) {
                    $this->{'model_extension_module_' . $this->codename}->setStatus($variation_id, $status);
                }
            }
            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route, $url));
        }
        $this->getForm();
    }

    public function delete()
    {
        $this->load->model('extension/d_opencart_patch/url');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $page_id) {
                $this->{'model_extension_module_' . $this->codename}->deletePage($page_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

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

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route, $url));
        }

        $this->getList();
    }

    protected function getList()
    {
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/load');

        if($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getAdminStyle('light');
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.title';
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
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->model_extension_d_opencart_patch_url->link('marketplace/extension', 'type=module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route, $url)
        );

        $data['add']    = $this->model_extension_d_opencart_patch_url->link($this->route . '/add', $url);
        $data['delete'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/delete', $url);

        $data['setup_link'] = $this->model_extension_d_opencart_patch_url->ajax($this->route.'/setup');

        $data['cancel'] = $this->model_extension_d_opencart_patch_url->getExtensionLink('module');

        $data['setup'] = false;

        $data['text_setup'] = $this->language->get('text_setup');

        $data['pages'] = array();

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $page_total = $this->{'model_extension_module_' . $this->codename}->getTotalPages();

        $results = $this->{'model_extension_module_' . $this->codename}->getPages($filter_data);
        if(!empty($results)) {
            $data['setup'] = true;
        }
        foreach ($results as $result) {
            $variations = $this->{'model_extension_module_' . $this->codename}->getVariations($result['page_id']);

            $implode = array();
            foreach ($variations as $key => $value) {
                $implode[] = $value['symbol'];
            }
            $data['pages'][] = array(
                'page_id' => $result['page_id'],
                'variations' => implode(', ', $implode),
                'title' => $result['title'],
                'sort_order' => $result['sort_order'],
                'view' => $this->{'model_extension_module_' . $this->codename}->getLink('extension/module/d_visual_designer_landing', 'page_id=' . $result['page_id'], true),
                'edit' => $this->model_extension_d_opencart_patch_url->link($this->route . '/edit', 'page_id=' . $result['page_id'] . $url, true)
            );
        }


        $data['version'] = $this->extension['version'];

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');

        $data['text_list']       = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm']    = $this->language->get('text_confirm');

        $data['column_title']      = $this->language->get('column_title');
        $data['column_variations'] = $this->language->get('column_variations');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_action']     = $this->language->get('column_action');

        $data['button_add']         = $this->language->get('button_add');
        $data['button_edit']        = $this->language->get('button_edit');
        $data['button_delete']      = $this->language->get('button_delete');
        $data['button_view']        = $this->language->get('button_view');
        $data['button_cancel']      = $this->language->get('button_cancel');
        $data['button_create_page'] = $this->language->get('button_create_page');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_title']      = $this->model_extension_d_opencart_patch_url->link($this->route, '&sort=pd.title' . $url);
        $data['sort_sort_order'] = $this->model_extension_d_opencart_patch_url->link($this->route, '&sort=p.sort_order' . $url);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination        = new Pagination();
        $pagination->total = $page_total;
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url   = $this->model_extension_d_opencart_patch_url->link($this->route, $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($page_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($page_total - $this->config->get('config_limit_admin'))) ? $page_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $page_total, ceil($page_total / $this->config->get('config_limit_admin')));

        $data['sort']  = $sort;
        $data['order'] = $order;

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/' . $this->codename . '/page_list', $data));
    }

    protected function getForm()
    {
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/load');

        if($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getAdminStyle('light');
        }

        $this->document->addStyle('view/stylesheet/d_visual_designer_landing/d_visual_designer_landing.css');

        //Bootstrap Switch
        $this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');

        $this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');


        $data['version'] = $this->extension['version'];

        $data['heading_title'] = $this->language->get('heading_title_main');

        $data['text_form']     = !isset($this->request->get['page_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_default']  = $this->language->get('text_default');
        $data['text_enabled']  = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes']      = $this->language->get('text_yes');
        $data['text_no']       = $this->language->get('text_no');

        $data['text_variation']            = $this->language->get('text_variation');
        $data['text_status']               = $this->language->get('text_status');
        $data['text_variation_edit']       = $this->language->get('text_variation_edit');
        $data['text_view_variation']       = $this->language->get('text_view_variation');
        $data['text_create_new_variation'] = $this->language->get('text_create_new_variation');
        $data['text_create_variation']     = $this->language->get('text_create_variation');
        $data['text_set_status']           = $this->language->get('text_set_status');
        $data['text_delete_variation']     = $this->language->get('text_delete_variation');
        $data['text_count_view']           = $this->language->get('text_count_view');
        $data['text_copy_variation']       = $this->language->get('text_copy_variation');
        $data['text_important']            = $this->language->get('text_important');
        $data['text_warning']              = $this->language->get('text_warning');

        $data['entry_title']            = $this->language->get('entry_title');
        $data['entry_description']      = $this->language->get('entry_description');
        $data['entry_meta_title']       = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword']     = $this->language->get('entry_meta_keyword');
        $data['entry_keyword']          = $this->language->get('entry_keyword');
        $data['entry_store']            = $this->language->get('entry_store');
        $data['entry_bottom']           = $this->language->get('entry_bottom');
        $data['entry_sort_order']       = $this->language->get('entry_sort_order');
        $data['entry_status']           = $this->language->get('entry_status');
        $data['entry_layout']           = $this->language->get('entry_layout');
        $data['entry_header_status']    = $this->language->get('entry_header_status');
        $data['entry_footer_status']    = $this->language->get('entry_footer_status');
        $data['entry_display_title']    = $this->language->get('entry_display_title');
        $data['entry_full_width']       = $this->language->get('entry_full_width');

        $data['help_keyword'] = $this->language->get('help_keyword');
        $data['help_bottom']  = $this->language->get('help_bottom');

        $data['button_save']   = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_copy']   = $this->language->get('button_copy');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_add']    = $this->language->get('button_add');

        $data['tab_general']   = $this->language->get('tab_general');
        $data['tab_data']      = $this->language->get('tab_data');
        $data['tab_variation'] = $this->language->get('tab_variation');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = array();
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
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
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->model_extension_d_opencart_patch_url->link('marketplace/extension', '&type=module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route, $url)
        );

        if (!isset($this->request->get['page_id'])) {
            $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/add', $url);
        } else {
            $data['action']        = $this->model_extension_d_opencart_patch_url->link($this->route . '/edit', 'page_id=' . $this->request->get['page_id'] . $url);
            $data['add_variation'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/addVariation', 'page_id=' . $this->request->get['page_id'] . $url);
        }

        $data['cancel'] = $this->model_extension_d_opencart_patch_url->link($this->route, $url);

        $data['page_id'] = '';

        if (isset($this->request->get['page_id'])) {
            $page_info       = $this->{'model_extension_module_' . $this->codename}->getPage($this->request->get['page_id']);
            $variations      = $this->{'model_extension_module_' . $this->codename}->getVariations($this->request->get['page_id']);
            $data['page_id'] = $this->request->get['page_id'];
        }

        $data['token']     = $this->model_extension_d_opencart_patch_user->getToken();
        $data['url_token'] = $this->model_extension_d_opencart_patch_user->getUrlToken();

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        foreach ($data['languages'] as $key => $language) {
            if (VERSION >= '2.2.0.0') {
                $data['languages'][$key]['flag'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
            } else {
                $data['languages'][$key]['flag'] = 'view/image/flags/' . $language['image'];
            }
        }

        if (isset($this->request->post['page_description'])) {
            $data['page_description'] = $this->request->post['page_description'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['page_description'] = $this->{'model_extension_module_' . $this->codename}->getPageDescriptions($this->request->get['page_id']);
        } else {
            $data['page_description'] = array();
        }
        if (isset($this->request->get['page_id'])) {
            $results = $this->{'model_extension_module_' . $this->codename}->getVariations($this->request->get['page_id']);

            $data['variations'] = array();

            foreach ($results as $variation_id => $value) {
                $data['variations'][] = array(
                    'variation_id' => $variation_id,
                    'character' => $value['symbol'],
                    'status' => $value['status'],
                    'count_view' => $value['view'],
                    'view' => $this->{'model_extension_module_' . $this->codename}->getLink($this->route, 'variation_id=' . $variation_id, true),
                    'copy' => $this->model_extension_d_opencart_patch_url->link($this->route . '/copyVariation', 'page_id=' . $this->request->get['page_id'] . '&variation_id=' . $variation_id),
                    'edit' => $this->model_extension_d_opencart_patch_url->link($this->route . '/editVariation', 'variation_id=' . $variation_id),
                    'delete' => $this->model_extension_d_opencart_patch_url->link($this->route . '/deleteVariation', 'variation_id=' . $variation_id . '&page_id=' . $this->request->get['page_id']),
                );
            }
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['page_store'])) {
            $data['page_store'] = $this->request->post['page_store'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['page_store'] = $this->{'model_extension_module_' . $this->codename}->getPageStores($this->request->get['page_id']);
        } else {
            $data['page_store'] = array(0);
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($page_info)) {
            $data['status'] = $page_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['header_status'])) {
            $data['header_status'] = $this->request->post['header_status'];
        } elseif (!empty($page_info)) {
            $data['header_status'] = $page_info['header_status'];
        } else {
            $data['header_status'] = true;
        }

        if (isset($this->request->post['footer_status'])) {
            $data['footer_status'] = $this->request->post['footer_status'];
        } elseif (!empty($page_info)) {
            $data['footer_status'] = $page_info['footer_status'];
        } else {
            $data['footer_status'] = true;
        }

        if (isset($this->request->post['display_title'])) {
            $data['display_title'] = $this->request->post['display_title'];
        } elseif (isset($page_info['display_title'])) {
            $data['display_title'] = $page_info['display_title'];
        } else {
            $data['display_title'] = true;
        }

        if (isset($this->request->post['full_width'])) {
            $data['full_width'] = $this->request->post['full_width'];
        } elseif (isset($page_info['full_width'])) {
            $data['full_width'] = $page_info['full_width'];
        } else {
            $data['full_width'] = false;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($page_info)) {
            $data['sort_order'] = $page_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        $installed_seo_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOExtensions();

        if (!in_array('d_seo_module', $installed_seo_extensions)) {
            $info = $this->load->controller('extension/d_seo_module/d_seo_module/control_install_extension');
            $this->load->language($this->route);

            if ($info) {
                if (!$info['error']) {
                    $installed_seo_extensions   = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOExtensions();
                }
            }
        }

        if (!in_array($this->codename, $installed_seo_extensions)) {
            $installed_seo_extensions[] = $this->codename;

            $this->{'model_extension_module_' . $this->codename}->saveSEOExtensions($installed_seo_extensions);
        }


        $this->prepareSeo($data);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/' . $this->codename . '/page_form', $data));
    }

    protected function prepareSeo(&$data)
    {
        $html_tab_general                = '';
        $html_tab_general_language       = array();
        $html_tab_general_store          = array();
        $html_tab_general_store_language = array();
        $html_style                      = '';
        $html_script                     = '';

        $seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOLandingExtensions();
        $languages      = $this->{'model_extension_module_' . $this->codename}->getLanguages();
        $stores         = $this->{'model_extension_module_' . $this->codename}->getStores();
        unset($stores[0]);;
        foreach ($seo_extensions as $seo_extension) {
            $info = $this->load->controller('extension/' . $this->codename . '_seo/' . $seo_extension . '/page_form_tab_general_language');

            foreach ($languages as $language) {
                if (!isset($html_tab_general_language[$language['language_id']])) {
                    $html_tab_general_language[$language['language_id']] = '';
                }

                if (isset($info[$language['language_id']])) {
                    $html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
                }
            }

            $info = $this->load->controller('extension/' . $this->codename . '_seo/' . $seo_extension . '/page_form_tab_general_store');

            foreach ($stores as $store) {
                if (!isset($html_tab_general_store[$store['store_id']])) {
                    $html_tab_general_store[$store['store_id']] = '';
                }

                if (isset($info[$store['store_id']])) {
                    $html_tab_general_store[$store['store_id']] .= $info[$store['store_id']];
                }
            }

            $info = $this->load->controller('extension/' . $this->codename . '_seo/' . $seo_extension . '/page_form_tab_general_store_language');

            foreach ($stores as $store) {
                foreach ($languages as $language) {
                    if (!isset($html_tab_general_store_language[$store['store_id']][$language['language_id']])) {
                        $html_tab_general_store_language[$store['store_id']][$language['language_id']] = '';
                    }

                    if (isset($info[$store['store_id']][$language['language_id']])) {
                        $html_tab_general_store_language[$store['store_id']][$language['language_id']] .= $info[$store['store_id']][$language['language_id']];
                    }
                }
            }

            $html_style  .= $this->load->controller('extension/' . $this->codename . '_seo/' . $seo_extension . '/page_form_style');
            $html_script .= $this->load->controller('extension/' . $this->codename . '_seo/' . $seo_extension . '/page_form_script');
        }


        $data['html_tab_general_language'] = $html_tab_general_language;
        $data['html_style']                = $html_style;
        $data['html_script']               = $html_script;

        $html_tab_general_language = reset($html_tab_general_store_language);

        if ((count($stores)) && (reset($html_tab_general_store) || reset($html_tab_general_language))) {
            $html_stores = '<ul class="nav nav-tabs" id="store">';

            foreach ($stores as $store) {
                $html_stores .= '<li' . (($store == reset($stores)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '" data-toggle="tab">' . $store['name'] . '</a></li>';
            }

            $html_stores .= '</ul>';
            $html_stores .= '<div class="tab-store tab-content">';

            foreach ($stores as $store) {
                $html_store_languages = '';

                if (reset($html_tab_general_store_language[$store['store_id']])) {
                    $html_store_languages = '<ul class="nav nav-tabs" id="store_' . $store['store_id'] . '_language">';

                    foreach ($languages as $language) {
                        $html_store_languages .= '<li' . (($language == reset($languages)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '_language_' . $language['language_id'] . '" data-toggle="tab"><img src="' . $language['flag'] . '" title="' . $language['name'] . '" /> ' . $language['name'] . '</a></li>';
                    }

                    $html_store_languages .= '</ul>';
                    $html_store_languages .= '<div class="tab-language tab-content">';

                    foreach ($languages as $language) {
                        $html_store_languages .= '<div class="tab-pane' . (($language == reset($languages)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '_language_' . $language['language_id'] . '">' . $html_tab_general_store_language[$store['store_id']][$language['language_id']] . '</div>';
                    }

                    $html_store_languages .= '</div>';
                }

                $html_stores .= '<div class="tab-pane' . (($store == reset($stores)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '">' . $html_tab_general_store[$store['store_id']] . $html_store_languages . '</div>';
            }

            $html_stores         .= '</div>';
            $data['html_stores'] = $html_stores;
        }
    }

    public function addVariation()
    {
        $this->load->model('extension/d_opencart_patch/url');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateVariationForm()) {
            $this->{'model_extension_module_' . $this->codename}->addVariation($this->request->get['page_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page_id'])) {
                $url .= '&page_id=' . $this->request->get['page_id'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route . '/edit', $url));
        }

        $this->getVariationForm();
    }

    public function editVariation()
    {
        $this->load->model('extension/d_opencart_patch/url');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateVariationForm()) {
            $this->{'model_extension_module_' . $this->codename}->editVariation($this->request->get['variation_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page_id'])) {
                $url .= '&page_id=' . $this->request->get['page_id'];
            }

            $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route . '/edit', $url));
        }

        $this->getVariationForm();
    }

    public function copyVariation()
    {
        $this->load->model('extension/d_opencart_patch/url');

        if ($this->validateVariationCopy()) {
            $this->{'model_extension_module_' . $this->codename}->copyVariation($this->request->get['variation_id']);

            $this->session->data['success'] = $this->language->get('text_success');
        }
        $url = '';
        if (isset($this->request->get['page_id'])) {
            $url .= '&page_id=' . $this->request->get['page_id'];
        }

        $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route . '/edit', $url));
    }

    public function deleteVariation()
    {
        $this->load->model('extension/d_opencart_patch/url');

        if ($this->validateVariationDelete()) {
            $this->{'model_extension_module_' . $this->codename}->deleteVariation($this->request->get['variation_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
        }
        $url = '';

        if (isset($this->request->get['page_id'])) {
            $url .= '&page_id=' . $this->request->get['page_id'];
        }

        $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route . '/edit', $url));
    }

    public function getVariationForm()
    {
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/load');

        $this->document->addStyle('view/stylesheet/d_visual_designer_landing/d_visual_designer_landing.css');

        if($this->d_admin_style){
            $this->load->model('extension/d_admin_style/style');
            $this->model_extension_d_admin_style_style->getAdminStyle('light');
        }

        $this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');

        if (VERSION >= '2.3.0.0') {
            $this->document->addScript('view/javascript/summernote/summernote.js');
            $this->document->addScript('view/javascript/summernote/opencart.js');
            $this->document->addStyle('view/javascript/summernote/summernote.css');
        }

        //Visual Designer
        $vd_status = $this->config->get('d_visual_designer_status');

        //Bootstrap Switch
        $this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');


        $data['version'] = $this->extension['version'];

        $data['heading_title'] = $this->language->get('heading_title_main');

        $data['text_default']   = $this->language->get('text_default');
        $data['text_enabled']   = $this->language->get('text_enabled');
        $data['text_disabled']  = $this->language->get('text_disabled');
        $data['text_yes']       = $this->language->get('text_yes');
        $data['text_no']        = $this->language->get('text_no');
        $data['text_use']       = sprintf($this->language->get('text_use'), $this->model_extension_d_opencart_patch_url->link('extension/module/d_visual_designer'));
        $data['text_important'] = $this->language->get('text_important');

        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_status']      = $this->language->get('entry_status');

        $data['help_designer_support']         = $this->language->get('help_designer_support');
        $data['text_install_designer_support'] = $this->language->get('text_install_designer_support');

        $data['button_save']   = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_copy']   = $this->language->get('button_copy');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = array();
        }
        if (isset($this->request->get['variation_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $variation_info    = $this->{'model_extension_module_' . $this->codename}->getVariation($this->request->get['variation_id']);
            $data['text_form'] = sprintf($this->language->get('text_edit_variation'), $variation_info['symbol']);
            $data['page_id']   = $variation_info['page_id'];
        } else {
            $symbol            = $this->{'model_extension_module_' . $this->codename}->getLastSymbol($this->request->get['page_id']);
            $data['text_form'] = sprintf($this->language->get('text_add_variation'), chr(ord($symbol) + 1));
            $data['page_id']   = $this->request->get['page_id'];
        }

        if (!empty($this->request->get['page_id'])) {
            $page_id = $this->request->get['page_id'];
        } elseif (!empty($variation_info)) {
            $page_id = $variation_info['page_id'];
        } else {
            $page_id = 0;
        }

        $url = '';
        if (!empty($page_id)) {
            $url .= '&page_id=' . $page_id;
        }


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->model_extension_d_opencart_patch_url->link('marketplace/extension', 'type=module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route, $url)
        );

        if (!isset($this->request->get['variation_id'])) {
            $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/addVariation', $url);
        } else {
            $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/editVariation', 'variation_id=' . $this->request->get['variation_id'] . $url);
        }

        $data['cancel'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/edit', $url);

        $data['token']     = $this->model_extension_d_opencart_patch_user->getToken();
        $data['url_token'] = $this->model_extension_d_opencart_patch_user->getUrlToken();

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        foreach ($data['languages'] as $key => $language) {
            if (VERSION >= '2.2.0.0') {
                $data['languages'][$key]['flag'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
            } else {
                $data['languages'][$key]['flag'] = 'view/image/flags/' . $language['image'];
            }
        }

        if (isset($this->request->post['variation_description'])) {
            $data['variation_description'] = $this->request->post['variation_description'];
        } elseif (isset($this->request->get['variation_id'])) {
            $data['variation_description'] = $this->{'model_extension_module_' . $this->codename}->getVariationDescription($this->request->get['variation_id']);
        } else {
            $data['variation_description'] = array();
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($variation_info)) {
            $data['status'] = $variation_info['status'];
        } else {
            $data['status'] = 1;
        }

        $data['designer_installed'] = false;

        if ($this->d_visual_designer) {
            $this->load->model('extension/d_visual_designer/designer');
            $data['designer_installed'] = $this->model_extension_d_visual_designer_designer->checkConfig($this->codename);
        }

        $url = '';
        if (!empty($page_id)) {
            $url .= '&page_id=' . $page_id;
        }
        if (!empty($this->request->get['variation_id'])) {
            $url .= '&variation_id=' . $this->request->get['variation_id'];
        }

        $data['install_designer_support'] = $this->model_extension_d_opencart_patch_url->link($this->route . '/installDesigner' . $url);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/' . $this->codename . '/variation_form', $data));
    }

    public function installDesigner()
    {
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_visual_designer/designer');

        if ($this->d_visual_designer) {
            $this->model_extension_d_visual_designer_designer->installConfig($this->codename);
        }

        $url = '';
        if (!empty($this->request->get['page_id'])) {
            $url .= '&page_id=' . $this->request->get['page_id'];
        }
        if (!empty($this->request->get['variation_id'])) {
            $url .= '&variation_id=' . $this->request->get['variation_id'];
        }
        if (!empty($this->request->get['variation_id'])) {
            $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route . '/editVariation', $url));
        } else {
            $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route . '/addVariation', $url));
        }
    }

    public function setup()
    {
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/module');
        $this->load->model('extension/d_opencart_patch/setting');
        $this->load->model('extension/d_visual_designer/designer');

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        $page_info = array(
            'sort_order' => 0,
            'header_status' => 1,
            'footer_status' => 1,
            'display_title' => 1,
            'full_width' => 0,
            'status' => 1,
            'page_description' => array(),
            'page_store' => array(0)
        );

        foreach ($languages as $value) {
            $page_info['page_description'][$value['language_id']] = array(
                'title' => $this->language->get('text_landing_page'),
                'meta_title' => $this->language->get('text_landing_page'),
                'meta_description' => '',
                'meta_keyword' => '',
            );
        }

        $page_id = $this->{'model_extension_module_' . $this->codename}->addPage($page_info);

        $variation_data = array(
            'status' => 1,
            'sort_order' => 0,
            'vd_content' => array(),
            'description' => array()
        );


        foreach ($languages as $value) {
            $variation_data['description'][$value['language_id']] = array(
                'description' => '');
        }

        $this->{'model_extension_module_' . $this->codename}->addVariation($page_id, $variation_data);

        if ($this->d_visual_designer) {
            $this->load->model('extension/d_visual_designer/designer');
            $this->model_extension_d_visual_designer_designer->installConfig($this->codename);
        }  

        $this->session->data['success'] = $this->language->get('success_setup');
        $this->response->redirect($this->model_extension_d_opencart_patch_url->link($this->route));
    }



    public function install()
    {
        if ($this->d_shopunity) {
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
        }

        if ($this->d_visual_designer) {
            $this->load->model('extension/module/d_visual_designer');
            $this->model_extension_module_d_visual_designer->compressRiotTag();
        }

        $this->installEvents();

        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'access', 'd_visual_designer_module/mailchimp');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'access', 'd_visual_designer_module/mailerlite');

        $this->{'model_extension_module_' . $this->codename}->installModule();
    }

    public function uninstall()
    {

        $this->{'model_extension_module_' . $this->codename}->uninstallModule();
        if ($this->d_event_manager) {
            $this->load->model('extension/module/d_event_manager');
            $this->model_extension_module_d_event_manager->deleteEvent($this->codename);
        }
    }

    protected function installEvents()
    {
        if ($this->d_event_manager) {
            $this->load->model('extension/module/d_event_manager');
            $this->model_extension_module_d_event_manager->deleteEvent($this->codename);
            $this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/view/common/column_left/after', 'extension/event/d_visual_designer_landing/column_left_after');
            $this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/controller/checkout/success/before', 'extension/event/d_visual_designer_landing/eventSuccess');
        }
    }

    protected function validateVariationForm()
    {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['page_description'] as $language_id => $value) {
            if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
                $this->error['title'][$language_id] = $this->language->get('error_title');
            }

            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        $seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

        foreach ($seo_extensions as $seo_extension) {
            $info = $this->load->controller('extension/' . $this->codename . '_seo/' . $seo_extension . '/page_validate_form', $this->error);
            if ($info != '') {
                $this->error = $info;
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateVariationDelete()
    {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateVariationCopy()
    {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
