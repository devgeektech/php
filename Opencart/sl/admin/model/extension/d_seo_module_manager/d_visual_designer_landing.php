<?php
class ModelExtensionDSEOModuleManagerDVisualDesignerLanding extends Model
{
    private $codename = 'd_visual_designer_landing';
    
    /*
    *	Return List Elements for Manager.
    */
    public function getListElements($data)
    {
        $url_token = '';
        
        if (isset($this->session->data['token'])) {
            $url_token .= 'token=' . $this->session->data['token'];
        }
        
        if (isset($this->session->data['user_token'])) {
            $url_token .= 'user_token=' . $this->session->data['user_token'];
        }
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        if ($data['sheet_code'] == 'landing_page') {
            $implode = array();
            $implode[] = "p.page_id";
            $add = '';
            
            foreach ($data['fields'] as $field) {
                if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
                    if ($field['code'] == 'title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "pd.title";
                    }
                    
                    if ($field['code'] == 'meta_title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
                    }
                
                    if ($field['code'] == 'meta_description') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
                    }
                
                    if ($field['code'] == 'meta_keyword') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
                    }
                    
                    if ($field['code'] == 'target_keyword') {
                        $implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
                        
                        if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
                            $target_keyword_store_id = $data['store_id'];
                        } else {
                            $target_keyword_store_id = 0;
                        }
                        
                        $add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('vdl_page_id=', p.page_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = CONCAT('vdl_page_id=', p.page_id) AND tk2.store_id = '" . (int)$target_keyword_store_id . "')";
                    }
                    
                    if ($field['code'] == 'url_keyword') {
                        $implode[] = "uk.keyword as url_keyword";
                        
                        if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
                            $url_keyword_store_id = $data['store_id'];
                        } else {
                            $url_keyword_store_id = 0;
                        }
                            
                        if (VERSION >= '3.0.0.0') {
                            $add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('vdl_page_id=', p.page_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('vdl_page_id=', p.page_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
                        } else {
                            $add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('vdl_page_id=', p.page_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('vdl_page_id=', p.page_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
                        }
                    }
                }
            }
            
            $sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "dvdl_page_description pd ON (pd.page_id = p.page_id AND pd.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('vdl_page_id=', p.page_id) AND md.store_id = '0' AND md.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('vdl_page_id=', p.page_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "dvdl_page_description pd2 ON (pd2.page_id = p.page_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md3 ON (md3.route = CONCAT('vdl_page_id=', p.page_id) AND md3.store_id = '0') LEFT JOIN " . DB_PREFIX . "d_meta_data md4 ON (md4.route = CONCAT('vdl_page_id=', p.page_id) AND md4.store_id = '" . (int)$data['store_id'] . "') " . $add;

            $implode = array();

            foreach ($data['filter'] as $field_code => $filter) {
                if (!empty($filter)) {
                    if ($field_code == 'page_id') {
                        $implode[] = "p.page_id = '" . (int)($filter) . "'";
                    }
                    
                    if ($field_code == 'target_keyword') {
                        $implode[] = "ut2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
                    }
                                        
                    if ($field_code == 'url_keyword') {
                        $implode[] = "uk2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
                    }
                }
                
                if (!empty($filter) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
                    if ($field_code == 'title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.title LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.title LIKE '%" . $this->db->escape($filter) . "%'";
                    }
                    
                    if ($field_code == 'meta_title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_title LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.meta_title LIKE '%" . $this->db->escape($filter) . "%'";
                    }
                    
                    if ($field_code == 'meta_description') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_description LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.meta_description LIKE '%" . $this->db->escape($filter) . "%'";
                    }
                    
                    if ($field_code == 'meta_keyword') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'";
                    }
                }
            }
            
            if ($implode) {
                $sql .= " WHERE " . implode(' AND ', $implode);
            }

            $sql .= " GROUP BY p.page_id";
            
            $query = $this->db->query($sql);
            
            $posts = array();
            
            foreach ($query->rows as $result) {
                $posts[$result['page_id']] = $result;
                $posts[$result['page_id']]['link'] = $this->url->link('extension/module/d_visual_designer_landing/edit', $url_token . '&page_id=' . $result['page_id'], true);
            }
            
