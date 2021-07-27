<?php
class ControllerExtensionModuleDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/module/d_seo_module_blog';
		
	public function category_before($route, &$data) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_before', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function category_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_after', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function category_get_category_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_get_category', $output);
				if ($info) $output = $info;
			}
		}
	}
		
	public function category_get_categories_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_get_categories', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function category_get_all_categories_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_get_all_categories', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function category_get_category_parents_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_get_category_parents', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function category_get_category_by_post_id_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_get_category_by_post_id', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function post_before($route, &$data) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_before', $data);
				if ($info) $data = $info;
			}
		}			
	}
			
	public function post_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_after', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function post_get_post_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_get_post', $output);
				if ($info) $output = $info;
			}
		}
	}
		
	public function post_get_posts_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_get_posts', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function post_get_posts_by_category_id_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_get_posts_by_category_id', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function post_get_prev_post_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_get_prev_post', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function post_get_next_post_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_get_next_post', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function author_before($route, &$data) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_before', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function author_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_after', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function author_get_author_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_get_author', $output);
				if ($info) $output = $info;
			}
		}
	}
		
	public function search_before($route, &$data) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/search_before', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function search_after($route, $data, &$output) {
		$this->load->model($this->route);
		
		$status = ($this->config->get('module_' . $this->codename . '_status')) ? $this->config->get('module_' . $this->codename . '_status') : false;
		
		if ($status) {
			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/search_after', $output);
				if ($info) $output = $info;
			}
		}
	}
}