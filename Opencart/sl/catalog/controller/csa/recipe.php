<?php

class ControllercsaRecipe extends Controller {

	public function index() {

		$this->language->load('csa/Recipe');

		

		$this->load->model('csa/Recipe');

	 

		$this->document->setTitle($this->language->get('heading_title')); 

	 

		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(

			'text' 		=> $this->language->get('text_home'),

			'href' 		=> $this->url->link('common/home')

		);

		

		$data['breadcrumbs'][] = array(

			'text' 		=> $this->language->get('heading_title'),

			'href' 		=> $this->url->link('csa/recipe')

		);

		  

		$url = '';

		

		if (isset($this->request->get['page'])) {

			$url .= '&page=' . $this->request->get['page'];

		}	



		if (isset($this->request->get['page'])) {

			$page = $this->request->get['page'];

		} else { 

			$page = 1;

		}

		

		$filter_data = array(

			'page' 	=> $page,

			'limit' => 10,

			'start' => 10 * ($page - 1),

		);

		

		$total = $this->model_csa_Recipe->getTotalRecipe();

		

		$pagination = new Pagination();

		$pagination->total = $total;

		$pagination->page = $page;

		$pagination->limit = 10;

		$pagination->url = $this->url->link('csa/recipe', 'page={page}');

		

		$data['pagination'] = $pagination->render();

	 

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($total - 10)) ? $total : ((($page - 1) * 10) + 10), $total, ceil($total / 10));



		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_title'] = $this->language->get('text_title');

		$data['text_description'] = $this->language->get('text_description');

		$data['text_date'] = $this->language->get('text_date');

		$data['text_view'] = $this->language->get('text_view');

	 

		$all_Recipe = $this->model_csa_Recipe->getAllRecipe($filter_data);

	 

		$data['all_Recipe'] = array();

		

		$this->load->model('tool/image');


		foreach ($all_Recipe as $Recipe) {

			$original = array(
				500,500
			);
			if ($Recipe['image']) {
				$original = getimagesize(DIR_IMAGE.$Recipe['image']);
			}
			$data['all_Recipe'][] = array (

				'title' 		=> html_entity_decode($Recipe['recipe_title'], ENT_QUOTES),

				'title' 		=> html_entity_decode($Recipe['recipe_title'], ENT_QUOTES),

				'image'			=> $this->model_tool_image->resize($Recipe['image'], $original[0], $original[1]),

				'directions' 	=> (utf8_strlen(strip_tags(html_entity_decode($Recipe['directions'], ENT_QUOTES))) > 250 ? utf8_substr(strip_tags(html_entity_decode($Recipe['directions'], ENT_QUOTES)), 0, 250) . '...' : strip_tags(html_entity_decode($Recipe['directions'], ENT_QUOTES))),

				'view' 			=> $this->url->link('csa/recipe/recipe', 'recipe_id=' . $Recipe['recipe_id']),

				'date_added' 	=> date('j M Y', strtotime($Recipe['date_added']))

			);

		}



		$this->load->model('csa/Recipe');
		$getAllRecipe = $this->model_csa_Recipe->getAllRecipe($filter_data);
		if($getAllRecipe){
			//print_r($getAllRecipe);
			$data['getAllRecipe'] = array();

			foreach ($getAllRecipe as $Recipe) {

				$original = array(
					500,500
				);
				if ($Recipe['image']) {
					$original = getimagesize(DIR_IMAGE.$Recipe['image']);
				}
				$data['getAllRecipe'][] = array (
	
					'title' 		=> html_entity_decode($Recipe['recipe_title'], ENT_QUOTES),
	
					'image'			=> $this->model_tool_image->resize($Recipe['image'], $original[0], $original[1]),
	
					'directions' 	=> (utf8_strlen(strip_tags(html_entity_decode($Recipe['directions'], ENT_QUOTES))) > 250 ? utf8_substr(strip_tags(html_entity_decode($Recipe['directions'], ENT_QUOTES)), 0, 250) . '...' : strip_tags(html_entity_decode($Recipe['directions'], ENT_QUOTES))),
	
					'view' 			=> $this->url->link('csa/recipe/recipe', 'recipe_id=' . $Recipe['recipe_id']),
	
					'date_added' 	=> date('j M Y', strtotime($Recipe['date_added']))
	
				);
	
			}
	
		}

		$this->load->model('extension/news');
		$filter_data1 = array(
			'limit' => 10
		);
		$all_news_bottom = $this->model_extension_news->getAllNews($filter_data1);
		$data['all_news_bottom'] = array();
		$this->load->model('tool/image');
		$data['all_news_url'] = $this->url->link('extension/news', '', true);;
        if ($all_news_bottom) {
            foreach ($all_news_bottom as $news) {
				$original = array(
					300,300
				);
                if ($news['image']) {
                    $original = getimagesize(DIR_IMAGE.$news['image']);
                }
                $data['all_news_bottom'][] = array(

                'title' 		=> html_entity_decode($news['title'], ENT_QUOTES),

                'image'			=> $this->model_tool_image->resize($news['image'],  $original[0], $original[1]),

                'description' 	=> (utf8_strlen(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))) > 50 ? utf8_substr(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES)), 0, 50) . '...' : strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))),

                'view' 			=> $this->url->link('extension/news/news', 'news_id=' . $news['news_id']),

                'date_added' 	=> date($this->language->get('date_format_short'), strtotime($news['date_added']))

            );
            }
		}
		
	 

		$data['column_left'] = $this->load->controller('common/column_left');

		$data['column_right'] = $this->load->controller('common/column_right');

		$data['content_top'] = $this->load->controller('common/content_top');

		$data['content_bottom'] = $this->load->controller('common/content_bottom');

		$data['footer'] = $this->load->controller('common/footer');

		$data['header'] = $this->load->controller('common/header');



		$this->response->setOutput($this->load->view('csa/recipe_list', $data));

	}

 

	public function Recipe() {

		$this->load->model('csa/Recipe');

	  

		$this->language->load('csa/Recipe');

 

		if (isset($this->request->get['recipe_id']) && !empty($this->request->get['recipe_id'])) {

			$Recipe_id = $this->request->get['recipe_id'];

		} else {

			$Recipe_id = 0;

		}

// echo $Recipe_id;

		$Recipe = $this->model_csa_Recipe->getRecipe($Recipe_id);

 //var_dump($Recipe);

		$data['breadcrumbs'] = array();

	  

		$data['breadcrumbs'][] = array(

			'text' 			=> $this->language->get('text_home'),

			'href' 			=> $this->url->link('common/home')

		);

	  

		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('heading_title'),

			'href' => $this->url->link('csa/recipe')

		);

 

		if ($Recipe) {

			$data['breadcrumbs'][] = array(

				'text' 		=> $Recipe['recipe_title'],

				'href' 		=> $this->url->link('csa/recipe/recipe', 'recipe_id=' . $Recipe_id)

			);

			$filter_data = array(
				'limit' => 10
			);
		$getAllRecipe = $this->model_csa_Recipe->getAllRecipe($filter_data);
		if($getAllRecipe){
		//print_r($getAllRecipe);
		$data['getAllRecipe'] = array();
		$this->load->model('tool/image');
		foreach ($getAllRecipe as $Recipe1) {

			$original = array(
				500,500
			);
			if ($Recipe1['image']) {
				$original = getimagesize(DIR_IMAGE.$Recipe1['image']);
			}
			$data['getAllRecipe'][] = array (

				'title' 		=> html_entity_decode($Recipe1['recipe_title'], ENT_QUOTES),

				'image'			=> $this->model_tool_image->resize($Recipe1['image'], $original[0], $original[1]),

				'directions' 	=> (utf8_strlen(strip_tags(html_entity_decode($Recipe1['directions'], ENT_QUOTES))) > 250 ? utf8_substr(strip_tags(html_entity_decode($Recipe1['directions'], ENT_QUOTES)), 0, 250) . '...' : strip_tags(html_entity_decode($Recipe1['directions'], ENT_QUOTES))),

				'view' 			=> $this->url->link('csa/recipe/recipe', 'recipe_id=' . $Recipe1['recipe_id']),

				'date_added' 	=> date('j M Y', strtotime($Recipe1['date_added']))

			);

		}
		}

		$this->load->model('extension/news');
		$filter_data1 = array(
			'limit' => 10
		);
		$all_news_bottom = $this->model_extension_news->getAllNews($filter_data1);
		$data['all_news_bottom'] = array();
		$this->load->model('tool/image');
		$data['all_news_url'] = $this->url->link('extension/news', '', true);;
        if ($all_news_bottom) {
            foreach ($all_news_bottom as $news) {
				$original = array(
					300,300
				);
                if ($news['image']) {
                    $original = getimagesize(DIR_IMAGE.$news['image']);
                }
                $data['all_news_bottom'][] = array(

                'title' 		=> html_entity_decode($news['title'], ENT_QUOTES),

                'image'			=> $this->model_tool_image->resize($news['image'],  $original[0], $original[1]),

                'description' 	=> (utf8_strlen(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))) > 50 ? utf8_substr(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES)), 0, 50) . '...' : strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))),

                'view' 			=> $this->url->link('extension/news/news', 'news_id=' . $news['news_id']),

                'date_added' 	=> date($this->language->get('date_format_short'), strtotime($news['date_added']))

            );
            }
		}
		
		

			$this->document->setTitle($Recipe['recipe_title']);

			

			$this->load->model('tool/image');

			

			$data['image'] = $this->model_tool_image->resize($Recipe['image'], 500, 500);
			$data['banner_image'] = $this->model_tool_image->resize($Recipe['image'],300,300);

			$data['heading_title'] = html_entity_decode($Recipe['recipe_title'], ENT_QUOTES);

			$data['directions'] = html_entity_decode($Recipe['directions'], ENT_QUOTES);
			$data['ingredients'] = html_entity_decode($Recipe['ingredients'], ENT_QUOTES);
			$data['serves'] = html_entity_decode($Recipe['serves'], ENT_QUOTES);
			$data['serving_size'] = html_entity_decode($Recipe['serving_size'], ENT_QUOTES);
			$data['prep_time'] = html_entity_decode($Recipe['prep_time'], ENT_QUOTES);
			$data['cook_time'] = html_entity_decode($Recipe['cook_time'], ENT_QUOTES);
			$data['total_time'] = html_entity_decode($Recipe['total_time'], ENT_QUOTES);
	 

			$data['column_left'] = $this->load->controller('common/column_left');

			$data['column_right'] = $this->load->controller('common/column_right');

			$data['content_top'] = $this->load->controller('common/content_top');

			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$data['footer'] = $this->load->controller('common/footer');

			$data['header'] = $this->load->controller('common/header');



			$this->response->setOutput($this->load->view('csa/recipe', $data));

		} else {

			$data['breadcrumbs'][] = array(

				'text' 		=> $this->language->get('text_error'),

				'href' 		=> $this->url->link('csa/recipe/recipe', 'recipe_id=' . $Recipe_id)

			);

	 

			$this->document->setTitle($this->language->get('text_error'));

	 

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

	 

			$data['column_left'] = $this->load->controller('common/column_left');

			$data['column_right'] = $this->load->controller('common/column_right');

			$data['content_top'] = $this->load->controller('common/content_top');

			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$data['footer'] = $this->load->controller('common/footer');

			$data['header'] = $this->load->controller('common/header');



			$this->response->setOutput($this->load->view('error/not_found', $data));

		}

	}

}