<?php
/*
*    location: admin/model
*/

class ModelExtensionModuleDVisualDesignerlanding extends Model
{
    public $codename = 'd_visual_designer_landing';

    public function installTemplate($variation_id, $language_id, $config, $template_id) {
       $this->load->model('extension/d_visual_designer/designer');

       $this->model_extension_d_visual_designer_designer->installTemplate($this->codename, $variation_id, 'description['.$language_id.'][description]', $config, $template_id);
    }
    
    public function installModule()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dvdl_page` (
            `page_id` INT(11) NOT NULL AUTO_INCREMENT,
            `status` INT(11) NOT NULL,
            `header_status` TINYINT(4) NOT NULL,
            `footer_status` TINYINT(4) NOT NULL,
            `display_title` TINYINT(1) NOT NULL,
            `full_width` TINYINT(1) NOT NULL,
            `sort_order` INT(11) NOT NULL,
            PRIMARY KEY (`page_id`)
            ) COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");
        $this->db->query("CREATE TABLE IF NOT EXISTS`".DB_PREFIX."dvdl_page_description` (
            `page_id` INT(11) NOT NULL,
            `language_id` INT(11) NOT NULL,
            `title` VARCHAR(256) NOT NULL,
            `meta_title` VARCHAR(256) NULL DEFAULT NULL,
            `meta_description` TEXT NULL,
            `meta_keyword` TEXT NULL,
            PRIMARY KEY (`page_id`, `language_id`)
            ) COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");
        $this->db->query("CREATE TABLE IF NOT EXISTS`".DB_PREFIX."dvdl_page_to_store` (
            `page_id` INT(11) NOT NULL,
            `store_id` INT(11) NOT NULL,
            PRIMARY KEY (`page_id`, `store_id`)
            ) COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");
        $this->db->query("CREATE TABLE IF NOT EXISTS`".DB_PREFIX."dvdl_variation` (
            `variation_id` INT(11) NOT NULL AUTO_INCREMENT,
            `page_id` INT(11) NOT NULL,
            `sort_order` INT(11) NOT NULL,
            `status` TINYINT(1) NOT NULL,
            `view` INT(11) NOT NULL,
            PRIMARY KEY (`variation_id`)
            ) COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");
        $this->db->query("CREATE TABLE IF NOT EXISTS`".DB_PREFIX."dvdl_variation_description` (
            `variation_id` INT(11) NOT NULL,
            `language_id` INT(11) NOT NULL,
            `description` LONGTEXT NULL,
            PRIMARY KEY (`variation_id`, `language_id`)
            ) COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");
        $this->db->query("CREATE TABLE  IF NOT EXISTS `".DB_PREFIX."dvdl_conversion` (
            `description_id` VARCHAR(50) NOT NULL,
            `type` VARCHAR(50) NOT NULL,
            `count` INT(11) NOT NULL,
            `date_added` DATE NOT NULL
            )
            COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dvdl_conversion_ip` (
              description_id varchar(50) not null,
              type           varchar(50) not null,
              ip             varchar(40) not null,
              date_added     datetime    not null,
              primary key (description_id, type, ip)
            )
            COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dvdl_variation_ip` (
            `variation_id` INT(11) NOT NULL,
            `ip` VARCHAR(40) NOT NULL,
            `date_added` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`variation_id`, `ip`)
            )
            COLLATE='utf8_general_ci' ENGINE=MyISAM;
            ");

        $stores = $this->getStores();
        $languages = $this->getLanguages();
            
        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "d_meta_data (route VARCHAR(255) NOT NULL, store_id INT(11) NOT NULL, language_id INT(11) NOT NULL, name VARCHAR(64) NOT NULL, title VARCHAR(64) NOT NULL, description TEXT NOT NULL, short_description TEXT NOT NULL, meta_title VARCHAR(255) NOT NULL, meta_description VARCHAR(255) NOT NULL, meta_keyword VARCHAR(255) NOT NULL, tag TEXT NOT NULL, custom_title_1 VARCHAR(255) NOT NULL, custom_title_2 VARCHAR(255) NOT NULL, custom_image_title VARCHAR(255) NOT NULL, custom_image_alt VARCHAR(255) NOT NULL, meta_robots VARCHAR(32) NOT NULL DEFAULT 'index,follow', PRIMARY KEY (route, store_id, language_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dvdl_page_description");
        
        foreach ($query->rows as $result) {
            foreach ($stores as $store) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'vdl_page_id=" . (int)$result['page_id'] . "', store_id = '" . (int)$store['store_id'] . "', language_id='" . (int)$result['language_id'] . "'");
            }
        }

        if (VERSION >= '3.0.0.0') {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'vdl_page_id=%'");
        } else {
            $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "d_url_keyword (route VARCHAR(255) NOT NULL, store_id INT(11) NOT NULL, language_id INT(11) NOT NULL, keyword VARCHAR(255) NOT NULL, PRIMARY KEY (route, store_id, language_id), KEY keyword (keyword)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
            
            $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'vdl_page_id=%'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'vdl_page_id=%'");
        }
    }
    
    public function uninstallModule()
    {
        $this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."dvdl_page");
        $this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."dvdl_page_description");
        $this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."dvdl_page_to_store");
        $this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."dvdl_variation");
        $this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."dvdl_variation_description");
        $this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."dvdl_conversion");
        $this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."dvdl_variation_ip");
        

        $this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route LIKE 'vdl_page_id=%'");
        
        $query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "d_target_keyword'");
        
        if ($query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'vdl_page_id=%'");
        }

        if (VERSION >= '3.0.0.0') {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'vdl_page_id=%'");
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'vdl_page_id=%'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'vdl_page_id=%'");
        }

        $this->cache->delete('url_rewrite');
    }

    /*
    *	Return list of stores.
    */
    public function getStores()
    {
        $this->load->model('setting/store');
        
        $result = array();
        
        $result[] = array(
            'store_id' => 0,
            'name' => $this->config->get('config_name')
        );
        
        $stores = $this->model_setting_store->getStores();
        
        if ($stores) {
            foreach ($stores as $store) {
                $result[] = array(
                    'store_id' => $store['store_id'],
                    'name' => $store['name']
                );
            }
        }
        
        return $result;
    }

    /*
    *	Return store.
    */
    public function getStore($store_id)
    {
        $this->load->model('setting/store');
        
        $result = array();
        
        if ($store_id == 0) {
            $result = array(
                'store_id' => 0,
                'name' => $this->config->get('config_name'),
                'url' => HTTP_CATALOG,
                'ssl' => HTTPS_CATALOG
            );
        } else {
            $store = $this->model_setting_store->getStore($store_id);
            
            $result = array(
                'store_id' => $store['store_id'],
                'name' => $store['name'],
                'url' => $store['url'],
                'ssl' => $store['ssl']
            );
        }
                
        return $result;
    }

    public function saveSEOExtensions($seo_extensions)
    {
        $this->load->model('setting/setting');
        
        $setting['d_seo_extension_install'] = $seo_extensions;
        
        $this->model_setting_setting->editSetting('d_seo_extension', $setting);
    }

    /*
    * Return list of installed SEO extensions.
    */
    public function getInstalledSEOExtensions() {
        $this->load->model('setting/setting');

        $installed_extensions = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");

        foreach ($query->rows as $result) {
            $installed_extensions[] = $result['code'];
        }

        $installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
        $installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();

        $seo_extensions = array();

        $files = glob(DIR_APPLICATION . 'controller/extension/d_seo_module/*.php');

        if ($files) {
            foreach ($files as $file) {
                $seo_extension = basename($file, '.php');

                if (in_array($seo_extension, $installed_extensions) && in_array($seo_extension, $installed_seo_extensions)) {
                    $seo_extensions[] = $seo_extension;
                }
            }
        }

        return $seo_extensions;
    }

    public function getSEOLandingExtensions()
    {
        $this->load->model('setting/setting');
        
        $installed_extensions = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");

        foreach ($query->rows as $result) {
            $installed_extensions[] = $result['code'];
        }

        $installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
        $installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();

        $seo_extensions = array();

        $files = glob(DIR_APPLICATION . 'controller/extension/'.$this->codename.'_seo/*.php');

        if ($files) {
            foreach ($files as $file) {
                $seo_extension = basename($file, '.php');
        
                if (in_array($seo_extension, $installed_extensions) && in_array($seo_extension, $installed_seo_extensions)) {
                    $seo_extensions[] = $seo_extension;
                }
            }
        }

        return $seo_extensions;
    }
    
    /*
    *   Return list of SEO extensions.
    */
    public function getSEOExtensions()
    {
        $this->load->model('setting/setting');
                
        $installed_extensions = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
        
        foreach ($query->rows as $result) {
            $installed_extensions[] = $result['code'];
        }
        
        $installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
        $installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();
        
        $seo_extensions = array();
        
        $files = glob(DIR_APPLICATION . 'controller/extension/d_seo_module/*.php');
        
        if ($files) {
            foreach ($files as $file) {
                $seo_extension = basename($file, '.php');
                
                if (in_array($seo_extension, $installed_extensions) && in_array($seo_extension, $installed_seo_extensions)) {
                    $seo_extensions[] = $seo_extension;
                }
            }
        }
        
        return $seo_extensions;
    }

    public function addPage($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_page SET 
            sort_order = '" . (int)$data['sort_order'] . "',
            header_status = '" .(int)$data['header_status'] . "', 
            footer_status = '" .(int)$data['footer_status'] . "', 
            display_title = '" .(int)$data['display_title'] . "', 
            full_width = '" .(int)$data['full_width'] . "', 
            status = '" . (int)$data['status'] . "'");

        $page_id = $this->db->getLastId();

        foreach ($data['page_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_page_description SET 
                page_id = '" . (int)$page_id . "',
                language_id = '" . (int)$language_id . "',
                title = '" . $this->db->escape($value['title']) . "',
                meta_title = '" . $this->db->escape($value['meta_title']) . "',
                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'
                ");
        }

        if (isset($data['page_store'])) {
            foreach ($data['page_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_page_to_store SET page_id = '" . (int)$page_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
        
        $data['page_id'] = $page_id;
        
        $seo_extensions = $this->getSEOLandingExtensions();
        
        foreach ($seo_extensions as $seo_extension) {
            $this->load->controller('extension/'.$this->codename . '_seo/' . $seo_extension . '/page_add_product', $data);
        }

        return $page_id;
    }

    public function editPage($page_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "dvdl_page SET 
            sort_order = '" . (int)$data['sort_order'] . "', 
            header_status = '" .(int)$data['header_status'] . "', 
            footer_status = '" .(int)$data['footer_status'] . "', 
            display_title = '" .(int)$data['display_title'] . "', 
            full_width = '" .(int)$data['full_width'] . "', 
            status = '" . (int)$data['status'] . "'
            WHERE page_id = '" . (int)$page_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_page_description WHERE page_id = '" . (int)$page_id . "'");

        foreach ($data['page_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_page_description SET 
                page_id = '" . (int)$page_id . "',
                language_id = '" . (int)$language_id . "',
                title = '" . $this->db->escape($value['title']) . "',
                meta_title = '" . $this->db->escape($value['meta_title']) . "',
                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_page_to_store WHERE page_id = '" . (int)$page_id . "'");

        if (isset($data['page_store'])) {
            foreach ($data['page_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_page_to_store SET 
                    page_id = '" . (int)$page_id . "', 
                    store_id = '" . (int)$store_id . "'");
            }
        }

        $data['page_id'] = $page_id;
        
        $seo_extensions = $this->getSEOLandingExtensions();
        
        foreach ($seo_extensions as $seo_extension) {
            $this->load->controller('extension/'.$this->codename.'_seo/' . $seo_extension . '/page_edit_product', $data);
        }
    }

    public function deletePage($page_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_page WHERE page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_page_description WHERE page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_page_to_store WHERE page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_variation_description 
            WHERE variation_id IN (SELECT v.variation_id FROM ".DB_PREFIX."dvdl_variation v WHERE v.page_id = '" . (int)$page_id . "')");
        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_variation WHERE page_id = '" . (int)$page_id . "'");
        
        $seo_extensions = $this->getSEOlandingExtensions();
        
        foreach ($seo_extensions as $seo_extension) {
            $this->load->controller('extension/'.$this->codename . $seo_extension . '/page_delete_product', array('page_id' => $page_id));
        }
    }
    
    public function deleteVariation($variation_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_variation WHERE variation_id = '" . (int)$variation_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "dvdl_variation_description WHERE variation_id = '" . (int)$variation_id . "'");
    }
    
    public function saveVariations($data, $active_variation_id, $page_id)
    {
        foreach ($data as $variation_id => $variation_data) {
            $old_variation_id = $variation_id;
            
            if (!empty($variation_data['new'])) {
                $variation_id = $this->addVariation($page_id, $variation_data);
            } else {
                $this->editVariation($page_id, $variation_id, $variation_data);
            }
        }
    }

    public function addVariation($page_id, $data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_variation SET 
            page_id = '" . (int)$page_id . "',
            status = '" . (int)$data['status'] . "',
            view = '0',
            sort_order = '" . (int)$data['sort_order'] . "'");
        
        $variation_id = $this->db->getLastId();
        
        if (!empty($data['description'])) {
            foreach ($data['description'] as $language_id => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_variation_description SET 
                    language_id = '" . (int)$language_id . "',
                    variation_id = '" . (int)$variation_id . "',
                    description = '" . $this->db->escape($value['description']) . "'");
            }
        }
        return $variation_id;
    }
    public function editVariation($variation_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "dvdl_variation SET 
            sort_order = '" . (int)$data['sort_order'] . "',
            status = '".(int)$data['status']."'
            WHERE variation_id = '" . (int)$variation_id . "'");
        
        $this->db->query("DELETE FROM ".DB_PREFIX."dvdl_variation_description WHERE variation_id='".$variation_id."'");
        
        if (!empty($data['description'])) {
            foreach ($data['description'] as $language_id => $value) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_variation_description SET 
                    language_id = '" . (int)$language_id . "',
                    variation_id = '" . (int)$variation_id . "',
                    description = '" . $this->db->escape($value['description']) . "'");
            }
        }
    }
    public function copyVariation($variation_id)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_variation (page_id, sort_order, status, view) 
            SELECT page_id, sort_order, status, '0' as view
            FROM ".DB_PREFIX."dvdl_variation
            WHERE variation_id = '" . (int)$variation_id . "'");

        $new_variation_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "dvdl_variation_description (language_id, variation_id, description) 
            SELECT language_id, '".$new_variation_id."' as variation_id, description
            FROM ".DB_PREFIX."dvdl_variation_description
            WHERE variation_id = '" . (int)$variation_id . "'");
    }

    public function setStatus($variation_id, $status)
    {
        $this->db->query("UPDATE ".DB_PREFIX."dvdl_variation SET status='".(int)$status."' WHERE variation_id='".(int)$variation_id."'");
    }

    public function getVariations($page_id)
    {
        $query = $this->db->query("SELECT *, char((SELECT count(*) FROM ".DB_PREFIX."dvdl_variation v2 WHERE v2.variation_id < v.variation_id AND v2.page_id = v.page_id)+65) as symbol  FROM ".DB_PREFIX."dvdl_variation v WHERE page_id = '".$page_id."'");

        $variation_data = array();
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $variation_data[$row['variation_id']] = array(
                    'variation_id' => $row['variation_id'],
                    'sort_order' => $row['sort_order'],
                    'symbol' => $row['symbol'],
                    'status' => $row['status'],
                    'view' => $row['view']
                    );
            }
        }
        return $variation_data;
    }
    
    public function getVariation($variation_id)
    {
        $query = $this->db->query("SELECT *, char((SELECT count(*) FROM ".DB_PREFIX."dvdl_variation v2 WHERE v2.variation_id < v.variation_id AND v2.page_id = v.page_id)+65) as symbol FROM ".DB_PREFIX."dvdl_variation v WHERE v.variation_id = '".$variation_id."'");

        return $query->row;
    }
    public function getLastSymbol($page_id)
    {
        $query = $this->db->query("SELECT char((SELECT count(*) FROM ".DB_PREFIX."dvdl_variation v2 WHERE v2.variation_id < v.variation_id AND v2.page_id = v.page_id)+65) as symbol FROM ".DB_PREFIX."dvdl_variation v WHERE page_id='".$page_id."' ORDER BY variation_id DESC LIMIT 1");
        if ($query->num_rows > 0) {
            return $query->row['symbol'];
        } else {
            return 'A';
        }
    }

    public function getVariationDescription($variation_id)
    {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."dvdl_variation v
            LEFT JOIN ".DB_PREFIX."dvdl_variation_description vd ON v.variation_id = vd.variation_id WHERE v.variation_id = '".$variation_id."'");
        $variation_data = array();
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $variation_data[$row['language_id']] = array(
                    'variation_id' => $row['variation_id'],
                    'description' => html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8')
                    );
            }
        }
        return $variation_data;
    }
    
    public function getPage($page_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "dvdl_page WHERE page_id = '" . (int)$page_id . "'");

        return $query->row;
    }
    
    public function getPageDescriptions($page_id)
    {
        $page_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dvdl_page_description WHERE page_id = '" . (int)$page_id . "'");

        foreach ($query->rows as $result) {
            $page_description_data[$result['language_id']] = array(
                'title'            => $result['title'],
                'meta_title'       => html_entity_decode($result['meta_title'], ENT_QUOTES, 'UTF-8'),
                'meta_description' => html_entity_decode($result['meta_description'], ENT_QUOTES, 'UTF-8'),
                'meta_keyword'     => html_entity_decode($result['meta_keyword'], ENT_QUOTES, 'UTF-8')
                );
        }

        return $page_description_data;
    }
    
    public function getPageStores($page_id)
    {
        $page_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dvdl_page_to_store WHERE page_id = '" . (int)$page_id . "'");

        foreach ($query->rows as $result) {
            $page_store_data[] = $result['store_id'];
        }

        return $page_store_data;
    }
    
    public function getPages($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM ".DB_PREFIX."dvdl_page p
            LEFT JOIN ".DB_PREFIX."dvdl_page_description pd
            ON pd.page_id = p.page_id
            WHERE pd.language_id = '".(int)$this->config->get('config_language_id')."'";
            $sort_data = array(
                'pd.title',
                'p.sort_order'
                );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY pd.title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }
            $query = $this->db->query($sql);
            
            return $query->rows;
        } else {
            $page_data = $this->cache->get('landing_page.' . (int)$this->config->get('config_language_id'));

            if (!$page_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dvdl_page p LEFT JOIN " . DB_PREFIX . "dvdl_page_description pd ON (p.page_id = pd.page_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.title");

                $page_data = $query->rows;

                $this->cache->set('landing_page.' . (int)$this->config->get('config_language_id'), $information_data);
            }

            return $page_data;
        }
    }
    
    public function getTotalPages()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "dvdl_page");

        return $query->row['total'];
    }
    
    public function ajax($link)
    {
        return str_replace('&amp;', '&', $link);
    }
    
    public function getGroupId()
    {
        return $this->user->getGroupId();
    }
    
    public function getLink($route, $args, $catalog = false)
    {
        $https = $this->request->server['HTTPS'];
        if (!empty($https)) {
            if ($catalog) {
                $url = HTTPS_CATALOG;
            } else {
                $url = HTTPS_SERVER;
            }
        } else {
            if ($catalog) {
                $url = HTTP_CATALOG;
            } else {
                $url = HTTP_SERVER;
            }
        }
        
        $url .= 'index.php?route=' . $route;
        
        if ($args) {
            if (is_array($args)) {
                $url .= '&amp;' . http_build_query($args);
            } else {
                $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
            }
        }
        
        return $url;
    }

    public function getLanguages()
    {
        $this->load->model('localisation/language');
        
        $languages = $this->model_localisation_language->getLanguages();
        
        foreach ($languages as $key => $language) {
            $languages[$key]['flag'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
        }
        
        return $languages;
    }
}
