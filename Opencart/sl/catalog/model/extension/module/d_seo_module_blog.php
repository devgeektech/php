<?php
class ModelExtensionModuleDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	
	/*
	*	Return Author.
	*/
	public function getAuthor($author_id) {	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_author WHERE author_id = " . (int)$author_id);
		
		return $query->row;
	}
	
	/*
	*	Return Author by User.
	*/
	public function getAuthorByUser($user_id) {	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_author WHERE user_id = " . (int)$user_id);
		
		return $query->row;
	}
	
	/*
	*	Return list of installed SEO Blog extensions.
	*/
	public function getInstalledSEOBlogExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
		
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
		
		$installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
		$installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();
		
		$seo_blog_extensions = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/' . $this->codename . '/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$seo_blog_extension = basename($file, '.php');
				
				if (in_array($seo_blog_extension, $installed_extensions) && in_array($seo_blog_extension, $installed_seo_extensions)) {
					$seo_blog_extensions[] = $seo_blog_extension;
				}
			}
		}
		
		return $seo_blog_extensions;
	}
	
	/*
	*	Return list of languages.
	*/
	public function getLanguages() {
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
	public function getStores() {
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