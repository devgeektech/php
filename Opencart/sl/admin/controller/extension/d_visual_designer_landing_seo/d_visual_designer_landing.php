<?php
class ControllerExtensionDVisualDesignerLandingSEODVisualDesignerLanding extends Controller
{
    private $codename = 'd_visual_designer_landing';
    private $route = 'extension/d_visual_designer_landing_seo/d_visual_designer_landing';
    private $config_file = 'd_visual_designer_landing';
    private $error = array();

    public function page_form_tab_general_language()
    {
        $this->load->model($this->route);
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('extension/module/' . $this->codename);
        
        $languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');

        if (isset($field_info['sheet']['landing_page']['field'])) {
            $data['fields'] = $field_info['sheet']['landing_page']['field'];
        } else {
            $data['fields'] = array();
        }
        
        $data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
        
        if (isset($this->request->post['meta_data'])) {
            $data['meta_data'] = $this->request->post['meta_data'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['meta_data'] = $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->getPageMetaData($this->request->get['page_id']);
        } else {
            $data['meta_data'] = array();
        }

        if (isset($this->request->post['url_keyword'])) {
            $data['url_keyword'] = $this->request->post['url_keyword'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['url_keyword'] = $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->getPageURLKeyword($this->request->get['page_id']);
        } else {
            $data['url_keyword'] = array();
        }

        if (isset($this->request->post['target_keyword'])) {
            $data['target_keyword'] = $this->request->post['target_keyword'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['target_keyword'] = $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->getPageTargetKeyword($this->request->get['page_id']);
        } else {
            $data['target_keyword'] = array();
        }

        $data['adviser_elements'] = array();
        $data['rating'] = array();
        
        if (isset($this->request->get['page_id'])) {
            $route = 'vdl_page_id=' . $this->request->get['page_id'];
            
            $adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);

            if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
                $data['adviser_elements'] = $adviser_info['adviser_elements'];
                $data['rating'] = $adviser_info['rating'];
            }
        }
        
        $data['store_id'] = 0;
        
        
        $html_tab_general_language = array();
                    
        foreach ($languages as $language) {
            $data['language_id'] = $language['language_id'];

            if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
                foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
                    $field_data = array(
                        'field_code' => 'target_keyword',
                        'filter' => array(
                            'store_id' => $data['store_id'],
                            'keyword' => $keyword
                        )
                    );
            
                    $target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
                    
                    if ($target_keywords) {
                        $store_target_keywords = reset($target_keywords);
                            
                        if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
                            $data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
                        }
                    }
                }
            }
            
