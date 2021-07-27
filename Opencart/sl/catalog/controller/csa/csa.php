<?php

class ControllerCsaCsa extends Controller {

    public function index() {

        $this->language->load('csa/csa');
        $this->load->model('csa/csa');

        $this->document->setTitle($this->language->get('index_heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('csa/csa')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_title'] = $this->language->get('text_title');

        $data['text_description'] = $this->language->get('text_description');

        $data['text_date'] = $this->language->get('text_date');

        $data['text_view'] = $this->language->get('text_view');

        $data['login_link'] = $this->url->link('account/login', '', true);
        $data['register_link'] = $this->url->link('csa/csa', '', true);

        $filters = array(
            'filter_visible' => 1,
        );

        $all_csa = $this->model_csa_csa->getAllCSA($filters);

        $data['all_csa'] = array();

        $this->load->model('tool/image');
        $this->load->model('setting/module');
        $module_name = 'Shares Module';
        $setting_info = $this->model_setting_module->getModuleByName($module_name, 'featured');
        $data['featured'] = '';
        if ($setting_info && $setting_info['status']) {
            $data['featured'] = $this->load->controller('extension/module/featured', $setting_info);
        }

        foreach ($all_csa as $csa) {

            $csna = strtolower($csa['csaname']);
            $journalName = str_replace(' ', '_', $csna);
            $data['all_csa'][] = array(
                'csaname' => html_entity_decode($csa['csaname'], ENT_QUOTES),
                'description' => (utf8_strlen(strip_tags(html_entity_decode($csa['description'], ENT_QUOTES))) > 250 ? utf8_substr(strip_tags(html_entity_decode($csa['description'], ENT_QUOTES)), 0, 250) . '...' : strip_tags(html_entity_decode($csa['description'], ENT_QUOTES))),
                'view' => $this->url->link('csa/csa/csa', 'csa_id=' . $csa['csa_id']),
                'register_link' => $this->url->link('account/register', 'csa=' . $journalName),
                'date_added' => date('j M Y', strtotime($csa['date_added'])),
                'pickup_address' => html_entity_decode($csa['pickup_address'], ENT_QUOTES),
                'latitude' => html_entity_decode($csa['latitude'], ENT_QUOTES),
                'longitude' => html_entity_decode($csa['longitude'], ENT_QUOTES),
                'operating_hours' => html_entity_decode($csa['operating_hours'], ENT_QUOTES),
                'csa_email' => html_entity_decode($csa['csa_email'], ENT_QUOTES),
                'csa_phone' => html_entity_decode($csa['csa_phone'], ENT_QUOTES),
                'order_notification_email' => html_entity_decode($csa['order_notification_email'], ENT_QUOTES),
                'registration' => $csa['registration'],
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');

        $data['column_right'] = $this->load->controller('common/column_right');

        $data['content_top'] = $this->load->controller('common/content_top');

        $data['content_bottom'] = $this->load->controller('common/content_bottom');

        $data['footer'] = $this->load->controller('common/footer');

        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('csa/csa_list', $data));
    }

    public function csa() {

        $this->load->model('csa/csa');
        $this->language->load('csa/csa');

        if (isset($this->request->get['csa_id']) && !empty($this->request->get['csa_id'])) {
            $csa_id = $this->request->get['csa_id'];
        } else {
            $csa_id = 0;
        }

        $csa = $this->model_csa_csa->getCSA($csa_id);
     
        $data['breadcrumbs'] = array();

        /*$data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );*/

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('csa/csa')
        );

        if ($csa) {
            $data['breadcrumbs'][] = array(
                'text' => $csa['csaname'],
                'href' => '',//$this->url->link('csa/csa/csa', 'csa_id=' . $csa_id)
            );

            $this->document->setTitle($this->language->get('index_heading_title'));
            $this->load->model('tool/image');

            $csna = strtolower($csa['csaname']);
            $journalName = str_replace(' ', '_', $csna);

            if($csa['csa_image_type'] == 1) {
                $csa_image = preg_replace('/^(?!https?:\/\/)/', 'http://', html_entity_decode($csa['csa_image'], ENT_QUOTES));
            } elseif($csa['csa_image_type'] == 2) {
                $csa_image = $this->model_tool_image->resize($csa['csa_image'], 500, 500);
            } else {
                $csa_image = $this->model_tool_image->resize('no_image.png', 100, 100);
            }
            $data['csa_image'] = $csa_image;
            $data['csa_image_type'] = $csa['csa_image_type'];
                    
            $data['banner_image'] = $this->model_tool_image->resize($csa['csa_image'], 300, 300);

            $data['heading_title'] = html_entity_decode($csa['csaname'], ENT_QUOTES);
            
            $data['description'] = html_entity_decode($csa['description'], ENT_QUOTES);
            $data['date_added'] = html_entity_decode($csa['date_added'], ENT_QUOTES);
            $data['pickup_address'] = html_entity_decode($csa['pickup_address'], ENT_QUOTES);
            $data['register_link'] = $this->url->link('account/register', 'csa=' . $journalName);
            $data['latitude'] = html_entity_decode($csa['latitude'], ENT_QUOTES);
            $data['longitude'] = html_entity_decode($csa['longitude'], ENT_QUOTES);
            $data['operating_hours'] = html_entity_decode($csa['operating_hours'], ENT_QUOTES);
            $data['csa_email'] = html_entity_decode($csa['csa_email'], ENT_QUOTES);
            $data['website'] = !empty($csa['website']) ? preg_replace('/^(?!https?:\/\/)/', 'http://', html_entity_decode($csa['website'], ENT_QUOTES)) : '';
            $data['brochure_link'] = !empty($csa['brochure_link']) ? preg_replace('/^(?!https?:\/\/)/', 'http://', html_entity_decode($csa['brochure_link'], ENT_QUOTES)): '';
            $data['csa_phone'] = html_entity_decode($csa['csa_phone'], ENT_QUOTES);
            $data['checkout_volunteer_messages'] = html_entity_decode($csa['checkout_volunteer_messages'], ENT_QUOTES);
            $data['membership_requirements'] = html_entity_decode($csa['membership_requirements'], ENT_QUOTES);

            $data['recipe_link'] = $this->url->link('extension/d_blog_module/post/type&type=recipes');
            $data['news_link'] = $this->url->link('extension/d_blog_module/post/type&type=news');
            
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('csa/csa', $data));
        } else {

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('csa/csa/csa', 'csa_id=' . $csa_id)
            );



            $this->document->setTitle($this->language->get('text_error'));



            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');



            $data['column_left'] = $this->load->controller('common/column_left');

            $data['column_right'] = $this->load->controller('common/column_right');

            $data['content_top'] = $this->load->controller('common/content_top');

            $data['content_bottom'] = $this->load->controller('common/content_bottom');

            $data['footer'] = $this->load->controller('common/footer');

            $data['header'] = $this->load->controller('common/header');



            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

}