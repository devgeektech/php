<?php
class ControllerExtensionDSEOModuleURLKeywordDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_url_keyword/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array();
	
	/*
	*	Functions for SEO Module URL Keyword.
	*/
	public function edit_url_element($url_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_url_keyword_' . $this->codename}->editURLElement($url_element_data);
	}
	
	public function url_elements() {	
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_url_keyword_' . $this->codename}->getURLElements();
	}
	
	public function store_url_elements_links($store_url_elements) {	
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		foreach ($store_url_elements as $store_id => $url_elements) {
			foreach ($url_elements as $url_element_key => $url_element) {
				if (strpos($url_element['route'], 'bm_category_id') === 0) {
					$route_arr = explode("bm_category_id=", $url_element['route']);
				
					if (isset($route_arr[1])) {
						$category_id = $route_arr[1];
						$store_url_elements[$store_id][$url_element_key]['link'] = $this->url->link('extension/d_blog_module/category/edit', $url_token . '&category_id=' . $category_id, true);
					}
				} elseif (strpos($url_element['route'], 'bm_post_id') === 0) {
					$route_arr = explode("bm_post_id=", $url_element['route']);
				
					if (isset($route_arr[1])) {
						$post_id = $route_arr[1];
						$store_url_elements[$store_id][$url_element_key]['link'] = $this->url->link('extension/d_blog_module/post/edit', $url_token . '&post_id=' . $post_id, true);
					}
				} elseif (strpos($url_element['route'], 'bm_author_id') === 0) {
					$route_arr = explode("bm_author_id=", $url_element['route']);
				
					if (isset($route_arr[1])) {
						$author_id = $route_arr[1];
						$store_url_elements[$store_id][$url_element_key]['link'] = $this->url->link('extension/d_blog_module/author/edit', $url_token . '&author_id=' . $author_id, true);
					}
				}
			}
		}
		
		return $store_url_elements;
	}
}