            $html_tab_general_language[$data['language_id']] = $this->model_extension_d_opencart_patch_load->view($this->route . '/page_form_tab_general_language', $data);
        }

        return $html_tab_general_language;
    }
    
    public function page_form_tab_general_store_language()
    {
        $this->load->model($this->route);
        $this->load->model('extension/module/' . $this->codename);
        
        $stores = $this->{'model_extension_module_' . $this->codename}->getStores();
        $languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        if (isset($field_info['sheet']['landing_page']['field'])) {
            $data['fields'] = $field_info['sheet']['landing_page']['field'];
        } else {
            $data['fields'] = array();
        }

        $data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();

        if (isset($this->request->post['meta_data'])) {
            $data['meta_data'] = $this->request->post['meta_data'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['meta_data'] = $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->getPageMetaData($this->request->get['page_id']);
        } else {
            $data['meta_data'] = array();
        }
        
        if (isset($this->request->post['url_keyword'])) {
            $data['url_keyword'] = $this->request->post['url_keyword'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['url_keyword'] = $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->getPageURLKeyword($this->request->get['page_id']);
        } else {
            $data['url_keyword'] = array();
        }
        
        if (isset($this->request->post['target_keyword'])) {
            $data['target_keyword'] = $this->request->post['target_keyword'];
        } elseif (isset($this->request->get['page_id'])) {
            $data['target_keyword'] = $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->getPageTargetKeyword($this->request->get['page_id']);
        } else {
            $data['target_keyword'] = array();
        }

        $data['adviser_elements'] = array();
        $data['rating'] = array();
        
        if (isset($this->request->get['page_id'])) {
            $route = 'vdl_page_id=' . $this->request->get['page_id'];
            
            $adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);
        
            if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
                $data['adviser_elements'] = $adviser_info['adviser_elements'];
                $data['rating'] = $adviser_info['rating'];
            }
        }
        
        $html_tab_general_store_language = array();
        
        foreach ($stores as $store) {
            $data['store_id'] = $store['store_id'];
        
            foreach ($languages as $language) {
                $data['language_id'] = $language['language_id'];
                
                if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
                    foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
                        $field_data = array(
                            'field_code' => 'target_keyword',
                            'filter' => array(
                                'store_id' => $data['store_id'],
                                'keyword' => $keyword
                            )
                        );

                        $target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);

                        if ($target_keywords) {
                            $store_target_keywords = reset($target_keywords);
                            
                            if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
                                $data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
                            }
                        }
                    }
                }

                $html_tab_general_store_language[$data['store_id']][$data['language_id']] = $this->load->view($this->route . '/page_form_tab_general_store_language', $data);
            }
        }
        return $html_tab_general_store_language;
    }
    
    public function page_validate_form($error)
    {
        unset($error['keyword']);
        
        if (isset($this->request->post['url_keyword'])) {
            $_language = new Language();
            $_language->load('extension/d_seo_module/'.$this->codename);
                
            foreach ($this->request->post['url_keyword'] as $store_id => $language_url_keyword) {
                foreach ($language_url_keyword as $language_id => $url_keyword) {
                    if (trim($url_keyword)) {
                        $field_data = array(
                            'field_code' => 'url_keyword',
                            'filter' => array(
                            'store_id' => $store_id,
                            'keyword' => $url_keyword
                            )
                        );

                        $url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);

                        if ($url_keywords) {
                            if (isset($this->request->get['page_id'])) {
                                foreach ($url_keywords as $route => $store_url_keywords) {
                                    if ($route != 'vdl_page_id=' . $this->request->get['page_id']) {
                                        $error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
                                    }
                                }
                            } else {
                                $error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
                            }
                        }
                    }
                }
            }
            
            $this->config->set($this->codename . '_error', $error);
        }
        
        return $error;
    }
    
    public function page_form_script()
    {
        $url_token = '';
        
        if (isset($this->session->data['token'])) {
            $url_token .= 'token=' . $this->session->data['token'];
        }
        
        if (isset($this->session->data['user_token'])) {
            $url_token .= 'user_token=' . $this->session->data['user_token'];
        }
        
        $data['route'] = $this->route;
        $data['url_token'] = $url_token;
        
        return $this->load->view($this->route . '/page_form_script', $data);
    }
    
    public function page_form_style()
    {
        $url_token = '';
        
        if (isset($this->session->data['token'])) {
            $url_token .= 'token=' . $this->session->data['token'];
        }
        
        if (isset($this->session->data['user_token'])) {
            $url_token .= 'user_token=' . $this->session->data['user_token'];
        }
        
        $data['route'] = $this->route;
        $data['url_token'] = $url_token;

        return $this->load->view($this->route . '/page_form_style', $data);
    }
    
    public function page_add_product($data)
    {
        $this->load->model($this->route);
        
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->savePageMetaData($data);
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->savePageTargetKeyword($data);
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->savePageURLKeyword($data);
    }
    
    public function page_edit_product($data)
    {
        $this->load->model($this->route);
        
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->savePageMetaData($data);
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->savePageTargetKeyword($data);
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->savePageURLKeyword($data);
    }
    
    public function page_delete_product($data)
    {
        $this->load->model($this->route);
        
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->deletePageMetaData($data);
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->deletePageTargetKeyword($data);
        $this->{'model_extension_d_visual_designer_landing_seo_' . $this->codename}->deletePageURLKeyword($data);
    }
}
