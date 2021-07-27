<?php

class ControllerExtensionModulesimpleinstagramwidget extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/simpleinstagramwidget');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            if (! isset($this->request->get['module_id'])) {
                
                $this->model_setting_module->addModule('simpleinstagramwidget', $this->request->post);
                
                $module_id   =  $this->db->getLastId();
                $module_info = $this->model_setting_module->getModule($module_id);
                if ($module_info) {
                    $module_info['module_id'] = $module_id;
                    $this->model_setting_module->editModule($module_id, $module_info);
                }
                
            } else {
                
                $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
                
                $this->cache->delete('simpleinstagramwidget');
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['error_access_token'])) {
            $data['error_access_token'] = $this->error['error_access_token'];
        } else {
            $data['error_access_token'] = '';
        }
        
        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }
        
        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
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

        if (! isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/simpleinstagramwidget', 'user_token=' . $this->session->data['user_token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/simpleinstagramwidget', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }

        if (! isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/simpleinstagramwidget', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/simpleinstagramwidget', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
        }

        // module_post

        if (isset($this->request->get['module_id'])) {
            $data['module_id']     = $this->request->get['module_id'];
        }
        
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (! empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['items_desktop'])) {
            $data['items_desktop'] = $this->request->post['items_desktop'];
        } elseif (! empty($module_info)) {
            $data['items_desktop'] = $module_info['items_desktop'];
        } else {
            $data['items_desktop'] = '3';
        }

        if (isset($this->request->post['items_tablet'])) {
            $data['items_tablet'] = $this->request->post['items_tablet'];
        } elseif (! empty($module_info)) {
            $data['items_tablet'] = $module_info['items_tablet'];
        } else {
            $data['items_tablet'] = '2';
        }

        if (isset($this->request->post['items_mobile'])) {
            $data['items_mobile'] = $this->request->post['items_mobile'];
        } elseif (! empty($module_info)) {
            $data['items_mobile'] = $module_info['items_mobile'];
        } else {
            $data['items_mobile'] = '2';
        }
        
        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (! empty($module_info)) {
            $data['width'] = $module_info['width'];
        } else {
            $data['width'] = '100';
        }
        
        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (! empty($module_info)) {
            $data['height'] = $module_info['height'];
        } else {
            $data['height'] = '100';
        }

        if (isset($this->request->post['limit'])) {
            $data['limit'] = $this->request->post['limit'];
        } elseif (! empty($module_info)) {
            $data['limit'] = $module_info['limit'];
        } else {
            $data['limit'] = '5';
        }

        if (isset($this->request->post['account_url'])) {
            $data['account_url'] = $this->request->post['account_url'];
        } elseif (! empty($module_info)) {
            $data['account_url'] = $module_info['account_url'];
        } else {
            $data['account_url'] = '';
        }

        if (isset($this->request->post['access_token'])) {
            $data['access_token'] = $this->request->post['access_token'];
        } elseif (! empty($module_info)) {
            $data['access_token'] = $module_info['access_token'];
        } else {
            $data['access_token'] = '';
        }
       
        if (isset($this->request->post['module_description'])) {
            $data['module_description'] = $this->request->post['module_description'];
        } elseif (! empty($module_info)) {
            $data['module_description'] = $module_info['module_description'];
        } else {
            $data['module_description'] = array();
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (! empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        if ($this->request->server['HTTPS']) {
            $server = HTTPS_SERVER;
        } else {
            $server = HTTP_SERVER;
        }

        $server = str_replace("/admin/", "", $server);

        $data['redirect_uri'] = $server . "/index.php?route=extension/module/simpleinstagramwidget/redirect_uri";

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/simpleinstagramwidget', $data));
    }

    protected function validate()
    {
        if (! $this->user->hasPermission('modify', 'extension/module/simpleinstagramwidget')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->post['width']) {
            $this->error['width'] = $this->language->get('error_width');
        }
        
        if (!$this->request->post['height']) {
            $this->error['height'] = $this->language->get('error_height');
        }
                
        if ((utf8_strlen($this->request->post['access_token']) < 3)) {
            $this->error['error_access_token'] = $this->language->get('error_access_token');
        }
      
        return ! $this->error;
    }
}