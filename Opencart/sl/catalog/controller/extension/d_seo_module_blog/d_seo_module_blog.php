<?php
class ControllerExtensionDSEOModuleBlogDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_blog/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	
	/*
	*	Functions for SEO Module Blog.
	*/	
	public function category_after($html) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		// Setting
		$_config = new Config();
		$_config->load($this->config_file);
		$config_setting = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		$setting = ($this->config->get('module_' . $this->codename . '_setting')) ? $this->config->get('module_' . $this->codename . '_setting') : array();
		$blog_setting = ($this->config->get('d_blog_module_setting')) ? $this->config->get('d_blog_module_setting') : array();
		
		if (!empty($setting)) {
			$config_setting = array_replace_recursive($config_setting, $setting);
		}
		
		$setting = $config_setting;
		
		if ($status && file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {
			if (isset($this->request->get['category_id'])) {
				$category_id = (int)$this->request->get['category_id'];
			} elseif (isset($blog_setting['main_category_id'])) {
				$category_id = $blog_setting['main_category_id'];
			} else {
				$category_id = 1;
			}
																	
			$route = 'bm_category_id=' . (int)$category_id;
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
						
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
										
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
				
				$html_dom = new d_simple_html_dom();
				$html_dom->load((string)$html, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);				
				
				if ($setting['sheet']['blog_category']['meta_title_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
					$meta_title_page = '';
					
					if (isset($setting['meta_title_page_template'][$language_id])) {
						$meta_title_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_title_page_template'][$language_id]);
					} elseif ($setting['meta_title_page_template_default']) {
						$meta_title_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_title_page_template_default']);
					}

					if ($meta_title_page) {
						if ($html_dom->find('title')) {
							foreach ($html_dom->find('title') as $element) {
								$element->innertext = $meta_title_page . ' ' . $meta_info['meta_title'];
							}
						} else {
							foreach ($html_dom->find('head') as $element) {
								$element->innertext .= '<title>' . $meta_title_page . ' ' . $meta_info['meta_title'] . '</title>' . "\n";
							}
						}
					}
				}
														
				if ($setting['sheet']['blog_category']['meta_description_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
					$meta_description_page = '';
					
					if (isset($setting['meta_description_page_template'][$language_id])) {
						$meta_description_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_description_page_template'][$language_id]);
					} elseif ($setting['meta_title_page_template_default']) {
						$meta_description_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_description_page_template_default']);
					}
					
					if ($meta_description_page) {
						if ($html_dom->find('meta[name="description"]')) {
							foreach ($html_dom->find('meta[name="description"]') as $element) {
								$element->setAttribute('content', $meta_description_page . ' ' . $meta_info['meta_description']);
							}
						} else {
							foreach ($html_dom->find('head') as $element) {
								$element->innertext .= '<meta name="description" content="' . $meta_description_page . ' ' . $meta_info['meta_description'] . '" />' . "\n";
							}
						}
					}
				}
				
				if (isset($meta_info['meta_robots']) && $meta_info['meta_robots']) {
					foreach ($html_dom->find('head') as $element) {
						$element->innertext .= '<meta name="robots" content="' . $meta_info['meta_robots'] . '" />' . "\n";
					}
				}
			
				if (isset($meta_info['custom_title_1']) && $meta_info['custom_title_1']) {
					foreach ($html_dom->find($setting['sheet']['blog_category']['custom_title_1_class']) as $element) {
						$element->innertext = $meta_info['custom_title_1'];
					}
				}
			
				if (isset($meta_info['custom_title_2']) && $meta_info['custom_title_2']) {
					foreach ($html_dom->find($setting['sheet']['blog_category']['custom_title_2_class']) as $element) {
						$element->innertext = $meta_info['custom_title_2'];
					}
				}
			
				if ((isset($meta_info['custom_image_title']) && $meta_info['custom_image_title']) || (isset($meta_info['custom_image_alt']) && $meta_info['custom_image_alt'])) {
					foreach ($html_dom->find($setting['sheet']['blog_category']['custom_image_class']) as $element) {
						if (isset($meta_info['custom_image_title']) && $meta_info['custom_image_title']) {
							$element->setAttribute('title', $meta_info['custom_image_title']);
						}
					
						if (isset($meta_info['custom_image_alt']) && $meta_info['custom_image_alt']) {
							$element->setAttribute('alt', $meta_info['custom_image_alt']);
						}
					}
				}
				
				$url = '';
			
				if ($setting['sheet']['blog_category']['canonical_link_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
					$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
				}
				
				$canonical_link = $this->url->link('extension/d_blog_module/category', 'category_id=' . $category_id . $url, true);
			
				if ($html_dom->find('link[rel="canonical"]')) {
					foreach ($html_dom->find('link[rel="canonical"]') as $element) {
						$element->setAttribute('href', $canonical_link);
					}
				} else {
					foreach ($html_dom->find('head') as $element) {
						$element->innertext .= '<link href="' . $canonical_link . '" rel="canonical" />' . "\n";
					}
				}
							
				return (string)$html_dom;
			}
		}
		
		return $html;
	}
	
	public function category_get_category($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {			
			$route = 'bm_category_id=' . (int)$data['category_id'];
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
			
				if (isset($meta_info['title']) && $meta_info['title']) {
					$data['title'] = $meta_info['title'];
				}
				
				if (isset($meta_info['short_description']) && $meta_info['short_description']) {
					$data['short_description'] = $meta_info['short_description'];
				}
			
				if (isset($meta_info['description']) && $meta_info['description']) {
					$data['description'] = $meta_info['description'];
				}
			
				if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
					$data['meta_title'] = $meta_info['meta_title'];
				}
			
				if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
					$data['meta_description'] = $meta_info['meta_description'];
				}
			
				if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
					$data['meta_keyword'] = $meta_info['meta_keyword'];
				}
			}
		}
		
		return $data;
	}
	
	public function category_get_categories($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => 'bm_category_id=%',
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			foreach ($data as $key => $category) {
				$route = 'bm_category_id=' . (int)$category['category_id'];
							
				if (isset($meta_data[$route][$store_id][$language_id])) {
					$meta_info = $meta_data[$route][$store_id][$language_id];
			
					if (isset($meta_info['title']) && $meta_info['title']) {
						$data[$key]['title'] = $meta_info['title'];
					}
					
					if (isset($meta_info['short_description']) && $meta_info['short_description']) {
						$data[$key]['short_description'] = $meta_info['short_description'];
					}
			
					if (isset($meta_info['description']) && $meta_info['description']) {
						$data[$key]['description'] = $meta_info['description'];
					}
			
					if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
						$data[$key]['meta_title'] = $meta_info['meta_title'];
					}
			
					if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
						$data[$key]['meta_description'] = $meta_info['meta_description'];
					}
			
					if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
						$data[$key]['meta_keyword'] = $meta_info['meta_keyword'];
					}
				}
			}
		}
		
		return $data;
	}
	
	public function category_get_all_categories($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => 'bm_category_id=%',
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			foreach ($data as $key => $category) {
				$route = 'bm_category_id=' . (int)$category['category_id'];
							
				if (isset($meta_data[$route][$store_id][$language_id])) {
					$meta_info = $meta_data[$route][$store_id][$language_id];
			
					if (isset($meta_info['title']) && $meta_info['title']) {
						$data[$key]['title'] = $meta_info['title'];
					}
					
					if (isset($meta_info['short_description']) && $meta_info['short_description']) {
						$data[$key]['short_description'] = $meta_info['short_description'];
					}
			
					if (isset($meta_info['description']) && $meta_info['description']) {
						$data[$key]['description'] = $meta_info['description'];
					}
			
					if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
						$data[$key]['meta_title'] = $meta_info['meta_title'];
					}
			
					if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
						$data[$key]['meta_description'] = $meta_info['meta_description'];
					}
			
					if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
						$data[$key]['meta_keyword'] = $meta_info['meta_keyword'];
					}
				}
			}
		}
		
		return $data;
	}
	
	public function category_get_category_parents($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => 'bm_category_id=%',
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			foreach ($data as $key => $category) {
				$route = 'bm_category_id=' . (int)$category['category_id'];
							
				if (isset($meta_data[$route][$store_id][$language_id])) {
					$meta_info = $meta_data[$route][$store_id][$language_id];
			
					if (isset($meta_info['title']) && $meta_info['title']) {
						$data[$key]['title'] = $meta_info['title'];
					}
					
					if (isset($meta_info['short_description']) && $meta_info['short_description']) {
						$data[$key]['short_description'] = $meta_info['short_description'];
					}
			
					if (isset($meta_info['description']) && $meta_info['description']) {
						$data[$key]['description'] = $meta_info['description'];
					}
			
					if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
						$data[$key]['meta_title'] = $meta_info['meta_title'];
					}
			
					if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
						$data[$key]['meta_description'] = $meta_info['meta_description'];
					}
			
					if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
						$data[$key]['meta_keyword'] = $meta_info['meta_keyword'];
					}
				}
			}
		}
		
		return $data;
	}
	
	public function category_get_category_by_post_id($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => 'bm_category_id=%',
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			foreach ($data as $key => $category) {
				$route = 'bm_category_id=' . (int)$category['category_id'];
							
				if (isset($meta_data[$route][$store_id][$language_id])) {
					$meta_info = $meta_data[$route][$store_id][$language_id];
			
					if (isset($meta_info['title']) && $meta_info['title']) {
						$data[$key]['title'] = $meta_info['title'];
					}
					
					if (isset($meta_info['short_description']) && $meta_info['short_description']) {
						$data[$key]['short_description'] = $meta_info['short_description'];
					}
			
					if (isset($meta_info['description']) && $meta_info['description']) {
						$data[$key]['description'] = $meta_info['description'];
					}
			
					if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
						$data[$key]['meta_title'] = $meta_info['meta_title'];
					}
			
					if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
						$data[$key]['meta_description'] = $meta_info['meta_description'];
					}
			
					if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
						$data[$key]['meta_keyword'] = $meta_info['meta_keyword'];
					}
				}
			}
		}
		
		return $data;
	}
	
	public function post_after($html) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
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
		
		if ($status && file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {
			if (isset($this->request->get['post_id'])) {
				$post_id = (int)$this->request->get['post_id'];
			} else {
				$post_id = 0;
			}
		
			$route = 'bm_post_id=' . (int)$post_id;
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
		
				$html_dom = new d_simple_html_dom();
				$html_dom->load((string)$html, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
			
				if (isset($meta_info['meta_robots']) && $meta_info['meta_robots']) {
					foreach ($html_dom->find('head') as $element) {
						$element->innertext .= '<meta name="robots" content="' . $meta_info['meta_robots'] . '" />' . "\n";
					}
				}
			
				if (isset($meta_info['custom_title_1']) && $meta_info['custom_title_1']) {
					foreach ($html_dom->find($setting['sheet']['blog_post']['custom_title_1_class']) as $element) {
						$element->innertext = $meta_info['custom_title_1'];
					}
				}
			
				if (isset($meta_info['custom_title_2']) && $meta_info['custom_title_2']) {
					foreach ($html_dom->find($setting['sheet']['blog_post']['custom_title_2_class']) as $element) {
						$element->innertext = $meta_info['custom_title_2'];
					}
				}
			
				if ((isset($meta_info['custom_image_title']) && $meta_info['custom_image_title']) || (isset($meta_info['custom_image_alt']) && $meta_info['custom_image_alt'])) {
					foreach ($html_dom->find($setting['sheet']['blog_post']['custom_image_class']) as $element) {
						if (isset($meta_info['custom_image_title']) && $meta_info['custom_image_title']) {
							$element->setAttribute('title', $meta_info['custom_image_title']);
						}
					
						if (isset($meta_info['custom_image_alt']) && $meta_info['custom_image_alt']) {
							$element->setAttribute('alt', $meta_info['custom_image_alt']);
						}
					}
				}
	
				return (string)$html_dom;
			}
		}
		
		return $html;
	}
	
	public function post_get_post($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {			
			$route = 'bm_post_id=' . (int)$data['post_id'];
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
			
				if (isset($meta_info['title']) && $meta_info['title']) {
					$data['title'] = $meta_info['title'];
				}
				
				if (isset($meta_info['short_description']) && $meta_info['short_description']) {
					$data['short_description'] = $meta_info['short_description'];
				}
			
				if (isset($meta_info['description']) && $meta_info['description']) {
					$data['description'] = $meta_info['description'];
				}
			
				if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
					$data['meta_title'] = $meta_info['meta_title'];
				}
			
				if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
					$data['meta_description'] = $meta_info['meta_description'];
				}
			
				if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
					$data['meta_keyword'] = $meta_info['meta_keyword'];
				}
				
				if (isset($meta_info['tag']) && $meta_info['tag']) {
					$data['tag'] = $meta_info['tag'];
				}
			}
		}
		
		return $data;
	}
	
	public function post_get_posts_by_category_id($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => 'bm_post_id=%',
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			foreach ($data as $key => $post) {
				$route = 'bm_post_id=' . (int)$post['post_id'];
							
				if (isset($meta_data[$route][$store_id][$language_id])) {
					$meta_info = $meta_data[$route][$store_id][$language_id];
			
					if (isset($meta_info['title']) && $meta_info['title']) {
						$data[$key]['title'] = $meta_info['title'];
					}
					
					if (isset($meta_info['short_description']) && $meta_info['short_description']) {
						$data[$key]['short_description'] = $meta_info['short_description'];
					}
			
					if (isset($meta_info['description']) && $meta_info['description']) {
						$data[$key]['description'] = $meta_info['description'];
					}
			
					if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
						$data[$key]['meta_title'] = $meta_info['meta_title'];
					}
			
					if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
						$data[$key]['meta_description'] = $meta_info['meta_description'];
					}
			
					if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
						$data[$key]['meta_keyword'] = $meta_info['meta_keyword'];
					}
				}
			}
		}
		
		return $data;
	}
	
	public function post_get_prev_post($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		// Setting
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		$setting = ($this->config->get('module_' . $this->codename . '_setting')) ? $this->config->get('module_' . $this->codename . '_setting') : array();
		
		if ($status && $data && $store_id) {			
			$route = 'bm_post_id=' . (int)$data['post_id'];
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
			
				if (isset($meta_info['title']) && $meta_info['title']) {
					$data['title'] = $meta_info['title'];
				}
				
				if (isset($meta_info['short_description']) && $meta_info['short_description']) {
					$data['short_description'] = $meta_info['short_description'];
				}
			
				if (isset($meta_info['description']) && $meta_info['description']) {
					$data['description'] = $meta_info['description'];
				}
			
				if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
					$data['meta_title'] = $meta_info['meta_title'];
				}
			
				if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
					$data['meta_description'] = $meta_info['meta_description'];
				}
			
				if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
					$data['meta_keyword'] = $meta_info['meta_keyword'];
				}
				
				if (isset($meta_info['tag']) && $meta_info['tag']) {
					$data['tag'] = $meta_info['tag'];
				}
			}
		}
		
		return $data;
	}
	
	public function post_get_next_post($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status && $data && $store_id) {			
			$route = 'bm_post_id=' . (int)$data['post_id'];
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
			
				if (isset($meta_info['title']) && $meta_info['title']) {
					$data['title'] = $meta_info['title'];
				}
				
				if (isset($meta_info['short_description']) && $meta_info['short_description']) {
					$data['short_description'] = $meta_info['short_description'];
				}
			
				if (isset($meta_info['description']) && $meta_info['description']) {
					$data['description'] = $meta_info['description'];
				}
			
				if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
					$data['meta_title'] = $meta_info['meta_title'];
				}
			
				if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
					$data['meta_description'] = $meta_info['meta_description'];
				}
			
				if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
					$data['meta_keyword'] = $meta_info['meta_keyword'];
				}
				
				if (isset($meta_info['tag']) && $meta_info['tag']) {
					$data['tag'] = $meta_info['tag'];
				}
			}
		}
		
		return $data;
	}
			
	public function author_after($html) {		
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
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
		
		if ($status && file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {
			if (isset($this->request->get['user_id'])) {
				$user_id = (int)$this->request->get['user_id'];
			} else {
				$user_id = 0;
			}
			
			$this->load->model('extension/module/' . $this->codename);
			
			$author = $this->{'model_extension_module_' . $this->codename}->getAuthorByUser($user_id);
			
			if (isset($author['author_id'])) {			
				$route = 'bm_author_id=' . (int)$author['author_id'];
			
				$field_data = array(
					'field_code' => 'meta_data',
					'filter' => array(
						'route' => $route,
						'store_id' => $store_id,
						'language_id' => $language_id
					)
				);
			
				$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
																
				if ($meta_data) {					
					$meta_info = $meta_data[$route][$store_id][$language_id];
										
					$html_dom = new d_simple_html_dom();
					$html_dom->load((string)$html, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
					
					if ($setting['sheet']['blog_author']['meta_title_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
						$meta_title_page = '';
					
						if (isset($setting['meta_title_page_template'][$language_id])) {
							$meta_title_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_title_page_template'][$language_id]);
						} elseif ($setting['meta_title_page_template_default']) {
							$meta_title_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_title_page_template_default']);
						}

						if ($meta_title_page) {
							if ($html_dom->find('title')) {
								foreach ($html_dom->find('title') as $element) {
									$element->innertext = $meta_title_page . ' ' . $meta_info['meta_title'];
								}
							} else {
								foreach ($html_dom->find('head') as $element) {
									$element->innertext .= '<title>' . $meta_title_page . ' ' . $meta_info['meta_title'] . '</title>' . "\n";
								}
							}
						}
					}
														
					if ($setting['sheet']['blog_author']['meta_description_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
						$meta_description_page = '';
					
						if (isset($setting['meta_description_page_template'][$language_id])) {
							$meta_description_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_description_page_template'][$language_id]);
						} elseif ($setting['meta_title_page_template_default']) {
							$meta_description_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_description_page_template_default']);
						}
					
						if ($meta_description_page) {
							if ($html_dom->find('meta[name="description"]')) {
								foreach ($html_dom->find('meta[name="description"]') as $element) {
									$element->setAttribute('content', $meta_description_page . ' ' . $meta_info['meta_description']);
								}
							} else {
								foreach ($html_dom->find('head') as $element) {
									$element->innertext .= '<meta name="description" content="' . $meta_description_page . ' ' . $meta_info['meta_description'] . '" />' . "\n";
								}
							}
						}
					}
					
					if (isset($meta_info['meta_robots']) && $meta_info['meta_robots']) {
						foreach ($html_dom->find('head') as $element) {
							$element->innertext .= '<meta name="robots" content="' . $meta_info['meta_robots'] . '" />' . "\n";
						}
					}
			
					if (isset($meta_info['custom_title_1']) && $meta_info['custom_title_1']) {
						foreach ($html_dom->find($setting['sheet']['blog_author']['custom_title_1_class']) as $element) {
							$element->innertext = $meta_info['custom_title_1'];
						}
					}
			
					if (isset($meta_info['custom_title_2']) && $meta_info['custom_title_2']) {
						foreach ($html_dom->find($setting['sheet']['blog_author']['custom_title_2_class']) as $element) {
							$element->innertext = $meta_info['custom_title_2'];
						}
					}
					
					if ((isset($meta_info['custom_image_title']) && $meta_info['custom_image_title']) || (isset($meta_info['custom_image_alt']) && $meta_info['custom_image_alt'])) {
						foreach ($html_dom->find($setting['sheet']['blog_author']['custom_image_class']) as $element) {
							if (isset($meta_info['custom_image_title']) && $meta_info['custom_image_title']) {
								$element->setAttribute('title', $meta_info['custom_image_title']);
							}
						
							if (isset($meta_info['custom_image_alt']) && $meta_info['custom_image_alt']) {
								$element->setAttribute('alt', $meta_info['custom_image_alt']);
							}
						}
					}
					
					$url = '';
			
					if ($setting['sheet']['blog_author']['canonical_link_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
						$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
					}
					
					$canonical_link = $this->url->link('extension/d_blog_module/author', 'user_id=' . $user_id . $url, true);
								
					if ($html_dom->find('link[rel="canonical"]')) {
						foreach ($html_dom->find('link[rel="canonical"]') as $element) {
							$element->setAttribute('href', $canonical_link);
						}
					} else {
						foreach ($html_dom->find('head') as $element) {
							$element->innertext .= '<link href="' . $canonical_link . '" rel="canonical" />' . "\n";
						}
					}
								
					return (string)$html_dom;
				}
			}
		}
		
		return $html;
	}
	
	public function author_get_author($data) {
		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
				
		if ($status && $data && $store_id) {			
			$route = 'bm_author_id=' . (int)$data['author_id'];
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array(
					'route' => $route,
					'store_id' => $store_id,
					'language_id' => $language_id
				)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			
			if ($meta_data) {
				$meta_info = $meta_data[$route][$store_id][$language_id];
			
				if (isset($meta_info['name']) && $meta_info['name']) {
					$data['name'] = $meta_info['name'];
				}
				
				if (isset($meta_info['short_description']) && $meta_info['short_description']) {
					$data['short_description'] = $meta_info['short_description'];
				}
			
				if (isset($meta_info['description']) && $meta_info['description']) {
					$data['description'] = $meta_info['description'];
				}
			
				if (isset($meta_info['meta_title']) && $meta_info['meta_title']) {
					$data['meta_title'] = $meta_info['meta_title'];
				}
			
				if (isset($meta_info['meta_description']) && $meta_info['meta_description']) {
					$data['meta_description'] = $meta_info['meta_description'];
				}
			
				if (isset($meta_info['meta_keyword']) && $meta_info['meta_keyword']) {
					$data['meta_keyword'] = $meta_info['meta_keyword'];
				}
			}
		}
		
		return $data;
	}
			
	public function search_after($html) {
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
				
		if ($status && file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {			
			$html_dom = new d_simple_html_dom();
			$html_dom->load((string)$html, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
			
			$meta_title_page = '';
							
			if ($setting['sheet']['blog_search']['meta_title_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
				if (isset($setting['meta_title_page_template'][$language_id])) {
					$meta_title_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_title_page_template'][$language_id]);
				} elseif ($setting['meta_title_page_template_default']) {
					$meta_title_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_title_page_template_default']);
				}
			}
			
			if ($meta_title_page) {
				if ($html_dom->find('title')) {
					foreach ($html_dom->find('title') as $element) {
						$element->innertext = $meta_title_page . ' ' . $element->innertext;
					}
				}
			}
			
			$meta_description_page = '';
			
			if ($setting['sheet']['blog_search']['meta_description_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
				if (isset($setting['meta_description_page_template'][$language_id])) {
					$meta_description_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_description_page_template'][$language_id]);
				} elseif ($setting['meta_title_page_template_default']) {
					$meta_description_page = str_replace('[page_number]', $this->request->get['page'], $setting['meta_description_page_template_default']);
				}
			}
			
			if ($meta_description_page) {
				if ($html_dom->find('meta[name="description"]')) {
					foreach ($html_dom->find('meta[name="description"]') as $element) {
						$element->setAttribute('content', $meta_description_page . ' ' . $element->getAttribute('content'));
					}
				} 
			}
			
			$url = '';

			if ($setting['sheet']['blog_search']['canonical_link_tag'] && isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}
						
			if ($setting['sheet']['blog_search']['canonical_link_page'] && isset($this->request->get['page']) && ($this->request->get['page'] > 1)) {
				$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
			}
			
			$canonical_link = $this->url->link('extension/d_blog_module/search', $url, true);
					
			if ($html_dom->find('link[rel="canonical"]')) {
				foreach ($html_dom->find('link[rel="canonical"]') as $element) {
					$element->setAttribute('href', $canonical_link);
				}
			} else {
				foreach ($html_dom->find('head') as $element) {
					$element->innertext .= '<link href="' . $canonical_link . '" rel="canonical" />' . "\n";
				}
			}
			
			return (string)$html_dom;
		}
		
		return $html;
	}
}