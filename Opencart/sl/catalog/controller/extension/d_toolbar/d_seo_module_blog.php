<?php
class ControllerExtensionDToolbarDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_toolbar/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array();
	
	/*
	*	Functions for Toolbar.
	*/
	public function toolbar_config($route) {
		$this->load->model($this->route);
		
		$data = array();
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
				
		if ($route == 'extension/d_blog_module/category') {
			if (isset($this->request->get['category_id'])) {
				$category_id = (int)$this->request->get['category_id'];
			} else {
				$category_id = 0;
			}
			
			if ($category_id) {
				$data['route'] = 'bm_category_id=' . $category_id;
				$data['edit'] = $this->{'model_extension_d_toolbar_' . $this->codename}->link('extension/d_blog_module/category/edit', $url_token . '&category_id=' . $category_id, true);
			}
		}
			
		if ($route == 'extension/d_blog_module/post') {
			if (isset($this->request->get['post_id'])) {
				$post_id = (int)$this->request->get['post_id'];
			} else {
				$post_id = 0;
			}
				
			if ($post_id) {
				$data['route'] = 'bm_post_id=' . $post_id;
				$data['edit'] = $this->{'model_extension_d_toolbar_' . $this->codename}->link('extension/d_blog_module/post/edit', $url_token . '&post_id=' . $post_id, true);
			}
		}
		
		if ($route == 'extension/d_blog_module/author') {
			if (isset($this->request->get['user_id'])) {
				$user_id = (int)$this->request->get['user_id'];
			} else {
				$user_id = 0;
			}
				
			if ($user_id) {
				$author = $this->{'model_extension_d_toolbar_' . $this->codename}->getAuthor($user_id);
				
				if (isset($author['author_id'])) {
					$data['route'] = 'bm_author_id=' . $author['author_id'];
					$data['edit'] = $this->{'model_extension_d_toolbar_' . $this->codename}->link('extension/d_blog_module/author/edit', $url_token . '&author_id=' . $author['author_id'], true);
				}
			}
		}
		
		return $data;
	}
}
