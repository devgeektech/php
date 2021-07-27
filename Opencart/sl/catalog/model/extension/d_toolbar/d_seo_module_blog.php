<?php
class ModelExtensionDToolbarDSEOModuleBlog extends Model {
		
	/*
	*	Return Author.
	*/
	public function getAuthor($user_id) {	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_author WHERE user_id = " . (int)$user_id);
		
		return $query->row;
	}
	
	/*
	*	Return Link.
	*/
	public function link($route, $args = '', $secure = false) {
		$url = $this->config->get('config_url') . 'admin/';
		$ssl = $this->config->get('config_ssl') . 'admin/';
	
		if ($ssl && $secure) {
			$url = $ssl . 'index.php?route=' . $route;
		} else {
			$url = $url . 'index.php?route=' . $route;
		}
		
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}
				
		return $url; 
	}
}

?>