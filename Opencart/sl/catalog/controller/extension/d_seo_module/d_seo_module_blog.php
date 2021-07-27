<?php
class ControllerExtensionDSEOModuleDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	
	/*
	*	Functions for SEO Module.
	*/	
	public function seo_url_add_rewrite() {
		$this->load->model('setting/setting');

		// Setting
		$_config = new Config();
		$_config->load($this->config_file);
		$config_setting = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		$setting = ($this->config->get('module_' . $this->codename . '_setting')) ? $this->config->get('module_' . $this->codename . '_setting') : array();
		
		if (!empty($setting)) {
			$config_setting = array_replace_recursive($config_setting, $setting);
		}
		
		$setting = $config_setting;
		
		$setting['custom_page_exception_routes'] = $this->load->controller('extension/module/d_seo_module_url/getCustomPageExceptionRoutes');
		
		$this->config->set('module_' . $this->codename . '_setting', $setting);
				
		if ($status) {			
			// Register Cache
			if (!$this->registry->has('d_cache') && file_exists(DIR_SYSTEM . 'library/d_cache.php')) {
				$this->registry->set('d_cache', new d_cache());
			}
		
			// Add rewrite to url class
			if ($this->config->get('config_seo_url')) {
				$this->url->addRewrite($this);
			}
		}
	}
	
	public function seo_url_analyse() {
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
				
		$store_id = (int)$this->config->get('config_store_id');
		
		// Setting
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		$setting = ($this->config->get('module_' . $this->codename . '_setting')) ? $this->config->get('module_' . $this->codename . '_setting') : array();
		$blog_setting = ($this->config->get('d_blog_module_setting')) ? $this->config->get('d_blog_module_setting') : array();
						
		if ($status) {						
			if (!isset($this->request->get['route']) && isset($this->request->get['_route_'])) {
				$parts = explode('/', $this->request->get['_route_']);

				// remove any empty arrays from trailing
				if (utf8_strlen(end($parts)) == 0) {
					array_pop($parts);
				}
				
				if ($setting['multi_language_sub_directory']['status']) {
					foreach ($setting['multi_language_sub_directory']['name'] as $subdirectory_language_id => $subdirectory_name) {
						if ($subdirectory_name == reset($parts)) {
							$multi_language_sub_directory_language_id = $subdirectory_language_id;
						
							array_shift($parts);
						
							break;
						}
					}
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

						if ($route[0] == 'bm_post_id') {
							$this->request->get['post_id'] = $route[1];
						}
					
						if ($route[0] == 'bm_category_id') {
							$this->request->get['category_id'] = $route[1];
						}

						if ($route[0] == 'bm_author_id') {
							$author = $this->{'model_extension_module_' . $this->codename}->getAuthor($route[1]);
						
							if (isset($author['user_id'])) {
								$this->request->get['user_id'] = $author['user_id'];
							}
						}
						
						if (preg_match('/[A-Za-z0-9]+\/[A-Za-z0-9]+/i', $route[0])) {
							$this->request->get['route'] = $route[0];
						}
					} else {										
						break;
					}
				}
				
				if (isset($multi_language_sub_directory_language_id)) {
					$language_id = $multi_language_sub_directory_language_id;
				}
				
				if (isset($language_id)) {
					$this->load->model($this->route);
					$this->load->model('localisation/language');
										
					$language_info = $this->model_localisation_language->getLanguage($language_id);
							
					if ($this->session->data['language'] != $language_info['code']) {
						$this->session->data['language'] = $language_info['code'];
						setcookie('language', $language_info['code'], time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
						
						if (VERSION >= '2.2.0.0') {
							$language = new Language($language_info['code']);
							$language->load($language_info['code']);
		
							$this->registry->set('language', $language);
		
							$this->config->set('config_language_id', $language_id);	
						} else {
							$language = new Language($language_info['directory']);
							$language->load($language_info['directory']);
							
							$this->registry->set('language', $language);
							
							$this->config->set('config_language_id', $language_id);
							$this->config->set('config_language', $language_info['code']);
						}
					}	
				}

				if (!isset($this->request->get['route'])) {
					if (isset($this->request->get['post_id'])) {
						$this->request->get['route'] = 'extension/d_blog_module/post';
					} elseif (isset($this->request->get['category_id'])) {
						$this->request->get['route'] = 'extension/d_blog_module/category';
					} elseif (isset($this->request->get['user_id'])) {
						$this->request->get['route'] = 'extension/d_blog_module/author';
					}
				}
			}
			
			if (isset($this->request->get['route'])) {
				if ($this->request->get['route'] == 'extension/d_blog_module/category') {
					if (isset($this->request->get['category_id'])) {
						$category_id = (int)$this->request->get['category_id'];
					} elseif (isset($blog_setting['main_category_id'])) {
						$category_id = $blog_setting['main_category_id'];
					} else {
						$category_id = 1;
					}
								
					if ($category_id) {		
						if ($setting['sheet']['blog_category']['unique_url']) {						
							$url_data = array();
						
							$url_data['category_id'] = $category_id;						
						
							$exception_data = explode(',', $setting['sheet']['blog_category']['exception_data']);
						
							foreach ($exception_data as $exception) {
								$exception = trim($exception);
								
								if (isset($this->request->get[$exception])) {
									$url_data[$exception] = html_entity_decode($this->request->get[$exception]);
								}
							}
															
							$url_from = $this->{'model_extension_d_seo_module_' . $this->codename}->getCurrentURL();
							$url_to = $this->url->link($this->request->get['route'], http_build_query($url_data), true);
							
							if (($url_to != $url_from) && ($url_to != urldecode($url_from))) {
								$this->response->redirect($url_to, '301');
							}
						}
					}
				} elseif ($this->request->get['route'] == 'extension/d_blog_module/post') {
					if (isset($this->request->get['post_id'])) {
						$post_id = (int)$this->request->get['post_id'];
					} else {
						$post_id = 0;
					}
				
					if ($post_id) {	
						if ($setting['sheet']['blog_post']['unique_url']) {							
							$url_data = array();
																					
							$url_data['post_id'] = $post_id;
						
							$exception_data = explode(',', $setting['sheet']['blog_post']['exception_data']);
						
							foreach ($exception_data as $exception) {
								$exception = trim($exception);
								
								if (isset($this->request->get[$exception])) {
									$url_data[$exception] = html_entity_decode($this->request->get[$exception]);
								}
							}			
						
							$url_from = $this->{'model_extension_d_seo_module_' . $this->codename}->getCurrentURL();
							$url_to = $this->url->link($this->request->get['route'], http_build_query($url_data), true);
							
							if (($url_to != $url_from) && ($url_to != urldecode($url_from))) {
								$this->response->redirect($url_to, '301');
							}
						}
					}	
				} elseif ($this->request->get['route'] == 'extension/d_blog_module/author') {
					if (isset($this->request->get['user_id'])) {
						$user_id = (int)$this->request->get['user_id'];
					} else {
						$user_id = 0;
					}
								
					if ($user_id) {
						if ($setting['sheet']['blog_author']['unique_url']) {
							$url_data = array();
							
							$url_data['user_id'] = $user_id;
						
							$exception_data = explode(',', $setting['sheet']['blog_author']['exception_data']);
							
							foreach ($exception_data as $exception) {
								$exception = trim($exception);
								
								if (isset($this->request->get[$exception])) {
									$url_data[$exception] = html_entity_decode($this->request->get[$exception]);
								}
							}
						
							$url_from = $this->{'model_extension_d_seo_module_' . $this->codename}->getCurrentURL();
							$url_to = $this->url->link($this->request->get['route'], http_build_query($url_data), true);
							
							if (($url_to != $url_from) && ($url_to != urldecode($url_from))) {
								$this->response->redirect($url_to, '301');
							}
						}
					}
				} elseif ($this->request->get['route'] == 'extension/d_blog_module/search') {
					if ($setting['sheet']['blog_search']['unique_url']) {
						$url_data = array();
						
						$exception_data = explode(',', $setting['sheet']['blog_search']['exception_data']);
							
						foreach ($exception_data as $exception) {
							$exception = trim($exception);
							
							if (isset($this->request->get[$exception])) {
								$url_data[$exception] = html_entity_decode($this->request->get[$exception]);
							}
						}
						
						$url_from = $this->{'model_extension_d_seo_module_' . $this->codename}->getCurrentURL();
						$url_to = $this->url->link($this->request->get['route'], http_build_query($url_data), true);
							
						if (($url_to != $url_from) && ($url_to != urldecode($url_from))) {
							$this->response->redirect($url_to, '301');
						}
					}
				}
			}			
		}
	}
		
	public function rewrite($url) {
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
		
		// Setting
		$setting = ($this->config->get('module_' . $this->codename . '_setting')) ? $this->config->get('module_' . $this->codename . '_setting') : array();
		$blog_setting = ($this->config->get('d_blog_module_setting')) ? $this->config->get('d_blog_module_setting') : array();
						
		$url_info = $this->{'model_extension_d_seo_module_' . $this->codename}->getURLInfo($url);
			
		if (isset($url_info['data']['route']) && $this->registry->has('d_cache')) {
			$store_id = (int)$this->config->get('config_store_id');
			$language_id = (int)$this->config->get('config_language_id');
			
			$url_rewrite = false;
			
			if ($url_info['data']['route'] == 'extension/d_blog_module/category') {
				if (isset($url_info['data']['category_id'])) {
					$category_id = (int)$url_info['data']['category_id'];
				} elseif (isset($blog_setting['main_category_id'])) {
					$category_id = (int)$blog_setting['main_category_id'];
				} else {
					$category_id = 0;
				}
									
				if ($category_id) {
					$url_rewrite = $this->d_cache->get($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $category_id . '.' . $store_id . '.' . $language_id);
					
					if ($url_rewrite) {
						unset($url_info['data']['route']);
						unset($url_info['data']['category_id']);
					} elseif ($url_rewrite === false) {
						$url_rewrite = '';
							
						if (!$setting['sheet']['blog_category']['short_url']) {
							$category_path = $this->{'model_extension_d_seo_module_' . $this->codename}->getCategoryPath($category_id);
						} else {
							$category_path = $category_id;						
						}
					
						unset($url_info['data']['category_id']);
					
						if ($category_path) {
							$url_info['data'] = array_slice($url_info['data'], 0, 1, true) + array('category_id' => $category_path) + array_slice($url_info['data'], 1, count($url_info['data']) - 1, true);
																
							$sub_categories_id = explode('_', $category_path);
								
							foreach ($sub_categories_id as $sub_category_id) {
								$route = 'bm_category_id=' . $sub_category_id;
								
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

										break;
									}
								}
							}
						}
						
						if ($url_rewrite && $setting['multi_language_sub_directory']['status'] && isset($setting['multi_language_sub_directory']['name'][$language_id]) && $setting['multi_language_sub_directory']['name'][$language_id]) {
							$url_rewrite = '/' . $setting['multi_language_sub_directory']['name'][$language_id] . $url_rewrite;
						}
							
						$this->d_cache->set($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $category_id . '.' . $store_id . '.' . $language_id, $url_rewrite);
							
						if ($url_rewrite) {
							unset($url_info['data']['route']);
							unset($url_info['data']['category_id']);
						}
					}
				}
			} elseif ($url_info['data']['route'] == 'extension/d_blog_module/post') {
				if (isset($url_info['data']['post_id'])) {
					$post_id = (int)$url_info['data']['post_id'];
				} else {
					$post_id = 0;
				}
				
				if ($post_id) {
					$url_rewrite = $this->d_cache->get($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $post_id . '.' . $store_id . '.' . $language_id);
						
					if ($url_rewrite) {
						unset($url_info['data']['route']);
						unset($url_info['data']['category_id']);
						unset($url_info['data']['post_id']);
					} elseif ($url_rewrite === false) {
						$url_rewrite = '';
							
						if (!$setting['sheet']['blog_post']['short_url']) {
							$post_path = $this->{'model_extension_d_seo_module_' . $this->codename}->getPostPath($post_id);
						} else {
							$post_path = '';
						}
							
						unset($url_info['data']['category_id']);
												
						if ($post_path) {
							$url_info['data'] = array_slice($url_info['data'], 0, 1, true) + array('category_id' => $post_path) + array_slice($url_info['data'], 1, count($url_info['data']) - 1, true);
								
							$sub_categories_id = explode('_', $post_path);
								
							foreach ($sub_categories_id as $sub_category_id) {
								$route = 'bm_category_id=' . $sub_category_id;
								
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

										break;
									}
								}
							}
						}
								
						$route = 'bm_post_id=' . $post_id; 
								
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
						
						if ($url_rewrite && $setting['multi_language_sub_directory']['status'] && isset($setting['multi_language_sub_directory']['name'][$language_id]) && $setting['multi_language_sub_directory']['name'][$language_id]) {
							$url_rewrite = '/' . $setting['multi_language_sub_directory']['name'][$language_id] . $url_rewrite;
						}
							
						$this->d_cache->set($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $post_id . '.' . $store_id . '.' . $language_id, $url_rewrite);
								
						if ($url_rewrite) {
							unset($url_info['data']['route']);
							unset($url_info['data']['category_id']);
							unset($url_info['data']['post_id']);
						}
					}
				}
			} elseif ($url_info['data']['route'] == 'extension/d_blog_module/author') {
				if (isset($url_info['data']['user_id'])) {
					$user_id = (int)$url_info['data']['user_id'];
				} else {
					$user_id = 0;
				}
				
				if ($user_id) {
					$url_rewrite = $this->d_cache->get($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $user_id . '.' . $store_id . '.' . $language_id);
						
					if ($url_rewrite) {
						unset($url_info['data']['route']);
						unset($url_info['data']['user_id']);
					} elseif ($url_rewrite === false) {
						$url_rewrite = '';
				
						$author = $this->{'model_extension_module_' . $this->codename}->getAuthorByUser($user_id);
				
						if (isset($author['author_id'])) {
							$route = 'bm_author_id=' . $author['author_id'];
											
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
								}
							}
							
							if ($url_rewrite && $setting['multi_language_sub_directory']['status'] && isset($setting['multi_language_sub_directory']['name'][$language_id]) && $setting['multi_language_sub_directory']['name'][$language_id]) {
								$url_rewrite = '/' . $setting['multi_language_sub_directory']['name'][$language_id] . $url_rewrite;
							}
							
							$this->d_cache->set($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $user_id . '.' . $store_id . '.' . $language_id, $url_rewrite);
							
							if ($url_rewrite) {
								unset($url_info['data']['route']);
								unset($url_info['data']['user_id']);
							}
						}	
					}
				}
			} elseif ($url_info['data']['route'] == 'extension/d_blog_module/search') {
				$url_rewrite = $this->d_cache->get($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $store_id . '.' . $language_id);
					
				if ($url_rewrite) {
					unset($url_info['data']['route']);
				} elseif ($url_rewrite === false) {
					$url_rewrite = '';
				
					$route = $url_info['data']['route'];
							
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
							if (substr($url_keyword, 0, 1) == '/') {
								$url_keyword = substr($url_keyword, 1, strlen($url_keyword) - 1);
							}
						
							$url_rewrite .= '/' . $url_keyword;
						}
					}

					if ($url_rewrite && $setting['multi_language_sub_directory']['status'] && isset($setting['multi_language_sub_directory']['name'][$language_id]) && $setting['multi_language_sub_directory']['name'][$language_id]) {
						$url_rewrite = '/' . $setting['multi_language_sub_directory']['name'][$language_id] . $url_rewrite;
					}					

					$this->d_cache->set($this->codename, 'url_rewrite.' . preg_replace('/[^A-Z0-9\._-]/i', '_', $url_info['data']['route']) . '.' . $store_id . '.' . $language_id, $url_rewrite);
							
					if ($url_rewrite) {
						unset($url_info['data']['route']);
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
	
	public function language_language() {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status && isset($this->request->post['redirect'])) {
			$this->request->post['redirect'] = $this->{'model_extension_d_seo_module_' . $this->codename}->getURLForLanguage($this->request->post['redirect'], $this->session->data['language']);
		}
	}
		
	public function header_after($html) {
		$this->load->model('extension/module/' . $this->codename);
				
		// Setting
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		$blog_setting = ($this->config->get('d_blog_module_setting')) ? $this->config->get('d_blog_module_setting') : array();
						
		if ($status && file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {
			if (isset($this->request->get['route'])) {				
				if ($this->request->get['route'] == 'extension/d_blog_module/category') {
					if (isset($this->request->get['category_id'])) {
						$category_id = (int)$this->request->get['category_id'];
					} elseif (isset($blog_setting['main_category_id'])) {
						$category_id = $blog_setting['main_category_id'];
					} else {
						$category_id = 1;
					}
													
					if ($category_id) {	
						$route = $this->request->get['route'];
						$args = 'category_id=' . $category_id;
						
						foreach ($this->request->get as $key => $value) {
							if (($key != 'route') && ($key != '_route_') && ($key != 'category_id')) {
								$args .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
							}
						}
					}
				} elseif ($this->request->get['route'] == 'extension/d_blog_module/post') {
					if (isset($this->request->get['post_id'])) {
						$post_id = (int)$this->request->get['post_id'];
					} else {
						$post_id = 0;
					}
				
					if ($post_id) {
						$route = $this->request->get['route'];
						$args = 'post_id=' . $post_id;
						
						foreach ($this->request->get as $key => $value) {
							if (($key != 'route') && ($key != '_route_') && ($key != 'post_id')) {
								$args .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
							}
						}
					}
				} elseif ($this->request->get['route'] == 'extension/d_blog_module/author') {
					if (isset($this->request->get['user_id'])) {
						$user_id = (int)$this->request->get['user_id'];
					} else {
						$user_id = 0;
					}
								
					if ($user_id) {
						$route = $this->request->get['route'];
						$args = 'user_id=' . $user_id;
						
						foreach ($this->request->get as $key => $value) {
							if (($key != 'route') && ($key != '_route_') && ($key != 'user_id')) {
								$args .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
							}
						}
					}
				} elseif ($this->request->get['route'] == 'extension/d_blog_module/search') {
					$route = $this->request->get['route'];
					$args = '';
					
					foreach ($this->request->get as $key => $value) {
						if (($key != 'route') && ($key != '_route_')) {
							$args .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
						}
					}
				}
								
				$html_links = '';
				$alternate_links = array();
								
				$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
							
				foreach ($languages as $language) {
					if (isset($route) && isset($args)) {
						$config_language_id = $this->config->get('config_language_id');
						$this->config->set('config_language_id', $language['language_id']);	
						$alternate_link = $this->url->link($route, $args, true);
						
						if (!in_array($alternate_link, $alternate_links)) {
							$alternate_links[] = $alternate_link;
							$html_links .= '<link rel="alternate" hreflang="' . preg_replace('/-(.+?)+/', '', $language['code']) . '" href="' . $alternate_link . '" />' . "\n";
						}
						
						$this->config->set('config_language_id', $config_language_id);	
					}
				}	
				
				if (count($alternate_links) > 1) {
					$html_dom = new d_simple_html_dom();
					$html_dom->load((string)$html, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
			
					foreach ($html_dom->find('head') as $element) {
						$element->innertext .= $html_links;
					}
				
					return (string)$html_dom;
				}
			}
		}
		
		return $html;
	}
	
	public function field_config() {
		$_language = new Language();
		$_language->load($this->route);
		
		$_config = new Config();
		$_config->load($this->config_file);
		$field_setting = ($_config->get($this->codename . '_field_setting')) ? $_config->get($this->codename . '_field_setting') : array();

		foreach ($field_setting['sheet'] as $sheet) {				
			if (substr($sheet['name'], 0, strlen('text_')) == 'text_') {
				$field_setting['sheet'][$sheet['code']]['name'] = $_language->get($sheet['name']);
			}
			
			foreach ($sheet['field'] as $field) {
				if (substr($field['name'], 0, strlen('text_')) == 'text_') {
					$field_setting['sheet'][$sheet['code']]['field'][$field['code']]['name'] = $_language->get($field['name']);
				}
				
				if (substr($field['description'], 0, strlen('help_')) == 'help_') {
					$field_setting['sheet'][$sheet['code']]['field'][$field['code']]['description'] = $_language->get($field['description']);
				}
			}
		}
					
		return $field_setting;
	}
	
	public function field_elements($filter_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->getFieldElements($filter_data);
	}
	
	public function custom_page_exception_routes() {	
		$_config = new Config();
		$_config->load($this->config_file);
		$config_setting = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();
			
		return $config_setting['custom_page_exception_routes'];
	}
}