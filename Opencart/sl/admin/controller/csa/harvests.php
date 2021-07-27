<?php

class ControllerCsaHarvests extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('csa/harvests');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/harvests');

        $this->getList();
    }

    public function add() {
        $this->load->language('csa/harvests');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/harvests');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
           
            $this->model_csa_harvests->addHarvest($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('csa/harvests');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/harvests');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            
            $this->model_csa_harvests->editHarvest($this->request->get['harvest_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'csa/harvests')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function delete() {
        $this->load->language('csa/harvests');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('csa/harvests');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $harvest_id) {
                $this->model_csa_harvests->deleteHarvest($harvest_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = '';
        }

        if (isset($this->request->get['filter_start_date'])) {
            $filter_start_date = $this->request->get['filter_start_date'];
        } else {
            $filter_start_date = '';
        }
        
        if (isset($this->request->get['filter_end_date'])) {
            $filter_end_date = $this->request->get['filter_end_date'];
        } else {
            $filter_end_date = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'harvest_title';
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

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_start_date'])) {
            $url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
        }
        
        if (isset($this->request->get['filter_end_date'])) {
            $url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
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
            'href' => $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('csa/harvests/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('csa/harvests/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['harvests'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'filter_start_date' => $filter_start_date,
            'filter_end_date' => $filter_end_date,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $harvest_total = $this->model_csa_harvests->getTotalHarvest($filter_data);

        $results = $this->model_csa_harvests->getHarvestList($filter_data);

        foreach ($results as $result) {
            $data['harvests'][] = array(
                'harvest_id' => $result['harvest_id'],
                'harvest_title' => $result['harvest_title'],
                'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'start_date' => date($this->language->get('date_format_short'), strtotime($result['start_date'])),
                'end_date' => date($this->language->get('date_format_short'), strtotime($result['end_date'])),
                'edit' => $this->url->link('csa/harvests/edit', 'user_token=' . $this->session->data['user_token'] . '&harvest_id=' . $result['harvest_id'] . $url, true)
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

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }


        if (isset($this->request->get['filter_start_date'])) {
            $url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
        }
        
        if (isset($this->request->get['filter_end_date'])) {
            $url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_harvest_title'] = $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . '&sort=harvest_title' . $url, true);
        $data['sort_start_date'] = $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . '&sort=start_date' . $url, true);
        $data['sort_end_date'] = $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . '&sort=end_date' . $url, true);
        $data['sort_status'] = $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);
        
        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_start_date'])) {
            $url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
        }
        
        if (isset($this->request->get['filter_end_date'])) {
            $url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $harvest_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($harvest_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($harvest_total - $this->config->get('config_limit_admin'))) ? $harvest_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $harvest_total, ceil($harvest_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;
        $data['filter_start_date'] = $filter_start_date;
        $data['filter_end_date'] = $filter_end_date;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('csa/harvests_list', $data));
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['harvest_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->get['harvest_id'])) {
            $data['harvest_id'] = $this->request->get['harvest_id'];
        } else {
            $data['harvest_id'] = 0;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['harvest_title'])) {
            $data['error_harvest_title'] = $this->error['harvest_title'];
        } else {
            $data['error_harvest_title'] = '';
        }

        if (isset($this->error['start_date'])) {
            $data['error_start_date'] = $this->error['start_date'];
        } else {
            $data['error_start_date'] = '';
        }

        if (isset($this->error['end_date'])) {
            $data['error_end_date'] = $this->error['end_date'];
        } else {
            $data['error_end_date'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_harvest'])) {
            $url .= '&filter_harvest=' . urlencode(html_entity_decode($this->request->get['filter_harvest'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['harvest_id'])) {
            $data['action'] = $this->url->link('csa/harvests/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('csa/harvests/edit', 'user_token=' . $this->session->data['user_token'] . '&harvest_id=' . $this->request->get['harvest_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('csa/harvests', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['harvest_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $harvest_info = $this->model_csa_harvests->getHarvest($this->request->get['harvest_id']);
        }

        if (isset($this->request->post['harvest_title'])) {
            $data['harvest_title'] = $this->request->post['harvest_title'];
        } elseif (!empty($harvest_info)) {
            $data['harvest_title'] = $harvest_info['harvest_title'];
        } else {
            $data['harvest_title'] = '';
        }

        if (isset($this->request->post['harvest_display_title'])) {
            $data['harvest_display_title'] = $this->request->post['harvest_display_title'];
        } elseif (!empty($harvest_info)) {
            $data['harvest_display_title'] = $harvest_info['harvest_display_title'];
        } else {
            $data['harvest_display_title'] = '';
        }
        
        if (isset($this->request->post['start_date'])) {
            $data['start_date'] = $this->request->post['start_date'];
        } elseif (!empty($harvest_info)) {
            $data['start_date'] = $harvest_info['start_date'];
        } else {
            $data['start_date'] = '';
        }
        
        if (isset($this->request->post['end_date'])) {
            $data['end_date'] = $this->request->post['end_date'];
        } elseif (!empty($harvest_info)) {
            $data['end_date'] = $harvest_info['end_date'];
        } else {
            $data['end_date'] = '';
        }
        
        if (isset($this->request->post['marketplace_start_date'])) {
            $data['marketplace_start_date'] = $this->request->post['marketplace_start_date'];
        } elseif (!empty($harvest_info)) {
            $data['marketplace_start_date'] = ($harvest_info['marketplace_start_date'] == '0000-00-00 00:00:00') ?  '' : $harvest_info['marketplace_start_date'];
        } else {
            $data['marketplace_start_date'] = '';
        }
        
        if (isset($this->request->post['marketplace_end_date'])) {
            $data['marketplace_end_date'] = $this->request->post['marketplace_end_date'];
        } elseif (!empty($harvest_info)) {
            $data['marketplace_end_date'] = ($harvest_info['marketplace_end_date'] == '0000-00-00 00:00:00') ?  '' : $harvest_info['marketplace_end_date'];
        } else {
            $data['marketplace_end_date'] = '';
        }
        
        if (isset($this->request->post['deliveries'])) {
            $data['deliveries'] = $this->request->post['deliveries'];
        } elseif (!empty($harvest_info)) {
            $data['deliveries'] = $harvest_info['deliveries'];
        } else {
            $data['deliveries'] = '';
        }
        
        if (isset($this->request->post['short_description'])) {
            $data['short_description'] = $this->request->post['short_description'];
        } elseif (!empty($harvest_info)) {
            $data['short_description'] = $harvest_info['short_description'];
        } else {
            $data['short_description'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($harvest_info)) {
            $data['status'] = $harvest_info['status'];
        } else {
            $data['status'] = 0;
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('csa/harvests_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'csa/harvests')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (utf8_strlen($this->request->post['harvest_title']) < 1) {
            $this->error['harvest_title'] = $this->language->get('error_harvest_title');
        }

        if (empty($this->request->post['start_date'])) {
            $this->error['start_date'] = $this->language->get('error_start_date');
        }

        if (empty($this->request->post['end_date'])) {
            $this->error['end_date'] = $this->language->get('error_end_date');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $this->load->model('csa/harvests');

            $filter_data = array(
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_csa_harvests->getHarvestList($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'harvest_id' => $result['harvest_id'],
                    'harvest_title' => strip_tags(html_entity_decode($result['harvest_title'], ENT_QUOTES, 'UTF-8')),
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['harvest_title'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
