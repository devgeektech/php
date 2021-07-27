<?php
class ControllerExtensionDSEOModuleDVisualDesignerLanding extends Controller
{
    private $codename = 'd_visual_designer_landing';
    private $route = 'extension/d_seo_module/d_visual_designer_landing';
    private $config_file = 'd_visual_designer_landing';
    
    /*
    *   Functions for SEO Module.
    */
    public function seo_url()
    {
        $this->load->model($this->route);
        $this->load->model('extension/module/' . $this->codename);
        $this->load->model('setting/setting');
        
        $store_id = (int)$this->config->get('config_store_id');

        $_config = new Config();
        $_config->load($this->config_file);

        $config_setting = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();

        $setting = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();
        
        if (!empty($setting)) {
            $config_setting = array_replace_recursive($config_setting, $setting);
        }
        
        $setting = $config_setting;

        // Register Cache
        if (!$this->registry->has('d_cache') && file_exists(DIR_SYSTEM . 'library/d_cache.php')) {
            $this->registry->set('d_cache', new d_cache());
        }

        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }

        if (!isset($this->request->get['route']) && !isset($this->request->get['_route_'])) {
            $this->request->get['route'] = 'common/home';
        }
        if (!isset($this->request->get['route']) && isset($this->request->get['_route_'])) {
            $parts = explode('/', $this->request->get['_route_']);

            // remove any empty arrays from trailing
            if (utf8_strlen(end($parts)) == 0) {
                array_pop($parts);
            }

            foreach ($parts as $part) {
                unset($route);
                unset($language_id);
                                        
                $field_data = array(
                        'field_code' => 'url_keyword',
                        'filter' => array(
                            'store_id' => $store_id,
                            'keyword' => $part
                        )
                    );
                $url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);

                if ($url_keywords) {
                    foreach ($url_keywords as $url_route => $store_url_keywords) {
                        foreach ($store_url_keywords[$store_id] as $url_language_id => $keyword) {
                            $route = $url_route;
                            $language_id = $url_language_id;
                        }
                            
                        foreach ($store_url_keywords[$store_id] as $url_language_id => $keyword) {
                            if ($url_language_id == (int)$this->config->get('config_language_id')) {
                                $route = $url_route;
                                $language_id = $url_language_id;
                            }
                        }
                    }
                }

                if (isset($route)) {
                    $route = explode('=', $route);
                    if ($route[0] == 'vdl_page_id') {
                        $this->request->get['page_id'] = $route[1];
                    }
                    
                    if (preg_match('/[A-Za-z0-9]+\/[A-Za-z0-9]+/i', $route[0])) {
                        $this->request->get['route'] = $route[0];
                    }
                }
            }
                
            if (isset($language_id)) {
                $this->load->model($this->route);
                $this->load->model('localisation/language');
                                        
                $language_info = $this->model_localisation_language->getLanguage($language_id);
                            
                if ($this->session->data['language'] != $language_info['code']) {
                    $this->session->data['language'] = $language_info['code'];
                    $this->response->redirect($this->{'model_extension_d_seo_module_' . $this->codename}->getCurrentURL(), '302');
                }
            }