            return $posts;
        }
                
        return false;
    }
    
    /*
    *	Edit Element Field for Manager.
    */
    public function editElementField($element)
    {
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        if ($element['sheet_code'] == 'landing_page') {
            if (($element['field_code'] == 'title')|| ($element['field_code'] == 'meta_title') || ($element['field_code'] == 'meta_description') || ($element['field_code'] == 'meta_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
                if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
                    $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='vdl_page_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$element['store_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
                
                    if (!$this->db->countAffected()) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='vdl_page_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
                    }
                } else {
                    $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE page_id = '" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
                }
            }
            
            if (($element['field_code'] == 'target_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
                if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
                    $target_keyword_store_id = $element['store_id'];
                } else {
                    $target_keyword_store_id = 0;
                }
                    
                $this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'vdl_page_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
                
                if ($element['value']) {
                    preg_match_all('/\[[^]]+\]/', $element['value'], $keywords);
                
                    $sort_order = 1;
                    $this->request->post['value'] = '';
                
                    foreach ($keywords[0] as $keyword) {
                        $keyword = substr($keyword, 1, strlen($keyword)-2);
                        $this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'vdl_page_id=" . (int)$element['element_id'] . "', 	store_id='" . (int)$target_keyword_store_id . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
                    
                        $sort_order++;
                        $this->request->post['value'] .= '[' . $keyword . ']';
                    }
                }
            }
                        
            if (($element['field_code'] == 'url_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
                if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
                    $url_keyword_store_id = $element['store_id'];
                } else {
                    $url_keyword_store_id = 0;
                }
                
                if (VERSION >= '3.0.0.0') {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'vdl_page_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
                    
                    if (trim($element['value'])) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'vdl_page_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
                    }
                } else {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'vdl_page_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
                    
                    if (trim($element['value'])) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'vdl_page_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
                    }
                    
                    if (($url_keyword_store_id == 0) && ($element['language_id'] == (int)$this->config->get('config_language_id'))) {
                        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'vdl_page_id=" . (int)$element['element_id'] . "'");
                        
                        if (trim($element['value'])) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'vdl_page_id=" . (int)$element['element_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
                        }
                    }
                }

                $this->cache->delete('path.blog_post');
                $this->cache->delete('url_rewrite');
            }
        }

        return true;
    }
    
    /*
    *	Return Export Elements for Manager.
    */
    public function getExportElements($data)
    {
        $this->load->model('extension/module/' . $this->codename);
        
        $languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
                
        if ($data['sheet_code'] == 'landing_page') {
            $posts = array();
            $implode = array();
            $add = '';
            
            foreach ($data['fields'] as $field) {
                if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
                    if ($field['code'] == 'title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "pd.title";
                    }
                
                    if ($field['code'] == 'meta_title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
                    }
                
                    if ($field['code'] == 'meta_description') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
                    }
                
                    if ($field['code'] == 'meta_keyword') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
                    }

                    if ($field['code'] == 'target_keyword') {
                        $implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
                        
                        if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
                            $target_keyword_store_id = $data['store_id'];
                        } else {
                            $target_keyword_store_id = 0;
                        }
                        
                        $add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('vdl_page_id=', p.page_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = pd.language_id)";
                    }
                                    
                    if ($field['code'] == 'url_keyword') {
                        $implode[] = "uk.keyword as url_keyword";
                        
                        if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
                            $url_keyword_store_id = $data['store_id'];
                        } else {
                            $url_keyword_store_id = 0;
                        }
                        
                        if (VERSION >= '3.0.0.0') {
                            $add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('vdl_page_id=', p.page_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
                        } else {
                            $add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('vdl_page_id=', p.page_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
                        }
                    }
                }
            }
            
            if ($implode) {
                $query = $this->db->query("SELECT pd.page_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "dvdl_page_description pd ON (pd.page_id = p.page_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('vdl_page_id=', p.page_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('vdl_page_id=', p.page_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id)" . $add . "GROUP BY p.page_id, pd.language_id");
        
                foreach ($query->rows as $result) {
                    $posts[$result['page_id']]['page_id'] = $result['page_id'];
                    
                    foreach ($result as $field => $value) {
                        if (($field != 'page_id') && ($field != 'language_id')) {
                            $posts[$result['page_id']][$field][$result['language_id']] = $value;
                        }
                    }
                }
            }
                        
            return $posts;
        }
        
        return false;
    }
    
    /*
    *	Save Import Elements for Manager.
    */
    public function saveImportElements($data)
    {
        $this->load->model('extension/module/' . $this->codename);
        
        $languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        if ($data['sheet_code'] == 'landing_page') {
            $posts = array();
            $implode = array();
            $add = '';
            
            foreach ($data['fields'] as $field) {
                if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
                    if ($field['code'] == 'title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "pd.title";
                    }
                
                    if ($field['code'] == 'meta_title') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
                    }
                
                    if ($field['code'] == 'meta_description') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
                    }
                
                    if ($field['code'] == 'meta_keyword') {
                        $implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
                    }
                
                    if ($field['code'] == 'target_keyword') {
                        $implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
                        
                        if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
                            $target_keyword_store_id = $data['store_id'];
                        } else {
                            $target_keyword_store_id = 0;
                        }
                        
                        $add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('vdl_page_id=', p.page_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = pd.language_id)";
                    }
                                    
                    if ($field['code'] == 'url_keyword') {
                        $implode[] = "uk.keyword as url_keyword";
                        
                        if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
                            $url_keyword_store_id = $data['store_id'];
                        } else {
                            $url_keyword_store_id = 0;
                        }
                        
                        if (VERSION >= '3.0.0.0') {
                            $add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('vdl_page_id=', p.page_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
                        } else {
                            $add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('vdl_page_id=', p.page_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
                        }
                    }
                }
            }
            
            if ($implode) {
                $query = $this->db->query("SELECT pd.page_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "dvdl_page_description pd ON (pd.page_id = p.page_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('vdl_page_id=', p.page_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('vdl_page_id=', p.page_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id) " . $add . "GROUP BY p.page_id, pd.language_id");
        
                foreach ($query->rows as $result) {
                    $posts[$result['page_id']]['page_id'] = $result['page_id'];
                    
                    foreach ($result as $field => $value) {
                        if (($field != 'page_id') && ($field != 'language_id')) {
                            $posts[$result['page_id']][$field][$result['language_id']] = $value;
                        }
                    }
                }
            }
            
            foreach ($data['elements'] as $element) {
                if (isset($posts[$element['page_id']])) {
                    $post = $posts[$element['page_id']];
                    
                    foreach ($languages as $language) {
                        $implode1 = array();
                        $implode2 = array();
                        $implode3 = array();
                            
                        foreach ($data['fields'] as $field) {
                            if (($field['code'] == 'title') || ($field['code'] == 'meta_title') || ($field['code'] == 'meta_description') || ($field['code'] == 'meta_keyword')) {
                                if (isset($element[$field['code']][$language['language_id']]) && isset($post[$field['code']][$language['language_id']])) {
                                    if ($element[$field['code']][$language['language_id']] != $post[$field['code']][$language['language_id']]) {
                                        if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
                                            $implode3[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
                                        } else {
                                            $implode1[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
                                        }
                                    }
                                }
                            }
                        }
                        
                        if ($implode1) {
                            $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET " . implode(', ', $implode1) . " WHERE page_id = '" . (int)$post['page_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                        }
                        
                        if ($implode2) {
                            $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode2) . " WHERE route='vdl_page_id=" . (int)$post['page_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
                            
                            if (!$this->db->countAffected()) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='vdl_page_id=" . (int)$post['page_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode2) . "'");
                            }
                        }
                        
                        if ($implode3) {
                            $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode3) . " WHERE route='vdl_page_id=" . (int)$post['page_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
                            
                            if (!$this->db->countAffected()) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='vdl_page_id=" . (int)$post['page_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode3) . "'");
                            }
                        }
                        
                        if (isset($element['target_keyword'][$language['language_id']])) {
                            if ((isset($post['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $post['target_keyword'][$language['language_id']])) || !isset($post['target_keyword'][$language['language_id']])) {
                                if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
                                    $target_keyword_store_id = $data['store_id'];
                                } else {
                                    $target_keyword_store_id = 0;
                                }
                                
                                $this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'vdl_page_id=" . (int)$post['page_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
                                
                                if ($element['target_keyword'][$language['language_id']]) {
                                    preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
                                    
                                    $sort_order = 1;
                                    
                                    foreach ($keywords[0] as $keyword) {
                                        $keyword = substr($keyword, 1, strlen($keyword)-2);
                                        $this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'vdl_page_id=" . (int)$post['page_id'] . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
                                        $sort_order++;
                                    }
                                }
                            }
                        }
                        
                        if (isset($element['url_keyword'][$language['language_id']])) {
                            if ((isset($post['url_keyword'][$language['language_id']]) && ($element['url_keyword'][$language['language_id']] != $post['url_keyword'][$language['language_id']])) || !isset($post['url_keyword'][$language['language_id']])) {
                                if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
                                    $url_keyword_store_id = $data['store_id'];
                                } else {
                                    $url_keyword_store_id = 0;
                                }
                                
                                if (VERSION >= '3.0.0.0') {
                                    $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'vdl_page_id=" . (int)$element['page_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
                                        
                                    if ($element['url_keyword'][$language['language_id']]) {
                                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'vdl_page_id=" . (int)$element['page_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
                                    }
                                } else {
                                    $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'vdl_page_id=" . (int)$element['page_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
                                    
                                    if ($element['url_keyword'][$language['language_id']]) {
                                        $this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'vdl_page_id=" . (int)$element['page_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
                                    }
                                
                                    if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
                                        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'vdl_page_id=" . (int)$element['page_id'] . "'");
                                            
                                        if ($element['url_keyword'][$language['language_id']]) {
                                            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'vdl_page_id=" . (int)$element['page_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            $this->cache->delete('path.blog_post');
            $this->cache->delete('url_rewrite');
        }
        
        return true;
    }
}
