<?php
class ControllerExtensionModuleCustomBlock extends Controller
{
    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    public function index($setting)
    {
		$this->load->model('extension/d_blog_module/post');
        $module_info = $setting;
		
		$data['posts'] = array();
		if($module_info['status'] == 1) {
			$posts = $module_info['posts'];
			if($posts) {
				foreach($posts as $post_id) {
					$post_info = $this->model_extension_d_blog_module_post->getPost($post_id);
					$data['posts'][] = array(
						"post_id" => $post_info['post_id'],
						"post_title" => $post_info['title'],
						"short_description" => html_entity_decode($post_info['short_description'], ENT_QUOTES, 'UTF-8'),
						"description" => html_entity_decode($post_info['description'], ENT_QUOTES, 'UTF-8'),
					
					);
				}
			}
			$this->load->language('extension/module/customblock');
			$data['route'] = HTTPS_SERVER;
			$data['heading_title'] = $module_info['name'];
			$data['text_button'] = $this->language->get('text_button');
			$data['num_posts'] = count($data['posts']);
			
		}
		//echo '<pre>';print_r($data);die();
        if (empty($data['posts'])) {
            return;
        } else {
            return $this->load->view('extension/module/customblock', $data);
        }
    }

}