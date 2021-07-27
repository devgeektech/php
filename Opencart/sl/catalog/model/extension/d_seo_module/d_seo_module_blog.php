<?php
class ModelExtensionDSEOModuleDSEOModuleBlog extends Model {	
	private $codename = 'd_seo_module_blog';
	
	/*
	*	Return URL for Language.
	*/	
	public function getURLForLanguage($url, $language_code) {		
		$url_info = $this->getURLInfo($url);
		
		$store_id = (int)$this->config->get('config_store_id');
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $language_code . "'");

		$language_id = $query->row['language_id'];
		
		// Setting
		$setting = ($this->config->get('module_' . $this->codename . '_setting')) ? $this->config->get('module_' . $this->codename . '_setting') : array();
		
		if (isset($url_info['data']['_route_'])) {
			$parts = explode('/', $url_info['data']['_route_']);

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
						}
							
						foreach ($store_url_keywords[$store_id] as $url_language_id => $keyword) {
							if ($url_language_id == (int)$this->config->get('config_language_id')) {
								$route = $url_route;
							}
						}
					}
				}
				
				if (isset($route)) {
					$route = explode('=', $route);

					if ($route[0] == 'bm_post_id') {
						$url_info['data']['post_id'] = $route[1];
					}

					if ($route[0] == 'bm_category_id') {
						$url_info['data']['category_id'] = $route[1];
					}

					if ($route[0] == 'bm_author_id') {
						$this->load->model('extension/module/' . $this->codename);
						
						$author = $this->{'model_extension_module_' . $this->codename}->getAuthor($route[1]);
							
						if (isset($author['user_id'])) {
							$url_info['data']['user_id'] = $author['user_id'];
						}
					}
					
					if (preg_match('/[A-Za-z0-9]+\/[A-Za-z0-9]+/i', $route[0])) {
						$url_info['data']['route'] = $route[0];
					}
				} else {
					break;
				}
			}
		}
		
		$params = array();
			
		if (isset($url_info['data']['post_id'])) {
			$url_info['data']['route'] = 'extension/d_blog_module/post';
			if (isset($url_info['data']['category_id'])) $params[] = 'category_id=' . $url_info['data']['category_id'];
			$params[] = 'post_id=' . $url_info['data']['post_id'];
		} elseif (isset($url_info['data']['category_id'])) {
			$url_info['data']['route'] = 'extension/d_blog_module/category';
			$params[] = 'category_id=' . $url_info['data']['category_id'];
		} elseif (isset($url_info['data']['user_id'])) {
			$url_info['data']['route'] = 'extension/d_blog_module/author';
			$params[] = 'user_id=' . $url_info['data']['user_id'];
		}
		
		if (isset($url_info['data']['route'])) {
			foreach($url_info['data'] as $param => $value) {
				if ($param != '_route_' && $param != 'route' && $param != 'category_id' && $param != 'post_id' && $param != 'user_id') {
					$params[] = $param . '=' . $value;
				}
			}
			
			$config_language_id = $this->config->get('config_language_id');
			$this->config->set('config_language_id', $language_id);	
			$url = $this->url->link($url_info['data']['route'], implode('&', $params), true);
			$this->config->set('config_language_id', $config_language_id);
		}

		return $url;
	}
		
	/*
	*	Return Category Path.
	*/		
	public function getCategoryPath($category_id) {
		$path = false;
				
		$query = $this->db->query("SELECT GROUP_CONCAT(c.category_id ORDER BY level SEPARATOR '_') as category_path FROM " . DB_PREFIX . "bm_category_path cp LEFT JOIN " . DB_PREFIX . "bm_category c ON (cp.path_id = c.category_id) WHERE cp.category_id = '" . (int)$category_id . "' GROUP BY cp.category_id");
		
		if ($query->num_rows) {
			$path = $query->row['category_path'];
		}
				
		return $path;
	}

	/*
	*	Return Post Path.
	*/		
	public function getPostPath($post_id) {		
		$path = false;
		
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "d_post_category WHERE post_id = '" . (int)$post_id . "'");
		
		if ($query->num_rows) {
			if ($query->row['category_id']) {
				$path = $this->getCategoryPath($query->row['category_id']);
			}
		}
				
		return $path;
	}
		
	/*
	*	Return Current URL.
	*/	
	public function getCurrentURL() {
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$url = "https://";
		} else {
			$url = 'http://';
		}
		
		$url .= $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			
		$url = str_replace('&', '&amp;', str_replace('&amp;', '&', $url));
		
		return $url;
	}
	
	/*
	*	Return URL Info.
	*/	
	public function getURLInfo($url) {						
		$url_info = parse_url(str_replace('&amp;', '&', $url));
		
		$url_info['scheme'] = isset($url_info['scheme']) ? $url_info['scheme'] . '://' : '';
		$url_info['user'] = isset($url_info['user']) ? $url_info['user'] : '';
		$url_info['pass'] = isset($url_info['pass']) ? ':' . $url_info['pass']  : '';
		$url_info['pass'] = ($url_info['user'] || $url_info['pass']) ? $url_info['pass'] . '@' : ''; 
		$url_info['host'] = isset($url_info['host']) ? $url_info['host'] : '';
		$url_info['port'] = isset($url_info['port']) ? ':' . $url_info['port'] : '';
		$url_info['path'] = isset($url_info['path']) ? $url_info['path'] : '';		
		
		$url_info['data'] = array();
		
		if (isset($url_info['query'])) {
			parse_str($url_info['query'], $url_info['data']);
		}
		
		$url_info['query'] = isset($url_info['query']) ? '?' . $url_info['query'] : '';
		$url_info['fragment'] = isset($url_info['fragment']) ? '#' . $url_info['fragment'] : '';
						
		return $url_info;
	}
			
	/*
	*	Return Field Elements.
	*/
	public function getFieldElements($data) {				
		if ($data['field_code'] == 'target_keyword') {
			$this->load->model('extension/module/' . $this->codename);
		
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
			$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
			
			$field_elements = array();
					
			$sql = "SELECT * FROM " . DB_PREFIX . "d_target_keyword";
			
			$implode = array();
				
			foreach ($data['filter'] as $filter_code => $filter) {
				if (!empty($filter)) {
					if ($filter_code == 'route') {
						if (strpos($filter, '%') !== false) {
							$implode[] = "route LIKE '" . $this->db->escape($filter) . "'";
						} else {
							$implode[] = "route = '" . $this->db->escape($filter) . "'";
						}
					}
													
					if ($filter_code == 'language_id' ) {
						$implode[] = "language_id = '" . (int)$filter . "'";
					}
						
					if ($filter_code == 'sort_order') {
						$implode[] = "sort_order = '" . (int)$filter . "'";
					}
						
					if ($filter_code == 'keyword') {
						$implode[] = "keyword = '" . $this->db->escape($filter) . "'";
					}
				}
			}
		
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}
		
			$sql .= " ORDER BY sort_order";
				
			$query = $this->db->query($sql);
										
			foreach ($query->rows as $result) {
				if (strpos($result['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
					
				if (strpos($result['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
										
				if (strpos($result['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
				
				if ($result['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
			}
				
			return $field_elements;
		}
		
		if ($data['field_code'] == 'url_keyword') {
			$this->load->model('extension/module/' . $this->codename);
		
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
			$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
			
			$field_elements = array();
			
			if (VERSION >= '3.0.0.0') {
				$sql = "SELECT query as route, store_id, language_id, keyword FROM " . DB_PREFIX . "seo_url";
			} else {
				$sql = "SELECT route, store_id, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword";
			}
			
			$implode = array();
				
			foreach ($data['filter'] as $filter_code => $filter) {
				if (!empty($filter)) {
					if ($filter_code == 'route') {
						if (strpos($filter, '%') !== false) {
							if (VERSION >= '3.0.0.0') {
								$implode[] = "query LIKE '" . $this->db->escape($filter) . "'";
							}else {
								$implode[] = "route LIKE '" . $this->db->escape($filter) . "'";
							}
						} else {
							if (VERSION >= '3.0.0.0') {
								$implode[] = "query = '" . $this->db->escape($filter) . "'";
							}else {
								$implode[] = "route = '" . $this->db->escape($filter) . "'";
							}
						}
					}
														
					if ($filter_code == 'language_id' ) {
						$implode[] = "language_id = '" . (int)$filter . "'";
					}
												
					if ($filter_code == 'keyword') {
						$implode[] = "keyword = '" . $this->db->escape($filter) . "'";
					}
				}
			}
		
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}
							
			$query = $this->db->query($sql);
									
			foreach ($query->rows as $result) {				
				if (strpos($result['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
					
				if (strpos($result['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
									
				if (strpos($result['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
				
				if ($result['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
			}
				
			return $field_elements;
		}
		
		if ($data['field_code'] == 'meta_data') {
			$this->load->model('extension/module/' . $this->codename);
		
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
			
			$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
						
			$field_elements = array();
			
			if ((isset($data['filter']['route']) && (strpos($data['filter']['route'], 'bm_category_id') === 0)) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "bm_category_description";
			
				$implode = array();
				
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$route_arr = explode('bm_category_id=', $filter);
			
							if (isset($route_arr[1]) && ($route_arr[1] != '%')) {
								$category_id = $route_arr[1];
								$implode[] = "category_id = '" . (int)$category_id . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					}
				}
					
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
					
				foreach ($query->rows as $result) {
					$route = 'bm_category_id=' . $result['category_id'];
				
					if ((isset($field_info['sheet']['blog_category']['field']['title']['multi_store']) && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['title'] = $result['title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['short_description'] = $result['short_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					}
				
					if ((isset($field_info['sheet']['blog_category']['field']['description']['multi_store']) && $field_info['sheet']['blog_category']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['description'] = $result['description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_title'] = $result['meta_title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_description'] = $result['meta_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
					}					
				}
			}
			
			if ((isset($data['filter']['route']) && (strpos($data['filter']['route'], 'bm_post_id') === 0)) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "bm_post_description";
			
				$implode = array();
								
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$route_arr = explode('bm_post_id=', $filter);
			
							if (isset($route_arr[1]) && ($route_arr[1] != '%')) {
								$post_id = $route_arr[1];
								$implode[] = "post_id = '" . (int)$post_id . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'tag') {
							$implode[] = "tag = '" . $this->db->escape($filter) . "'";
						}
					}
				}
					
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
										
				foreach ($query->rows as $result) {
					$route = 'bm_post_id=' . $result['post_id'];
							
					if ((isset($field_info['sheet']['blog_post']['field']['title']['multi_store']) && $field_info['sheet']['blog_post']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['title'] = $result['title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['short_description'] = $result['short_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					}
				
					if ((isset($field_info['sheet']['blog_post']['field']['description']['multi_store']) && $field_info['sheet']['blog_post']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['description'] = $result['description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_title'] = $result['meta_title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_description'] = $result['meta_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['tag'] = $result['tag'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['tag'] = $result['tag'];
							}
						}
					}
				}
			}
			
			if ((isset($data['filter']['route']) && (strpos($data['filter']['route'], 'bm_author_id') === 0)) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "bm_author_description";
			
				$implode = array();
				
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$route_arr = explode('bm_author_id=', $filter);
			
							if (isset($route_arr[1]) && ($route_arr[1] != '%')) {
								$author_id = $route_arr[1];
								$implode[] = "author_id = '" . (int)$author_id . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'name') {
							$implode[] = "name = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					}
				}
			
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
										
				foreach ($query->rows as $result) {
					$route = 'bm_author_id=' . $result['author_id'];
							
					if ((isset($field_info['sheet']['blog_author']['field']['name']['multi_store']) && $field_info['sheet']['blog_author']['field']['name']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['name']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['name'] = $result['name'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['name'] = $result['name'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['short_description'] = $result['short_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					}
				
					if ((isset($field_info['sheet']['blog_author']['field']['description']['multi_store']) && $field_info['sheet']['blog_author']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['description'] = $result['description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_title'] = $result['meta_title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_description'] = $result['meta_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
					}
				}
			}
			
			if ((isset($data['filter']['route']) && ((strpos($data['filter']['route'], 'bm_category_id') === 0) || (strpos($data['filter']['route'], 'bm_post_id') === 0) || (strpos($data['filter']['route'], 'bm_author_id') === 0))) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "d_meta_data";
				
				$implode = array();
								
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							if (strpos($filter, '%') !== false) {
								$implode[] = "route LIKE '" . $this->db->escape($filter) . "'";
							} else {
								$implode[] = "route = '" . $this->db->escape($filter) . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'name') {
							$implode[] = "name = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'tag') {
							$implode[] = "tag = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_title_1') {
							$implode[] = "custom_title_1 = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_title_2') {
							$implode[] = "custom_title_2 = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_image_title') {
							$implode[] = "custom_image_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_image_alt') {
							$implode[] = "custom_image_alt = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_robots') {
							$implode[] = "meta_robots = '" . $this->db->escape($filter) . "'";
						}
					}
				}
					
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
			
				foreach ($query->rows as $result) {
					if (strpos($result['route'], 'bm_category_id') === 0) {
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store']) && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['description']['multi_store']) && $field_info['sheet']['blog_category']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_robots']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_robots']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_robots']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_robots']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
								}
							}
						}
					}
				
					if (strpos($result['route'], 'bm_post_id') === 0) {
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store']) && $field_info['sheet']['blog_post']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['description']['multi_store']) && $field_info['sheet']['blog_post']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['tag'] = $result['tag'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_robots']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_robots']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_robots']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_robots']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
								}
							}
						}
					}
												
					if (strpos($result['route'], 'bm_author_id') === 0) {
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store']) && $field_info['sheet']['blog_author']['field']['name']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['name']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['name'] = $result['name'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['description']['multi_store']) && $field_info['sheet']['blog_author']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
												
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
								}
							}
						}
												
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_robots']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_robots']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_robots']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_robots']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
								}
							}
						}
					}
				}
			}
			
			return $field_elements;
		}
				
		return false;
	}
}