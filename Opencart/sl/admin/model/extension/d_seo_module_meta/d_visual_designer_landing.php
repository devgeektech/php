<?php
class ModelExtensionDSEOModuleMetaDVisualDesignerLanding extends Model
{
    private $codename = 'd_visual_designer_landing';
    
    /*
    *	Generate Fields.
    */
    public function generateFields($data)
    {
        $this->load->model('extension/module/' . $this->codename);
        
        $store = $this->{'model_extension_module_' . $this->codename}->getStore($data['store_id']);
        $languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
                                
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        $field_data = array(
            'field_code' => 'meta_data',
            'filter' => array(
                'route' => 'common/home',
                'store_id' => $data['store_id']
            )
        );
            
        $meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
        
        if (file_exists(DIR_APPLICATION . 'model/extension/module/d_translit.php')) {
            $this->load->model('extension/module/d_translit');
            
            $translit_data = true;
        } else {
            $translit_data = false;
        }
        if (isset($data['sheet']['landing_page']['field'])) {
            $field = array();
            $implode = array();

            if (isset($data['sheet']['landing_page']['field']['meta_title']) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status'])) {
                $field = $data['sheet']['landing_page']['field']['meta_title'];
                $implode[] = ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
            }
            
            if (isset($data['sheet']['landing_page']['field']['meta_description']) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status'])) {
                $field = $data['sheet']['landing_page']['field']['meta_description'];
                $implode[] = ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
            }
            
            if (isset($data['sheet']['landing_page']['field']['meta_keyword']) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status'])) {
                $field = $data['sheet']['landing_page']['field']['meta_keyword'];
                $implode[] = ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
            }
            
            if (isset($field_info['sheet']['landing_page']['field']['title']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['title']['multi_store_status'])) {
                $implode[] = ($data['store_id'] && $field_info['sheet']['landing_page']['field']['title']['multi_store'] && $field_info['sheet']['landing_page']['field']['title']['multi_store_status']) ? "md2.title" : "pd.title";
            }
            
            $pages = array();
            
            if ($field) {
                $field_template = isset($field['template']) ? $field['template'] : '';
                $field_overwrite = isset($field['overwrite']) ? $field['overwrite'] : 1;
                
                if ($translit_data) {
                    $translit_data = array(
                        'translit_symbol_status' => isset($field['translit_symbol_status']) ? $field['translit_symbol_status'] : 1,
                        'translit_language_symbol_status' => isset($field['translit_language_symbol_status']) ? $field['translit_language_symbol_status'] : 1,
                        'transform_language_symbol_id' => isset($field['transform_language_symbol_id']) ? $field['transform_language_symbol_id'] : 0
                    );
                }
                
                $field_data = array(
                    'field_code' => 'target_keyword',
                    'filter' => array('store_id' => $data['store_id'])
                );
                
                $target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
                
                $query = $this->db->query("SELECT pd.page_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "dvdl_page_description pd ON (pd.page_id = p.page_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('vdl_page_id=', p.page_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('vdl_page_id=', p.page_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id) GROUP BY p.page_id, pd.language_id");

                foreach ($query->rows as $result) {
                    $pages[$result['page_id']]['page_id'] = $result['page_id'];
                    foreach ($result as $field => $value) {
                        if (($field != 'page_id') && ($field != 'language_id')) {
                            $pages[$result['page_id']][$field][$result['language_id']] = $value;
                        }
                    }
                }
            }
            
            foreach ($pages as $page) {
                foreach ($languages as $language) {
                    if (isset($target_keywords['vdl_page_id=' . $page['page_id']][$data['store_id']][$language['language_id']])) {
                        $target_keyword = $target_keywords['vdl_page_id=' . $page['page_id']][$data['store_id']][$language['language_id']];
                    } else {
                        $target_keyword = array();
                    }
                    
                    if (isset($store['title'][$language['language_id']])) {
                        $store_title = $store['title'][$language['language_id']];
                    } else {
                        $store_title = '';
                    }
                    
                    if (is_array($field_template)) {
                        $field_new = $field_template[$language['language_id']];
                    } else {
                        $field_new = $field_template;
                    }
                                        
                    $field_new = strtr($field_new, array(
                        '[title]' => $page['title'][$language['language_id']],
                        '[store_name]' => $store['name'],
                        '[store_title]' => $store_title
                    ));
                    $field_new = $this->replaceTargetKeyword($field_new, $target_keyword);
                    
                    if ($translit_data) {
                        $field_new = $this->model_extension_module_d_translit->translit($field_new, $translit_data);
                    }

                    if (isset($data['sheet']['landing_page']['field']['meta_title']) && isset($page['meta_title'][$language['language_id']]) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status'])) {
                        if (($field_new != $page['meta_title'][$language['language_id']]) && ($field_overwrite || !$page['meta_title'][$language['language_id']])) {
                            if ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) {
                                $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '" . $this->db->escape($field_new) . "' WHERE route='vdl_page_id=" . (int)$page['page_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                                if (!$this->db->countAffected()) {
                                    $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='vdl_page_id=" . (int)$page['page_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_title = '" . $this->db->escape($field_new) . "'");
                                }
                            } else {
                                $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET meta_title = '" . $this->db->escape($field_new) . "' WHERE page_id = '" . (int)$page['page_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                            }
                        }
                    }
                    
                    if (isset($data['sheet']['landing_page']['field']['meta_description']) && isset($page['meta_description'][$language['language_id']]) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status'])) {
                        if (($field_new != $page['meta_description'][$language['language_id']]) && ($field_overwrite || !$page['meta_description'][$language['language_id']])) {
                            if ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status']) {
                                $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '" . $this->db->escape($field_new) . "' WHERE route='vdl_page_id=" . (int)$page['page_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                                
                                if (!$this->db->countAffected()) {
                                    $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='page_id=" . (int)$page['page_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_description = '" . $this->db->escape($field_new) . "'");
                                }
                            } else {
                                $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET meta_description = '" . $this->db->escape($field_new) . "' WHERE page_id = '" . (int)$page['page_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                            }
                        }
                    }
                    
                    if (isset($data['sheet']['landing_page']['field']['meta_keyword']) && isset($page['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status'])) {
                        if (($field_new != $page['meta_keyword'][$language['language_id']]) && ($field_overwrite || !$page['meta_keyword'][$language['language_id']])) {
                            if ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status']) {
                                $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE route='vdl_page_id=" . (int)$page['page_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                                
                                if (!$this->db->countAffected()) {
                                    $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='vdl_page_id=" . (int)$page['page_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_keyword = '" . $this->db->escape($field_new) . "'");
                                }
                            } else {
                                $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE page_id = '" . (int)$page['page_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                            }
                        }
                    }
                }
            }
        }
    }
    
