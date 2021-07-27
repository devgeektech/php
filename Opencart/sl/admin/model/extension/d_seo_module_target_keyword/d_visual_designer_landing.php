<?php
class ModelExtensionDSEOModuleTargetKeywordDVisualDesignerLanding extends Model
{
    private $codename = 'd_seo_module_blog';
    
    /*
    *	Edit Target Element.
    */
    public function editTargetElement($data)
    {
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        if (isset($data['route']) && isset($data['store_id']) && isset($data['language_id']) && isset($data['target_keyword'])) {
            if (strpos($data['route'], 'vdl_page_id') === 0) {
                $target_keyword_store_id = 0;
                
                if (strpos($data['route'], 'vdl_page_id') === 0) {
                    if (isset($field_info['sheet']['landing_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['target_keyword']['multi_store_status']) {
                        $target_keyword_store_id = $data['store_id'];
                    } else {
                        $target_keyword_store_id = 0;
                    }
                }
                
                $this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
                
                if ($data['target_keyword']) {
                    preg_match_all('/\[[^]]+\]/', $data['target_keyword'], $keywords);
                
                    $sort_order = 1;
        
                    foreach ($keywords[0] as $keyword) {
                        $keyword = substr($keyword, 1, strlen($keyword) - 2);
                        $this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = '" . $this->db->escape($data['route']) . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$data['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
                    
                        $sort_order++;
                    }
                }
            }
        }
    }
    
    /*
    *	Return Target Elements.
    */
    public function getTargetElements()
    {
        $this->load->model('extension/module/' . $this->codename);
        
        $stores = $this->{'model_extension_module_' . $this->codename}->getStores();
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        $target_elements = array();
                
        $query = $this->db->query("SELECT p.page_id, tk.store_id, tk.language_id, tk.sort_order, tk.keyword FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('vdl_page_id=', p.page_id))");
        
        foreach ($query->rows as $result) {
            $route = 'vdl_page_id=' . $result['page_id'];
            $target_elements[$route]['route'] = $route;
            
            if (!isset($target_elements[$route]['target_keyword'])) {
                $target_elements[$route]['target_keyword'] = array();
            }
            
            if ((isset($field_info['sheet']['landing_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['target_keyword']['multi_store_status'])) {
                $target_elements[$route]['target_keyword'][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
            } elseif ($result['store_id'] == 0) {
                foreach ($stores as $store) {
                    $target_elements[$route]['target_keyword'][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
                }
            }
        }
        
        return $target_elements;
    }
}
