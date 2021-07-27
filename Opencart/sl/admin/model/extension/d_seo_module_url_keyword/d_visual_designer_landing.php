<?php
class ModelExtensionDSEOModuleURLKeywordDVisualDesignerLanding extends Model
{
    private $codename = 'd_visual_designer_landing';
    
    /*
    *	Edit URL Element.
    */
    public function editURLElement($data)
    {
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        if (isset($data['route']) && isset($data['store_id']) && isset($data['language_id']) && isset($data['url_keyword']) && trim($data['url_keyword'])) {
            if (strpos($data['route'], 'vdl_page_id') === 0) {
                $url_keyword_store_id = 0;
                
                if (strpos($data['route'], 'vdl_page_id') === 0) {
                    if (isset($field_info['sheet']['landing_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['v']['field']['url_keyword']['multi_store_status']) {
                        $url_keyword_store_id = $data['store_id'];
                    } else {
                        $url_keyword_store_id = 0;
                    }
                }
                
                if (VERSION >= '3.0.0.0') {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
                    $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = '" . $this->db->escape($data['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($data['url_keyword']) . "'");
                } else {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
                    $this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = '" . $this->db->escape($data['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($data['url_keyword']) . "'");
        
                    if (($url_keyword_store_id == 0) && ($data['language_id'] == (int)$this->config->get('config_language_id'))) {
                        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($data['route']) . "'");
                        $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($data['route']) . "', keyword = '" . $this->db->escape($data['url_keyword']) . "'");
                    }
                }
            }
        }
        
        $this->cache->delete('url_rewrite');
    }
    
    /*
    *	Return URL Elements.
    */
    public function getURLElements()
    {

        $this->load->model('extension/module/' . $this->codename);
        
        $stores = $this->{'model_extension_module_' . $this->codename}->getStores();
        
        $field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
        
        $url_elements = array();
        
        if (VERSION >= '3.0.0.0') {
            $query = $this->db->query("SELECT p.page_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('vdl_page_id=', p.page_id))");
        } else {
            $query = $this->db->query("SELECT p.page_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('vdl_page_id=', p.page_id))");
        }

        foreach ($query->rows as $result) {
            $route = 'vdl_page_id=' . $result['page_id'];
            $url_elements[$route]['route'] = $route;
            
            if (!isset($url_elements[$route]['url_keyword'])) {
                $url_elements[$route]['url_keyword'] = array();
            }
            
            if ((isset($field_info['sheet']['landing_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['landing_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['landing_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['landing_page']['field']['url_keyword']['multi_store_status'])) {
                $url_elements[$route]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
            } elseif ($result['store_id'] == 0) {
                foreach ($stores as $store) {
                    $url_elements[$route]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
                }
            }
        }

        return $url_elements;
    }
}
