<?php
class ModelExtensionDSEOModuleDVisualDesignerLanding extends Model
{
    private $codename = 'd_visual_designer_landing';
    
    /*
    *	Add Language.
    */
    public function addLanguage($data)
    {
        if (VERSION >= '3.0.0.0') {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_url_keyword WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
        }
                    
        foreach ($query->rows as $result) {
            if (VERSION >= '3.0.0.0') {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = '" . $this->db->escape($result['query']) . "', store_id = '" . (int)$result['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($result['keyword']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = '" . $this->db->escape($result['route']) . "', store_id = '" . (int)$result['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($result['keyword']) . "'");
            }
        }
        
        $this->db->query("ALTER TABLE " . DB_PREFIX . "d_url_redirect ADD (url_to_" . (int)$data['language_id'] . " VARCHAR(512) NOT NULL)");
        
        $this->db->query("UPDATE " . DB_PREFIX . "d_url_redirect SET url_to_" . (int)$data['language_id'] . " = url_to_" . (int)$this->config->get('config_language_id'));
    }
    
    /*
    *	Delete Language.
    */
    public function deleteLanguage($data)
    {
        if (VERSION >= '3.0.0.0') {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE language_id = '" . (int)$data['language_id'] . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE language_id = '" . (int)$data['language_id'] . "'");
        }
        
        $this->db->query("ALTER TABLE " . DB_PREFIX . "d_url_redirect DROP url_to_" . (int)$data['language_id']);

        $this->cache->delete('url_rewrite');
    }

    
    
    /*
    *	Return Field Elements.
    */
    public function getFieldElements($data)
    {
        if ($data['field_code'] == 'url_keyword') {
            $this->load->model('extension/module/' . $this->codename);
        
            $stores = $this->{'model_extension_module_' . $this->codename}->getStores();
        
            $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
            $custom_page_exception_routes = $this->load->controller('extension/module/d_seo_module_url/getCustomPageExceptionRoutes');
            
            $field_elements = array();
            
            if (VERSION >= '3.0.0.0') {
                $sql = "SELECT * FROM " . DB_PREFIX . "seo_url";
            } else {
                $sql = "SELECT * FROM " . DB_PREFIX . "d_url_keyword";
            }
            
            $implode = array();
                
            foreach ($data['filter'] as $filter_code => $filter) {
                if (!empty($filter)) {
                    if ($filter_code == 'route') {
                        if (strpos($filter, '%') !== false) {
                            if (VERSION >= '3.0.0.0') {
                                $implode[] = "query LIKE '" . $this->db->escape($filter) . "'";
                            } else {
                                $implode[] = "route LIKE '" . $this->db->escape($filter) . "'";
                            }
                        } else {
                            if (VERSION >= '3.0.0.0') {
                                $implode[] = "query = '" . $this->db->escape($filter) . "'";
                            } else {
                                $implode[] = "route = '" . $this->db->escape($filter) . "'";
                            }
                        }
                    }
                                                        
                    if ($filter_code == 'language_id') {
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
                if (VERSION >= '3.0.0.0') {
                    $result['route'] = $result['query'];
                }
                
                if (strpos($result['route'], 'vdl_page_id') !== false) {
                    if (isset($field_info['sheet']['landing_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['url_keyword']['multi_store_status']) {
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
                    
                if (preg_match('/[A-Za-z0-9]+\/[A-Za-z0-9]+/i', $result['route']) && !($custom_page_exception_routes && in_array($result['route'], $custom_page_exception_routes))) {
                    if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
                        $field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
                    }
                }
            }
                
            return $field_elements;
        }

        if ($data['field_code'] == 'target_keyword') {
            $this->load->model('extension/module/' . $this->codename);
        
            $stores = $this->{'model_extension_module_' . $this->codename}->getStores();

            $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
            $custom_page_exception_routes = $this->load->controller('extension/module/d_seo_module/getCustomPageExceptionRoutes');
            
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
                                                    
                    if ($filter_code == 'language_id') {
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
                if (strpos($result['route'], 'vdl_page_id') !== false) {
                    if (isset($field_info['sheet']['landing_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['target_keyword']['multi_store_status']) {
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
                    
                if (preg_match('/[A-Za-z0-9]+\/[A-Za-z0-9]+/i', $result['route']) && !($custom_page_exception_routes && in_array($result['route'], $custom_page_exception_routes))) {
                    if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
                        $field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
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
			
			if ((isset($data['filter']['route']) && (strpos($data['filter']['route'], 'vdl_page_id') === 0)) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "dvdl_page_description";
			
				$implode = array();
								
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$route_arr = explode('vdl_page_id=', $filter);
			
							if (isset($route_arr[1]) && ($route_arr[1] != '%')) {
								$page_id = $route_arr[1];
								$implode[] = "page_id = '" . (int)$page_id . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
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
					$route = 'vdl_page_id=' . $result['page_id'];
							
					if ((isset($field_info['sheet']['landing_page']['field']['title']['multi_store']) && $field_info['sheet']['landing_page']['field']['title']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['title']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['title']['multi_store_status'])) {
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
					
					if ((isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status'])) {
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

					if ((isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status'])) {
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

					if ((isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status'])) {
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
			
			if (isset($data['filter']['route']) && (strpos($data['filter']['route'], 'vdl_page_id') === 0)) {
				$sql = "SELECT * FROM " . DB_PREFIX . "d_meta_data";
				
				$implode = array();
								
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$implode[] = "route = '" . $this->db->escape($filter) . "'";
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
                        }
                        
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
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
				
					if (strpos($result['route'], 'vdl_page_id') === 0) {
						if ($result['store_id'] && isset($field_info['sheet']['landing_page']['field']['title']['multi_store']) && $field_info['sheet']['landing_page']['field']['title']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['title']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
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
