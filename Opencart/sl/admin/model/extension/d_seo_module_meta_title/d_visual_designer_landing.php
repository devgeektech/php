<?php
class ModelExtensionDSEOModuleMetaTitleDVisualDesignerLanding extends Model
{
    private $codename = 'd_visual_designer_landing';
    
    /*
    *	Edit Meta Element.
    */
    public function editMetaElement($data)
    {
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        if (isset($data['route']) && isset($data['store_id']) && isset($data['language_id']) && isset($data['meta_title'])) {
            if (strpos($data['route'], 'vdl_page_id') === 0) {
                $meta_title_store_id = 0;
            
                if (strpos($data['route'], 'bm_post_id') === 0) {
                    if (isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) {
                        $meta_title_store_id = $data['store_id'];
                    } else {
                        $meta_title_store_id = 0;
                    }
                    
                    if ($meta_title_store_id) {
                        $this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '" . $this->db->escape($data['meta_title']) . "' WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . $meta_title_store_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
                        
                        if (!$this->db->countAffected()) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = '" . $this->db->escape($data['route']) . "', store_id='" . (int)$meta_title_store_id . "', language_id='" . (int)$data['language_id'] . "', meta_title = '" . $this->db->escape($data['meta_title']) . "'");
                        }
                    } else {
                        $route_arr = explode("vdl_page_id=", $data['route']);
            
                        if (isset($route_arr[1])) {
                            $page_id = $route_arr[1];
                
                            $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page_description SET meta_title = '" . $this->db->escape($data['meta_title']) . "' WHERE page_id= '" . (int)$page_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
                        }
                    }
                }
            }
        }
    }
    
    /*
    *	Return Meta Elements.
    */
    public function getMetaElements()
    {
        $this->load->model('extension/module/' . $this->codename);
        
        $stores = $this->{'model_extension_module_' . $this->codename}->getStores();
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        $meta_elements = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dvdl_page_description");
                
        foreach ($query->rows as $result) {
            $route = 'vdl_page_id=' . $result['page_id'];
            $meta_elements[$route]['route'] = $route;
            if ((isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status'])) {
                $meta_elements[$route]['meta_title'][0][$result['language_id']] = $result['meta_title'];
            } else {
                foreach ($stores as $store) {
                    $meta_elements[$route]['meta_title'][$store['store_id']][$result['language_id']] = $result['meta_title'];
                }
            }
        }
                
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_meta_data");
        
        foreach ($query->rows as $result) {
            $meta_elements[$result['route']]['route'] = $result['route'];
            
            if (strpos($result['route'], 'vdl_page_id') === 0) {
                if ($result['store_id'] && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['meta_title']['multi_store_status']) {
                    $meta_elements[$result['route']]['meta_title'][$result['store_id']][$result['language_id']] = $result['meta_title'];
                }
            }
        }
        
        return $meta_elements;
    }
}