    /*
    *	Clear Fields.
    */
    public function clearFields($data)
    {
        $this->load->model('extension/module/' . $this->codename);
        
        $store = $this->{'model_extension_module_' . $this->codename}->getStore($data['store_id']);
        $languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
                                
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        if (isset($data['sheet']['landing_page']['field'])) {
            $field = array();
            $implode = array();
            
            if (isset($data['sheet']['landing_page']['field']['meta_title']) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status'])) {
                $field = $data['sheet']['landing_page']['field']['meta_title'];
                $implode[] = ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
            }
            
            if (isset($data['sheet']['landing_page']['field']['meta_description']) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status'])) {
                $field = $data['sheet']['landing_page']['field']['meta_description'];
                $implode[] = ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
            }
            
            if (isset($data['sheet']['landing_page']['field']['meta_keyword']) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status'])) {
                $field = $data['sheet']['landing_page']['field']['meta_keyword'];
                $implode[] = ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
            }

            $pages = array();
            
            $query = $this->db->query("SELECT pd.page_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "dvdl_page_description pd ON (pd.page_id = p.page_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('vdl_page_id=', p.page_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('vdl_page_id=', p.page_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id) GROUP BY p.page_id, pd.language_id");
                
            foreach ($query->rows as $result) {
                $pages[$result['page_id']]['page_id'] = $result['page_id'];
                
                foreach ($result as $field => $value) {
                    if (($field != 'page_id') && ($field != 'language_id')) {
                        $pages[$result['page_id']][$field][$result['language_id']] = $value;
                    }
                }
            }
            
            foreach ($pages as $page) {
                foreach ($languages as $language) {

                    if (isset($data['sheet']['landing_page']['field']['meta_title']) && isset($page['meta_title'][$language['language_id']]) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status'])) {
                        if ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) {
                            $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '' WHERE route='vdl_page_id=" . (int)$page['page_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                        } else {
                            $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET meta_title = '' WHERE page_id = '" . (int)$page['page_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                        }
                    }
                                        
                    if (isset($data['sheet']['landing_page']['field']['meta_description']) && isset($page['meta_description'][$language['language_id']]) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status'])) {
                        if ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_description']['multi_store_status']) {
                            $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '' WHERE route='vdl_page_id=" . (int)$page['page_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                        } else {
                            $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET meta_description = '' WHERE page_id = '" . (int)$page['page_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                        }
                    }
                    
                    if (isset($data['sheet']['landing_page']['field']['meta_keyword']) && isset($page['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status'])) {
                        if ($data['store_id'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['landing_page']['field']['meta_keyword']['multi_store_status']) {
                            $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '' WHERE route='vdl_page_id=" . (int)$page['page_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                        } else {
                            $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET meta_keyword = '' WHERE page_id = '" . (int)$page['page_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                        }
                    }
                }
            }
        }
    }
    
    /*
    *	Replace Target Keyword.
    */
    private function replaceTargetKeyword($field_template, $target_keyword)
    {
        $field_template = preg_replace_callback('/\[target_keyword[^]]+\]/', function ($matches) use ($target_keyword) {
            $replacement_target_keyword = '';
            
            if (preg_match('/#number=[0-9]+/', $matches[0], $matches_number)) {
                $explode = explode('=', $matches_number[0]);
                $number = $explode[1];
                
                if (isset($target_keyword[$number])) {
                    $replacement_target_keyword = $target_keyword[$number];
                }
            }
            
            return $replacement_target_keyword;
        }, $field_template);
        
        return $field_template;
    }
}