            if (!isset($this->request->get['route'])) {
                if (isset($this->request->get['page_id'])) {
                    $this->request->get['route'] = 'extension/module/d_visual_designer_landing';
                }
            }
        }
        
        if (isset($this->request->get['route'])) {
            if ($this->request->get['route'] == 'extension/module/d_visual_designer_landing') {
                if (isset($this->request->get['page_id'])) {
                    $page_id = (int)$this->request->get['page_id'];
                } else {
                    $page_id = 0;
                }

                if ($page_id) {
                    if ($setting['sheet']['landing_page']['unique_url']) {
                        $url_data = array();
                        $url_data['page_id'] = $page_id;
                        
                        $exception_data = explode(',', $setting['sheet']['landing_page']['exception_data']);

                        foreach ($exception_data as $exception) {
                            $exception = trim($exception);
                                
                            if (isset($this->request->get[$exception])) {
                                $url_data[$exception] = $this->request->get[$exception];
                            }
                        }

                        if ($this->url->link($this->request->get['route'], http_build_query($url_data), true) != $this->{'model_extension_d_seo_module_' . $this->codename}->getCurrentURL()) {
                            $this->response->redirect($this->url->link($this->request->get['route'], http_build_query($url_data), true), '301');
                        }
                    }
                }

                if (isset($this->request->get['variation_id'])) {
                    $variation_id = (int)$this->request->get['variation_id'];
                } else {
                    $variation_id = 0;
                }

                if ($variation_id) {
                    if ($setting['sheet']['landing_page']['unique_url']) {
                        $url_data = array();
                        $url_data['variation_id'] = $variation_id;
                        
                        $exception_data = explode(',', $setting['sheet']['landing_page']['exception_data']);
                        foreach ($exception_data as $exception) {
                            $exception = trim($exception);
                                
                            if (isset($this->request->get[$exception])) {
                                $url_data[$exception] = $this->request->get[$exception];
                            }
                        }

                        if ($this->url->link($this->request->get['route'], http_build_query($url_data), true) != $this->{'model_extension_d_seo_module_' . $this->codename}->getCurrentURL()) {
                            $this->response->redirect($this->url->link($this->request->get['route'], http_build_query($url_data), true), '301');
                        }
                    }
                }
            }
        }
    }

    public function rewrite($url)
    {
        $this->load->model($this->route);
        $this->load->model('extension/module/' . $this->codename);
        
        // Setting
        $setting = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();

        $url_info = $this->{'model_extension_d_seo_module_' . $this->codename}->getURLInfo($url);

        if (isset($url_info['data']['route']) && $this->registry->has('d_cache')) {
            $store_id = (int)$this->config->get('config_store_id');
            $language_id = (int)$this->config->get('config_language_id');

            $url_rewrite = false;

            if ($url_info['data']['route'] == 'extension/module/d_visual_designer_landing' && isset($url_info['data']['page_id'])) {
                if (isset($url_info['data']['page_id'])) {
					$page_id = (int)$url_info['data']['page_id'];
				} else {
					$page_id = 0;
                }
                
                if ($page_id) {
					$url_rewrite = $this->d_cache->get($this->codename, 'url_rewrite.' . md5($url_info['data']['route']) . '.' . $page_id . '.' . $store_id . '.' . $language_id);
					if ($url_rewrite) {
						unset($url_info['data']['route']);
						unset($url_info['data']['page_id']);
					} elseif ($url_rewrite === false) {
						$url_rewrite = '';
							
						$route = 'vdl_page_id=' . $page_id; 
								
						$field_data = array(
							'field_code' => 'url_keyword',
							'filter' => array(
								'route' => $route,
								'store_id' => $store_id,
								'language_id' => $language_id
							)
                        );
                        
                        $url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
                        
								
						if ($url_keywords) {
							$url_keyword = $url_keywords[$route][$store_id][$language_id];
								
							if ($url_keyword) {
								$url_rewrite .= '/' . $url_keyword;
							} else {
								$url_rewrite = '';
							}
						}
							
						$this->d_cache->set($this->codename, 'url_rewrite.' . md5($url_info['data']['route']) . '.' . $page_id . '.' . $store_id . '.' . $language_id, $url_rewrite);
								
						if ($url_rewrite) {
							unset($url_info['data']['route']);
							unset($url_info['data']['page_id']);
						}
					}
				}
            } elseif($url_info['data']['route'] == 'extension/module/d_visual_designer_landing' && isset($url_info['data']['variation_id']) && !isset($url_info['data']['page_id'])) {

                $page_info = $this->{'model_extension_module_'.$this->codename}->getPageByVariation($url_info['data']['variation_id']);

                if (isset($page_info['page_id'])) {
					$page_id = (int)$page_info['page_id'];
				} else {
					$page_id = 0;
                }
                
                if ($page_id) {
					$url_rewrite = $this->d_cache->get($this->codename, 'url_rewrite.' . md5($url_info['data']['route']) . '.' . $page_id . '.' . $store_id . '.' . $language_id);
					if ($url_rewrite) {
						unset($url_info['data']['route']);
						unset($url_info['data']['page_id']);
					} elseif ($url_rewrite === false) {
						$url_rewrite = '';
							
						$route = 'vdl_page_id=' . $page_id; 
								
						$field_data = array(
							'field_code' => 'url_keyword',
							'filter' => array(
								'route' => $route,
								'store_id' => $store_id,
								'language_id' => $language_id
							)
                        );
                        
                        $url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
                        
								
						if ($url_keywords) {
							$url_keyword = $url_keywords[$route][$store_id][$language_id];
								
							if ($url_keyword) {
								$url_rewrite .= '/' . $url_keyword;
							} else {
								$url_rewrite = '';
							}
						}
							
						$this->d_cache->set($this->codename, 'url_rewrite.' . md5($url_info['data']['route']) . '.' . $page_id . '.' . $store_id . '.' . $language_id, $url_rewrite);
								
						if ($url_rewrite) {
							unset($url_info['data']['route']);
							unset($url_info['data']['page_id']);
						}
					}
				}

            }

            if ($url_rewrite) {			
				$url_info['path'] = str_replace('/index.php', '', $url_info['path']) . $url_rewrite;
				
				$url = $url_info['scheme'] . $url_info['host'] . $url_info['port'] . $url_info['path'];
				
				if ($url_info['data']) {
					$url .= '?' . http_build_query($url_info['data'], '', '&amp;');
				}
			}

        }

        return $url;
    }

    public function language_language()
    {
        $this->load->model($this->route);
        
        if (isset($this->request->post['redirect'])) {
            $this->request->post['redirect'] = $this->{'model_extension_d_seo_module_' . $this->codename}->getURLForLanguage($this->request->post['redirect'], $this->session->data['language']);
        }
    }
    

    public function field_elements($filter_data)
    {
        $this->load->model($this->route);
        
        return $this->{'model_extension_d_seo_module_' . $this->codename}->getFieldElements($filter_data);
    }
}
