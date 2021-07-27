<?php
class ModelExtensionModuleDVisualDesignerLanding extends Model
{

    private $codename = 'd_visual_designer_landing';

    public function getPageByVariation($variation_id)
    {
        $sql = "SELECT DISTINCT *, v.variation_id as variation_id
        FROM " . DB_PREFIX . "dvdl_page p
        LEFT JOIN ".DB_PREFIX."dvdl_page_description pd
        ON pd.page_id = p.page_id
        LEFT JOIN ".DB_PREFIX."dvdl_page_to_store ps
        ON ps.page_id = p.page_id
        LEFT JOIN ".DB_PREFIX."dvdl_variation v 
        ON v.page_id = p.page_id
        LEFT JOIN ".DB_PREFIX."dvdl_variation_description vd 
        ON v.variation_id = vd.variation_id WHERE v.variation_id = '".$variation_id."' AND pd.language_id = '".$this->config->get('config_language_id')."' AND
        vd.language_id = '".$this->config->get('config_language_id')."' AND ps.store_id = '".(int)$this->config->get('config_store_id')."'
        AND p.status = '1'";

        $query = $this->db->query($sql);
        
        $data = $query->row;

        $seo_landing_extensions = $this->getSEOLandingExtensions();

        foreach ($seo_landing_extensions as $seo_landing_extension) {
            $info = $this->load->controller('extension/' . $this->codename . '_seo/' . $seo_landing_extension . '/page_get_page', $data);
            if ($info) {
                $data = $info;
            }
        }

        return $data;
    }

    public function addView($variation_id)
    {
        $client_ip = $this->request->server['REMOTE_ADDR'];

        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."dvdl_variation_ip WHERE `variation_id` = '".$variation_id."' AND `ip`='".$client_ip."'");

        if ($query->num_rows) {
            return;
        }

        $this->db->query("INSERT INTO `".DB_PREFIX."dvdl_variation_ip` SET `variation_id` = '".$variation_id."', `ip` = '".$client_ip."', date_added =NOW()");

        $this->db->query("UPDATE ".DB_PREFIX."dvdl_variation SET view = view+1 WHERE variation_id='".(int)$variation_id."'");
    }

    public function getCurrentVariation($page_id)
    {
        $results = $this->getVariations($page_id);

        $random = array();
        foreach ($results as $variation_id => $variation) {
            if ($variation['status']) {
                $random[] = $variation_id;
            }
        }
        shuffle($random);

        $variation_id = current($random);
        return $variation_id;
    }

    public function getVariations($page_id)
    {
        $query = $this->db->query("SELECT *, char((SELECT count(*) FROM ".DB_PREFIX."dvdl_variation v2 WHERE v2.variation_id < v.variation_id AND v2.page_id = v.page_id)+65) as symbol FROM ".DB_PREFIX."dvdl_variation v LEFT JOIN `".DB_PREFIX."dvdl_variation_description` vd ON v.variation_id = vd.variation_id WHERE v.page_id = '".$page_id."' AND vd.language_id='".(int)$this->config->get('config_language_id')."'");

        $variation_data = array();
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $variation_data[$row['variation_id']] = array(
                    'variation_id' => $row['variation_id'],
                    'status' => $row['status'],
                    'symbol' => $row['symbol']
                    );
            }
        }
        return $variation_data;
    }

    public function editVariation($variation_id, $data)
    {
        if(!empty($data['description'])){
            foreach ($data['description'] as $language_id => $value) {
                $implode = array();

                if(isset($value['description'])){
                    $implode[] = "description='".$this->db->escape($value['description'])."'";
                }

                if(count($implode) > 0){
                    $this->db->query("UPDATE " . DB_PREFIX . "dvdl_variation_description SET ".implode(',', $implode)."
                        WHERE variation_id = '".$variation_id."' AND language_id='".$language_id."'");
                }
            }
        }
    }

    /*
	*	Return list of SEO Landing extensions.
	*/
	public function getSEOLandingExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
		
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
		
		$installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
		$installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();
		
		$seo_landing_extensions = array();
		
        $files = glob(DIR_APPLICATION . 'controller/extension/' . $this->codename . '_seo/*.php');
        
		if ($files) {
			foreach ($files as $file) {
				$seo_landing_extension = basename($file, '.php');
				
				if (in_array($seo_landing_extension, $installed_extensions) && in_array($seo_landing_extension, $installed_seo_extensions)) {
					$seo_landing_extensions[] = $seo_landing_extension;
				}
			}
		}
		
		return $seo_landing_extensions;
	}

    /*
    *	Return list of seo extensions.
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
        
        $files = glob(DIR_APPLICATION . 'controller/extension/' . $this->codename . '/*.php');
        
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
    *	Return list of languages.
    */
    public function getLanguages()
    {
        $this->load->model('localisation/language');
        
        $languages = $this->model_localisation_language->getLanguages();
        
        foreach ($languages as $key => $language) {
            if (VERSION >= '2.2.0.0') {
                $languages[$key]['flag'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
            } else {
                $languages[$key]['flag'] = 'view/image/flags/' . $language['image'];
            }
        }
        
        return $languages;
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
}
