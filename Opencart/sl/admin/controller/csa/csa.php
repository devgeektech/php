<?php

class ControllerCsaCsa extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('csa/csa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/csa');

        $this->getList();
    }

    public function add() {
        $this->load->language('csa/csa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/csa');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $csa_image = '';
            if (isset($this->request->post['csa_image_type'])) {
                $csa_image_type = $this->request->post['csa_image_type'];
                if ($csa_image_type == 1) {
                    $csa_image = $this->request->post['image_link'];
                } elseif ($csa_image_type == 2) {
                    $csa_image = $this->request->post['image'];
                }
            }

            $this->request->post['csa_image'] = $csa_image;

            $this->model_csa_csa->addCSA($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_visible'])) {
                $url .= '&filter_visible=' . urlencode(html_entity_decode($this->request->get['filter_visible'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_registration'])) {
                $url .= '&filter_registration=' . urlencode(html_entity_decode($this->request->get['filter_registration'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }


            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('csa/csa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/csa');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $csa_image = '';
            if (isset($this->request->post['csa_image_type'])) {
                $csa_image_type = $this->request->post['csa_image_type'];
                if ($csa_image_type == 1) {
                    $csa_image = $this->request->post['image_link'];
                } elseif ($csa_image_type == 2) {
                    $csa_image = $this->request->post['image'];
                }
            }

            $this->request->post['csa_image'] = $csa_image;

            $this->model_csa_csa->editCSA($this->request->get['csa_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_visible'])) {
                $url .= '&filter_visible=' . urlencode(html_entity_decode($this->request->get['filter_visible'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_registration'])) {
                $url .= '&filter_registration=' . urlencode(html_entity_decode($this->request->get['filter_registration'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'csa/csa')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function delete() {
        $this->load->language('csa/csa');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/csa');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $csa_id) {
                $this->model_csa_csa->deleteCSA($csa_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_email'])) {
                $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_visible'])) {
                $url .= '&filter_visible=' . urlencode(html_entity_decode($this->request->get['filter_visible'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_registration'])) {
                $url .= '&filter_registration=' . urlencode(html_entity_decode($this->request->get['filter_registration'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = '';
        }

        if (isset($this->request->get['filter_visible'])) {
            $filter_visible = $this->request->get['filter_visible'];
        } else {
            $filter_visible = '';
        }

        if (isset($this->request->get['filter_registration'])) {
            $filter_registration = $this->request->get['filter_registration'];
        } else {
            $filter_registration = '';
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = '';
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'csaname';
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_visible'])) {
            $url .= '&filter_visible=' . urlencode(html_entity_decode($this->request->get['filter_visible'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration'])) {
            $url .= '&filter_registration=' . urlencode(html_entity_decode($this->request->get['filter_registration'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

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
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('csa/csa/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('csa/csa/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['csas'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_email' => $filter_email,
            'filter_visible' => $filter_visible,
            'filter_registration' => $filter_registration,
            'filter_status' => $filter_status,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $csa_total = $this->model_csa_csa->getTotalCSA($filter_data);

        $results = $this->model_csa_csa->getCSAList($filter_data);

        foreach ($results as $result) {
            $data['csas'][] = array(
                'csa_id' => $result['csa_id'],
                'csaname' => $result['csaname'],
                'csa_email' => $result['csa_email'],
                'display' => ($result['display'] == '1') ? $server . 'view/image/active.gif' : $server . 'view/image/inactive.gif',
                'registration' => ($result['registration'] == '1') ? $server . 'view/image/active.gif' : $server . 'view/image/inactive.gif',
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'edit' => $this->url->link('csa/csa/edit', 'user_token=' . $this->session->data['user_token'] . '&csa_id=' . $result['csa_id'] . $url, true)
            );
        }

        $data['user_token'] = $this->session->data['user_token'];

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
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_visible'])) {
            $url .= '&filter_visible=' . urlencode(html_entity_decode($this->request->get['filter_visible'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration'])) {
            $url .= '&filter_registration=' . urlencode(html_entity_decode($this->request->get['filter_registration'], ENT_QUOTES, 'UTF-8'));
        }        
        
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }


        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . '&sort=csaname' . $url, true);
        $data['sort_display'] = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . '&sort=display' . $url, true);
        $data['sort_registration'] = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . '&sort=registration' . $url, true);
        $data['sort_email'] = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . '&sort=csa_email' . $url, true);
        $data['sort_status'] = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);
        $data['sort_date_added'] = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, true);

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_visible'])) {
            $url .= '&filter_visible=' . urlencode(html_entity_decode($this->request->get['filter_visible'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_registration'])) {
            $url .= '&filter_registration=' . urlencode(html_entity_decode($this->request->get['filter_registration'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $csa_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($csa_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($csa_total - $this->config->get('config_limit_admin'))) ? $csa_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $csa_total, ceil($csa_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_email'] = $filter_email;
        $data['filter_visible'] = $filter_visible;
        $data['filter_registration'] = $filter_registration;
        $data['filter_status'] = $filter_status;
        $data['filter_date_added'] = $filter_date_added;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('csa/csa_list', $data));
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['csa_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->get['csa_id'])) {
            $data['csa_id'] = $this->request->get['csa_id'];
        } else {
            $data['csa_id'] = 0;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['csaname'])) {
            $data['error_csaname'] = $this->error['csaname'];
        } else {
            $data['error_csaname'] = '';
        }

        if (isset($this->error['pickup_address'])) {
            $data['error_pickup_address'] = $this->error['pickup_address'];
        } else {
            $data['error_pickup_address'] = '';
        }

        if (isset($this->error['operating_hours'])) {
            $data['error_operating_hours'] = $this->error['operating_hours'];
        } else {
            $data['error_operating_hours'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_ip'])) {
            $url .= '&filter_ip=' . $this->request->get['filter_ip'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

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
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['csa_id'])) {
            $data['action'] = $this->url->link('csa/csa/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('csa/csa/edit', 'user_token=' . $this->session->data['user_token'] . '&csa_id=' . $this->request->get['csa_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('csa/csa', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['csa_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $csa_info = $this->model_csa_csa->getCSA($this->request->get['csa_id']);
        }

        if (isset($this->request->post['display'])) {
            $data['display'] = $this->request->post['display'];
        } elseif (!empty($csa_info)) {
            $data['display'] = $csa_info['display'];
        } else {
            $data['display'] = '';
        }

        if (isset($this->request->post['registration'])) {
            $data['registration'] = $this->request->post['registration'];
        } elseif (!empty($csa_info)) {
            $data['registration'] = $csa_info['registration'];
        } else {
            $data['registration'] = '';
        }

        if (isset($this->request->post['csaname'])) {
            $data['csaname'] = $this->request->post['csaname'];
        } elseif (!empty($csa_info)) {
            $data['csaname'] = $csa_info['csaname'];
        } else {
            $data['csaname'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($csa_info)) {
            $data['description'] = $csa_info['description'];
        } else {
            $data['description'] = '';
        }
        
        if (isset($this->request->post['membership_requirements'])) {
            $data['membership_requirements'] = $this->request->post['membership_requirements'];
        } elseif (!empty($csa_info)) {
            $data['membership_requirements'] = $csa_info['membership_requirements'];
        } else {
            $data['membership_requirements'] = '';
        }        

        if (isset($this->request->post['pickup_address'])) {
            $data['pickup_address'] = $this->request->post['pickup_address'];
        } elseif (!empty($csa_info)) {
            $data['pickup_address'] = $csa_info['pickup_address'];
        } else {
            $data['pickup_address'] = '';
        }

        if (isset($this->request->post['latitude'])) {
            $data['latitude'] = $this->request->post['latitude'];
        } elseif (!empty($csa_info)) {
            $data['latitude'] = $csa_info['latitude'];
        } else {
            $data['latitude'] = '';
        }

        if (isset($this->request->post['longitude'])) {
            $data['longitude'] = $this->request->post['longitude'];
        } elseif (!empty($csa_info)) {
            $data['longitude'] = $csa_info['longitude'];
        } else {
            $data['longitude'] = '';
        }

        if (isset($this->request->post['operating_hours'])) {
            $data['operating_hours'] = $this->request->post['operating_hours'];
        } elseif (!empty($csa_info)) {
            $data['operating_hours'] = $csa_info['operating_hours'];
        } else {
            $data['operating_hours'] = '';
        }
        
        if (isset($this->request->post['delivery_day'])) {
            $data['delivery_day'] = $this->request->post['delivery_day'];
        } elseif (!empty($csa_info)) {
            $data['delivery_day'] = $csa_info['delivery_day'];
        } else {
            $data['delivery_day'] = '';
        }
        
        if (isset($this->request->post['csa_admin_fee'])) {
            $data['csa_admin_fee'] = $this->request->post['csa_admin_fee'];
        } elseif (!empty($csa_info)) {
            $data['csa_admin_fee'] = $csa_info['csa_admin_fee'];
        } else {
            $data['csa_admin_fee'] = '';
        }

        if (isset($this->request->post['csa_email'])) {
            $data['csa_email'] = $this->request->post['csa_email'];
        } elseif (!empty($csa_info)) {
            $data['csa_email'] = $csa_info['csa_email'];
        } else {
            $data['csa_email'] = '';
        }

        if (isset($this->request->post['csa_phone'])) {
            $data['csa_phone'] = $this->request->post['csa_phone'];
        } elseif (!empty($csa_info)) {
            $data['csa_phone'] = $csa_info['csa_phone'];
        } else {
            $data['csa_phone'] = '';
        }

        if (isset($this->request->post['website'])) {
            $data['website'] = $this->request->post['website'];
        } elseif (!empty($csa_info)) {
            $data['website'] = $csa_info['website'];
        } else {
            $data['website'] = '';
        }

        if (isset($this->request->post['brochure_link'])) {
            $data['brochure_link'] = $this->request->post['brochure_link'];
        } elseif (!empty($csa_info)) {
            $data['brochure_link'] = $csa_info['brochure_link'];
        } else {
            $data['brochure_link'] = '';
        }

        $this->load->model('tool/image');
        if (isset($this->request->post['csa_image_type'])) {
            $data['csa_image_type'] = $this->request->post['csa_image_type'];
            
            if($data['csa_image_type'] == 1) {
                $data['csa_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                $data['image'] = '';
                $data['image_link'] = $this->request->post['image_link'];
            } elseif($data['csa_image_type']  == 2) {
                $data['csa_image'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
                $data['image'] = $this->request->post['image'];
                $data['image_link'] = '';
            } else {           
                $data['csa_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                $data['image'] = '';
                $data['image_link'] = '';
            }
        } elseif (!empty($csa_info)) {
            $data['csa_image_type'] = $csa_info['csa_image_type'];
            if($data['csa_image_type'] == 1) {
                $data['csa_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                $data['image'] = '';
                $data['image_link'] = $csa_info['csa_image'];
            } elseif($data['csa_image_type']  == 2) {
                $data['csa_image'] = $this->model_tool_image->resize($csa_info['csa_image'], 100, 100);
                $data['image'] = $csa_info['csa_image'];
                $data['image_link'] = '';
            } else {           
                $data['csa_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                $data['image'] = '';
                $data['image_link'] = '';
            }
        } else {
            $data['csa_image_type'] = 0;
            $data['csa_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            $data['image'] = '';
            $data['image_link'] = '';
        }
        
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['order_notification_email'])) {
            $data['order_notification_email'] = $this->request->post['csaname'];
        } elseif (!empty($csa_info)) {
            $data['order_notification_email'] = $csa_info['order_notification_email'];
        } else {
            $data['order_notification_email'] = '';
        }

        if (isset($this->request->post['volunteering_required'])) {
            $data['volunteering_required'] = $this->request->post['volunteering_required'];
        } elseif (!empty($csa_info)) {
            $data['volunteering_required'] = $csa_info['volunteering_required'];
        } else {
            $data['volunteering_required'] = '';
        }

        if (isset($this->request->post['checkout_volunteer_messages'])) {
            $data['checkout_volunteer_messages'] = $this->request->post['checkout_volunteer_messages'];
        } elseif (!empty($csa_info)) {
            $data['checkout_volunteer_messages'] = $csa_info['checkout_volunteer_messages'];
        } else {
            $data['checkout_volunteer_messages'] = '';
        }

        if (isset($this->request->post['allow_share_partners'])) {
            $data['allow_share_partners'] = $this->request->post['allow_share_partners'];
        } elseif (!empty($csa_info)) {
            $data['allow_share_partners'] = $csa_info['allow_share_partners'];
        } else {
            $data['allow_share_partners'] = '';
        }

        if (isset($this->request->post['allow_share_partners'])) {
            $data['allow_share_partners'] = $this->request->post['allow_share_partners'];
        } elseif (!empty($csa_info)) {
            $data['allow_share_partners'] = $csa_info['allow_share_partners'];
        } else {
            $data['allow_share_partners'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($csa_info)) {
            $data['status'] = $csa_info['status'];
        } else {
            $data['status'] = 0;
        }

        $this->load->model('customer/customer_group');

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

        if (isset($this->request->post['customer_group_id'])) {
            $data['customer_group_id'] = $this->request->post['customer_group_id'];
        } elseif (!empty($csa_info)) {
            $data['customer_group_id'] = $csa_info['customer_group_id'];
        } else {
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }
        
        $this->load->model('extension/module/warehouse');

        $data['warehouses'] = $this->model_extension_module_warehouse->getwarehouses();

        if (isset($this->request->post['warehouse_id'])) {
            $data['warehouse_id'] = $this->request->post['warehouse_id'];
        } elseif (!empty($csa_info)) {
            $data['warehouse_id'] = $csa_info['warehouse_id'];
        } else {
            $data['warehouse_id'] = 0;
        }
        
        if (isset($this->request->post['delivery_date'])) {
                $delivery_dates = $this->request->post['delivery_date'];
        } elseif (isset($this->request->get['csa_id'])) {
                $delivery_dates = $this->model_csa_csa->getCSADeliveryDates($this->request->get['csa_id']);
        } else {
                $delivery_dates = array();
        }

        $data['delivery_dates'] = array();
        $this->load->model('csa/harvests');
        $harvest_details = $this->model_csa_harvests->getCurrentActiveHarvest();
        $harvest_id = $harvest_details['harvest_id']; 
        
        $data['product_shares'] = $this->model_csa_csa->getShareProducts($harvest_id);
                
        if(empty($delivery_dates)){ 
            //create data from harvest start date and end date selection
            $data['delivery_dates'] = $this->model_csa_csa->getdeliveryDateByHarvestId($data['delivery_day'],$harvest_details);
        } else {
            foreach ($delivery_dates as $delivery_date) {
                $data['delivery_dates'][] = array(                        
                    'weeks'            => $delivery_date['weeks'],
                    'delivery_date'    => ($delivery_date['delivery_date'] != '0000-00-00') ? $delivery_date['delivery_date'] : '',
                    'odd_even_week'    => $delivery_date['odd_even_week'],
                    'beginning_of'     => $delivery_date['beginning_of'],
                    'note'             => $delivery_date['note'],
                );
            }
        }
        
       

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('csa/csa_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'csa/csa')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (utf8_strlen($this->request->post['csaname']) < 1) {
            $this->error['csaname'] = $this->language->get('error_csaname');
        }

        if ((utf8_strlen($this->request->post['pickup_address']) < 1)) {
            $this->error['pickup_address'] = $this->language->get('error_pickup_address');
        }

        if ((utf8_strlen($this->request->post['operating_hours']) < 1)) {
            $this->error['operating_hours'] = $this->language->get('error_operating_hours');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            $this->load->model('csa/csa');

            $filter_data = array(
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_csa_csa->getCSAList($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'csa_id' => $result['csa_id'],
                    'csaname' => strip_tags(html_entity_decode($result['csaname'], ENT_QUOTES, 'UTF-8')),
                    'csa_email' => $result['csa_email'],
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['csaname'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
