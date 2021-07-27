<?php
/*
*    location: admin/model
*/

class ModelExtensionDVisualDesignerLandingSEODVisualDesignerLanding extends Model
{
    public $codename = 'd_visual_designer_landing';
    
    /*
    *	Save Page Target Keyword.
    */
    public function savePageTargetKeyword($data)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'vdl_page_id=" . (int)$data['page_id'] . "'");
                        
        if (isset($data['target_keyword'])) {
            foreach ($data['target_keyword'] as $store_id => $language_target_keyword) {
                foreach ($language_target_keyword as $language_id => $keywords) {
                    $sort_order = 1;
                
                    foreach ($keywords as $keyword) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'vdl_page_id=" . (int)$data['page_id'] . "', store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
                    
                        $sort_order++;
                    }
                }
            }
        }
    }
    
    /*
    *	Return Page Target Keyword.
    */
    public function getPageTargetKeyword($page_id)
    {
        $target_keyword = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'vdl_page_id=" . (int)$page_id . "' ORDER BY sort_order");
        
        foreach ($query->rows as $result) {
            $target_keyword[$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
        }
        
        return $target_keyword;
    }

    /*
    *   Save Page URL Keyword.
    */

    public function savePageURLKeyword($data)
    {
        if (VERSION >= '3.0.0.0') {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'vdl_page_id=" . (int)$data['page_id'] . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'vdl_page_id=" . (int)$data['page_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'vdl_page_id=" . (int)$data['page_id'] . "'");
        }

        if (isset($data['url_keyword'])) {
            foreach ($data['url_keyword'] as $store_id => $language_url_keyword) {
                foreach ($language_url_keyword as $language_id => $url_keyword) {
                    if ($url_keyword) {
                        if (VERSION >= '3.0.0.0') {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'vdl_page_id=" . (int)$data['page_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
                        } else {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'vdl_page_id=" . (int)$data['page_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
                
                            if (($store_id == 0) && ($language_id == (int)$this->config->get('config_language_id'))) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'vdl_page_id=" . (int)$data['page_id'] . "', keyword = '" . $this->db->escape($url_keyword) . "'");
                            }
                        }
                    }
                }
            }
        }
        
        $this->cache->delete('url_rewrite');
    }

    /*
    *	Delete Page URL Keyword.
    */
    public function deletePageURLKeyword($data)
    {
        if (VERSION >= '3.0.0.0') {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'vdl_page_id=" . (int)$data['page_id'] . "'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'vdl_page_id=" . (int)$data['page_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'vdl_page_id=" . (int)$data['page_id'] . "'");
        }
    }

    /*
    *   Return Page Target Keyword.
    */

    public function getPageURLKeyword($page_id)
    {
        $url_keyword = array();
        
        if (VERSION >= '3.0.0.0') {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'vdl_page_id=" . (int)$page_id . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'vdl_page_id=" . (int)$page_id . "'");
        }
        
        foreach ($query->rows as $result) {
            $url_keyword[$result['store_id']][$result['language_id']] = $result['keyword'];
        }
                        
        return $url_keyword;
    }

    /*
    *	Return Page Meta Data.
    */
    public function getPageMetaData($category_id)
    {
        $meta_data = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_meta_data WHERE route='vdl_page_id=" . (int)$category_id . "'");
        
        foreach ($query->rows as $result) {
            $meta_data[$result['store_id']][$result['language_id']] = array(
                'title'             => $result['title'],
                'meta_title'        => $result['meta_title'],
                'meta_description'  => $result['meta_description'],
                'meta_keyword'      => $result['meta_keyword']
            );
        }
        
        return $meta_data;
    }

    /*
    *	Save Post Meta Data.
    */
    public function savePageMetaData($data)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route = 'vdl_page_id=" . (int)$data['page_id'] . "'");

        if (isset($data['meta_data'])) {
            foreach ($data['meta_data'] as $store_id => $language_meta_data) {
                foreach ($language_meta_data as $language_id => $meta_data) {
                    $implode = array();
                    
                    if ($store_id) {
                        if (isset($meta_data['title'])) {
                            $implode[] = "title = '" . $this->db->escape($meta_data['title']) . "'";
                        }
                        
                        if (isset($meta_data['meta_title'])) {
                            $implode[] = "meta_title = '" . $this->db->escape($meta_data['meta_title']) . "'";
                        }
                        
                        if (isset($meta_data['meta_description'])) {
                            $implode[] = "meta_description = '" . $this->db->escape($meta_data['meta_description']) . "'";
                        }
                        
                        if (isset($meta_data['meta_keyword'])) {
                            $implode[] = "meta_keyword = '" . $this->db->escape($meta_data['meta_keyword']) . "'";
                        }
                    }
                    
                    $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'vdl_page_id=" . (int)$data['page_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', " . implode(', ', $implode));
                }
            }
        }
    }
}
