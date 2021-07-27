<?php
class ControllerExtensionModuleCustomBlock extends Controller {
	private $error = array();

	public function index() { 
		$this->load->language('extension/module/customblock');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('customblock', $this->request->post);
			} else { //echo '<pre>';print_r($this->request->post);die();
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			$files = glob(DIR_CACHE . 'cache.vd-pre-render' . '.*');
			if ($files) {
            foreach ($files as $file) {
                if (!@unlink($file)) {
                    clearstatcache(false, $file);
                }
			}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/customblock', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/customblock', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/customblock', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/customblock', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post[$this->codename]['posts'])) {
            $data['posts'] = $this->request->post[$this->codename]['posts'];
		} elseif (!empty($module_info)) {
            $data['posts'] = $module_info['posts'];
        } else{
            $data['posts'] = array();
        }
		//echo '<pre>';print_r($data['posts']);die();
		$this->load->model('extension/d_blog_module/post');
		if($data['posts']) {
			foreach ($data['posts'] as $key => $post_id) {
				$post_info = $this->model_extension_d_blog_module_post->getPost($post_id);
				$data['posts'][$key] = array(
					'title' => $post_info['title'],
					'post_id' => $post_id
				);
			}
		}
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		$url = 'extension/module/customblock/autocomplete&user_token='.$this->session->data['user_token'];
		
		$data['posts_autocomplete'] = $this->url->link($url);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/customblock', $data));
	}
	 public function ajax($route, $url = '', $secure = true){
        return str_replace('&amp;', '&', $this->link($route, $url, $secure));
    }
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/customblock')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
	public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_title']) || isset($this->request->get['filter_tag'])) {
            $this->load->model('extension/module/customblock');
            if (isset($this->request->get['filter_title'])) {
                $filter_title = $this->request->get['filter_title'];
            } else {
                $filter_title = '';
            }

            if (isset($this->request->get['filter_tag'])) {
                $filter_tag = $this->request->get['filter_tag'];
            } else {
                $filter_tag = '';
            }
			if (isset($this->request->get['type'])) {
                $type = $this->request->get['type'];
            } else {
                $type = 0;
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 10;
            }

            $filter_data = array(
                'filter_title' => $filter_title,
                'filter_tag'   => $filter_tag,
                'type'   => $type,
                'start'        => 0,
                'limit'        => $limit
            );
			
            $results = $this->model_extension_module_customblock->getPosts($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'post_id' => $result['post_id'],
                    'title'   => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
                    'tag'     => strip_tags(html_entity_decode($result['tag'], ENT_QUOTES, 'UTF-8')),
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }		
	
}