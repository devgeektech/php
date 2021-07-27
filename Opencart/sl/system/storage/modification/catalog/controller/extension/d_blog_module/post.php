<?php

class ControllerExtensionDBlogModulePost extends Controller
{
    private $id = 'd_blog_module';
    private $route = 'extension/d_blog_module/post';
    private $sub_versions = array('lite', 'light', 'free');
    //private $mbooth = '';
    private $prefix = '';
    private $config_file = '';
    private $error = array();
    private $debug = false;
    private $setting = array();
    private $theme = 'default';

    public function __construct($registry)
    {
        parent::__construct($registry);
        if (!isset($this->user)) {
            if (VERSION >= '2.2.0.0') {
                $this->user = new Cart\User($registry);
            } else {
                $this->user = new User($registry);
            }
        }
        //fix theme detection
        if (VERSION >= '3.0.0.0') {
            $this->theme = $this->config->get('theme_' . $this->config->get('config_theme') . '_directory');
        } elseif (VERSION >= '2.2.0.0') {
            $this->theme = $this->config->get($this->config->get('config_theme') . '_directory');
        } else {
            $this->theme = $this->config->get('config_template');
        }
        
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->language('extension/d_blog_module/post');
        $this->load->model('extension/module/d_blog_module');
        $this->load->model('extension/d_blog_module/category');
        $this->load->model('extension/d_blog_module/post');
        $this->load->model('extension/d_blog_module/review');
        $this->load->model('extension/d_blog_module/author');
        $this->load->model('tool/image');

        $this->session->data['d_blog_module_debug'] = $this->config->get('d_blog_module_debug');

        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);

        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id . '_setting', $this->config->get('config_store_id'), $this->config_file);

        $this->load->model('localisation/language');
        $lang = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
        $locales = array();
        foreach (explode(',', $lang['locale']) as $l) {
            $locales [] = $l;
        }
        $loc_de = setlocale(LC_ALL, $locales);

    }

    public function index()
    {

        if (!$this->config->get('d_blog_module_status')) {
            $this->response->redirect($this->url->link('error/not_found'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL')
        );

        if (isset($this->request->get['post_id'])) {
            $post_id = (int)$this->request->get['post_id'];
        } else {
            $post_id = 0;
        }

        $PostType = '';
        $PostType = $this->model_extension_d_blog_module_post->gettypePostID($post_id);
        if(in_array(1, $PostType)){
            $data['post_type'] = 'recipes';
            $post_info = $this->model_extension_d_blog_module_post->getPostByType($post_id, 1);
            $data['serves'] = $post_info['serves'];
            $data['short_description'] = $post_info['short_description'];
            $data['serving_size'] = $post_info['serving_size'];
            $data['prep_time'] = $post_info['prep_time'];
            $data['cook_time'] = $post_info['cook_time'];
            $data['total_time'] = $post_info['total_time'];
        }else{
        $post_info = $this->model_extension_d_blog_module_post->getPost($post_id);
        }

        if ($post_info) {
            if (VERSION >= '2.2.0.0') {
                $this->user = new Cart\User($this->registry);
            } else {
                $this->user = new User($this->registry);
            }

            if (!$this->user->isLogged()) { // loged as admin
                if ((isset($post_info['limit_access_user']) && $post_info['limit_access_user'])) {
                    //yes limit
                    if (!$this->customer->isLogged()) {
                        $this->postRestrict($post_id);
                        return;
                    } else {
                        //user is logged find in allowed
                        $allowed_users = explode(',', $post_info['limit_users']);
                        if (!in_array($this->customer->getId(), $allowed_users)) {
                            $this->postRestrict($post_id);
                            return;
                        }
                    }
                }
                if (isset($post_info['limit_access_user_group']) && $post_info['limit_access_user_group']) {
                    if (!$this->customer->isLogged()) {
                        $this->postRestrict($post_id);
                        return;
                    } else {
                        //user is logged find in allowed groups
                        $allowed_groups = explode(',', $post_info['limit_user_groups']);
                        if (!in_array($this->customer->getGroupId(), $allowed_groups)) {
                            $this->postRestrict($post_id);
                            return;
                        }
                    }
                }
            }

            $this->model_extension_d_blog_module_post->updateViewed($post_id);
            $url = '';
            $parent_category = array();

            $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
            if (file_exists(DIR_APPLICATION . 'view/javascript/jquery/datetimepicker/moment/moment.min.js')) {
                $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
            } elseif (file_exists(DIR_APPLICATION . 'view/javascript/jquery/datetimepicker/moment.js')) {
                $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
            } else {
                $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.min.js');
            }
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

            $styles = array(
                'd_blog_module/d_blog_module.css',
                'd_blog_module/bootstrap.css',
                'd_blog_module/theme/' . $this->setting['theme'] . '.css'
            );

            foreach ($styles as $style) {
                if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/' . $style)) {
                    $this->document->addStyle('catalog/view/theme/' . $this->theme . '/stylesheet/' . $style);
                } else {
                    $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $style);
                }
            }

            $scripts = array(
                'd_blog_module/main.js',
                'd_blog_module/post.js'
            );

            foreach ($scripts as $script) {
                if (file_exists(DIR_TEMPLATE . $this->theme . '/javascript/' . $script)) {
                    $this->document->addScript('catalog/view/theme/' . $this->theme . '/javascript/' . $script);
                } else {
                    $this->document->addScript('catalog/view/theme/default/javascript/' . $script);
                }
            }

            if ($this->user->isLogged()) {
                $data['user'] = true;
            } else {
                $data['user'] = false;
            }

            $this->load->language('product/category');

            $data['heading_title'] = $post_info['title'];
            $data['post_id'] = (int)$post_id;
            $data['setting'] = $this->setting;

            $author = $this->model_extension_d_blog_module_author->getAuthor($post_info['user_id']);
            $data['author'] = (!empty($author['name'])) ? $author['name'] : $this->language->get('text_anonymous');
            $data['author_link'] = $this->url->link('extension/d_blog_module/author', 'author_id=' . $post_info['user_id'], 'SSL');

            if (isset($author['image'])) {
                $data['author_image'] = $this->model_tool_image->resize($author['image'], $this->setting['author']['image_width'], $this->setting['author']['image_height']);
            } else {
                $data['author_image'] = $this->model_tool_image->resize('placeholder.png', $this->setting['author']['image_width'], $this->setting['author']['image_height']);
            }

            $data['author_name'] = (isset($author['name'])) ? $author['name'] : '';
            $data['author_description'] = (isset($author['short_description'])) ? strip_tags(html_entity_decode($author['short_description'], ENT_QUOTES, 'UTF-8')) : '';

            $data['description'] = html_entity_decode($post_info['description'], ENT_QUOTES, 'UTF-8');
            $data['date_published'] = iconv(mb_detect_encoding(strftime($this->setting['post']['date_format'][$this->config->get('config_language_id')], strtotime($post_info['date_published']))), "utf-8//IGNORE", strftime($this->setting['post']['date_format'][$this->config->get('config_language_id')], strtotime($post_info['date_published'])));

            $data['date_modified'] = strftime($this->setting['post']['date_format'][$this->config->get('config_language_id')], strtotime($post_info['date_modified']));
            $data['date_published_link'] = $this->url->link('extension/d_blog_module/search', 'date_published=' . date("m", strtotime($post_info['date_published'])) . '-' . date("Y", strtotime($post_info['date_published'])), 'SSL');

            $data['date_published_utc'] = strftime($this->setting['utc_datetime_format'][$this->config->get('config_language_id')], strtotime($post_info['date_published']));
            $data['date_modified_utc'] = strftime($this->setting['utc_datetime_format'][$this->config->get('config_language_id')], strtotime($post_info['date_modified']));
            $data['custom_style'] = $this->setting['design']['custom_style'];

            $data['text_posted_by'] = $this->language->get('text_posted_by');
            $data['text_on'] = $this->language->get('text_on');
            $data['text_product_group_name'] = $this->language->get('text_product_group_name');
            $data['text_select'] = $this->language->get('text_select');
            $data['text_option'] = $this->language->get('text_option');
            $data['text_note'] = $this->language->get('text_note');
            $data['text_tags'] = $this->language->get('text_tags');
            $data['text_related'] = $this->language->get('text_related');
            $data['text_loading'] = $this->language->get('text_loading');

            $this->load->language('extension/d_blog_module/category');
            $data['text_views'] = $this->language->get('text_views');
            $data['text_review'] = $this->language->get('text_review');
            $data['text_read_more'] = $this->language->get('text_read_more');
            $data['button_continue'] = $this->language->get('button_continue');

            $data['tab_description'] = $this->language->get('tab_description');
            $data['tab_attribute'] = $this->language->get('tab_attribute');
            $data['tab_review'] = $this->language->get('tab_review');

            $data['entry_limit_access_user'] = $this->language->get('entry_limit_access_user');
            $data['entry_limit_access_user_group'] = $this->language->get('entry_limit_access_user_group');
            $data['entry_user'] = $this->language->get('entry_user');
            $data['entry_user_group'] = $this->language->get('entry_user_group`');
            $data['text_edit'] = $this->language->get('text_edit');
            $data['edit'] = false;
            $data['edit'] = false;
            if ($this->user->isLogged()) {
                if (VERSION >= '3.0.0.0') {
                    $data['edit'] = $this->config->get('config_url') . $this->setting['dir_admin'] . '/index.php?route=extension/d_blog_module/post/edit&post_id=' . $post_id . '&user_token=' . $this->session->data['user_token'];
                } else {
                    $data['edit'] = $this->config->get('config_url') . $this->setting['dir_admin'] . '/index.php?route=extension/d_blog_module/post/edit&post_id=' . $post_id . '&token=' . $this->session->data['token'];
                }
            }

            // Categories
            $categories = $this->model_extension_d_blog_module_category->getCategoryByPostId($post_id);
            $data['categories'] = array();
            foreach ($categories as $category) {
                $data['categories'][] = array(
                    'title' => $category['title'],
                    'href'  => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL')
                );
            }

            if (isset($categories[0])) {
                $parent_category = $categories[0];
            }

            //Videos
            $post_videos = $this->model_extension_d_blog_module_post->getPostVideos($post_id);
            $data['post_videos'] = array();
            foreach ($post_videos as $video) {
                $data['post_videos'][] = array(
                    'text' => $video['text'],
                    'code' => '<iframe frameborder="0" allowfullscreen src="' . str_replace("watch?v=", "embed/", $video['video']) . '" height="' . $video['height'] . '" width="100%" style="max-width:' . $video['width'] . 'px"></iframe>'
                );
            }

            if ($parent_category) {
                $parents = $this->model_extension_d_blog_module_category->getCategoryParents($parent_category['category_id']);
                foreach ($parents as $category) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category['title'],
                        'href' => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL')
                    );
                }
                $data['breadcrumbs'][] = array(
                    'text' => $parent_category['title'],
                    'href' => $this->url->link('extension/d_blog_module/category', 'category_id=' . $parent_category['category_id'] . $url, 'SSL')
                );
            }


            $data['breadcrumbs'][] = array(
                'text' => $post_info['title']
            );

            $data['tags'] = array();
            $tags = array();
            if (!empty($post_info['image_title'])) {
                $data['image_title'] = $post_info['image_title'];
            } else {
                $data['image_title'] = $data['heading_title'];
            }
            if (!empty($post_info['image_alt'])) {
                $data['image_alt'] = $post_info['image_alt'];
            } else {
                $data['image_alt'] = $data['heading_title'];
            }
            $data['image_alt'] = $post_info['image_alt'];
            $data['image_title'] = $post_info['image_title'];

            if ($post_info['tag']) {
                $tags = explode(',', $post_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'text' => trim($tag),
                        'href' => $this->url->link('extension/d_blog_module/search', 'tag=' . trim($tag), 'SSL')
                    );
                }
            }

            if ($post_info['image'] && $this->setting['post']['popup_display']) {
                $data['popup'] = $this->model_tool_image->resize($post_info['image'], $this->setting['post']['popup_width'], $this->setting['post']['popup_height']);
            } else {
                $data['popup'] = '';
            }

            if ($post_info['image'] && $this->setting['post']['image_display']) {
                if($PostType['type'] == 2)
                    $data['thumb'] = $this->model_tool_image->resize($post_info['image'], 770, 306);
                else 
                    $data['thumb'] = $this->model_tool_image->resize($post_info['image'], $this->setting['post']['image_width'], $this->setting['post']['image_height']);
            } else {
                $data['thumb'] = $this->model_tool_image->resize('placeholder.png', $this->setting['post']['image_width'], $this->setting['post']['image_height']);
            }

            $review_total_info = $this->model_extension_d_blog_module_review->getTotalReviewsByPostId($post_id);
            $data['rating'] = (int)$review_total_info['rating'];

            if (isset($this->request->get['format'])) {
                $format = $this->request->get['format'];
                if ($this->format($format, $data)) {
                    return false;
                }
            }

            if ($post_info['review_display'] == 1) {
                $data['review_display'] = true;
            } elseif ($post_info['review_display'] == 2) {
                $data['review_display'] = false;
            } else {
                $data['review_display'] = $this->setting['post']['review_display'];
            }
            $data['review'] = $this->load->controller('extension/d_blog_module/review');

            //next and prev posts
            $nav_category_id = 0;
            if ($this->setting['post']['nav_same_category'] && $parent_category) {
                $nav_category_id = $parent_category['category_id'];
            }
            $next_post_info = $this->model_extension_d_blog_module_post->getNextPost($post_id, $nav_category_id);
            $prev_post_info = $this->model_extension_d_blog_module_post->getPrevPost($post_id, $nav_category_id);

            $data['next_post'] = array();
            if ($next_post_info) {
                $data['next_post'] = array(
                    'text'              => $next_post_info['title'],
                    'href'              => $this->url->link('extension/d_blog_module/post', 'post_id=' . $next_post_info['post_id'] . $url, 'SSL'),
                    'short_description' => utf8_substr(strip_tags(html_entity_decode($next_post_info['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']) . '...',
                    'thumb'             => ($next_post_info['image']) ? $this->model_tool_image->resize($next_post_info['image'], $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']) : '',
                );
            }

            $data['prev_post'] = array();
            if ($prev_post_info) {
                $data['prev_post'] = array(
                    'text'              => $prev_post_info['title'],
                    'href'              => $this->url->link('extension/d_blog_module/post', 'post_id=' . $prev_post_info['post_id'] . $url, 'SSL'),
                    'short_description' => utf8_substr(strip_tags(html_entity_decode($prev_post_info['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']) . '...',
                    'thumb'             => ($prev_post_info['image']) ? $this->model_tool_image->resize($prev_post_info['image'], $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']) : '',
                );
            }


            //metas
            $this->document->setTitle($post_info['meta_title']);
            $this->document->setDescription($post_info['meta_description']);
            $this->document->setKeywords($post_info['meta_keyword']);
            $this->document->addLink($this->url->link('extension/d_blog_module/post', 'post_id=' . $post_id, 'SSL'), 'canonical');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            
                //logic to load post detail page:- change template layout based on post type or post parent category

                if($PostType['type'] == 2) {//news
                    $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/d_blog_module/post_news', $data));
                } elseif($PostType['type'] == 1) {//recepie
                    $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/d_blog_module/post', $data));
                } else {
                    $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/d_blog_module/post', $data));
                }
            

        } else {
            $url = '';

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('extension/d_blog_module/post', $url . '&post_id=' . $post_id, 'SSL')
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

public function type(){
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL')
        );
        if (isset($this->request->get['type'])) {
            $Gettype = $this->request->get['type'];
            if($Gettype){
                $data['post_type'] = $Gettype;
            }
        }
        $main_cat_id = '';
        $data['submit_recipe'] = $this->url->link('information/information', 'information_id=10', true);
        $limit = 20;       
        $page_title = 'Our Blog';            
        if($Gettype == 'recipes'){
            $type = 1;
            $name = 'Recipes';
            $main_cat_id = 10;           
            $page_title = 'Recipes - Stoneledge Farm - Leeds, NY';
        }else if ($Gettype == 'news'){
            $type = 2;
            $name = 'News';
            $main_cat_id = 11;
            $page_title = 'News - Stoneledge Farm - Leeds, NY';    
            $limit = 6;      
        }else{
            $type = 0;
            $name = 'Our Blog';
        }
        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
        } elseif (isset($this->request->get['search'])) {
            $tag = $this->request->get['search'];
        } else {
            $tag = '';
        }
        if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
        } else {
            $description = '';
        }
        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
            $open_cat_name = $this->model_extension_d_blog_module_category->getCategory($category_id);
            if($open_cat_name)
                $name = $open_cat_name['title'];
        } else {
            $category_id = 0;
        }
                $data['category_id'] = $category_id;
                //cateogry image
        if (!empty($open_cat_name['image'])) {
            $open_cat_name['image_width'] = (!empty($open_cat_name['image_width'])) ? $open_cat_name['image_width'] : $this->setting['category']['image_width'];
            $open_cat_name['image_height'] = (!empty($open_cat_name['image_height'])) ? $open_cat_name['image_height'] : $this->setting['category']['image_height'];    
            $data['thumb'] = $this->model_tool_image->resize($open_cat_name['image'], $open_cat_name['image_width'], $open_cat_name['image_height']);
        } else {
            $data['thumb'] = '';
        }
        if (isset($this->request->get['sub_category'])) {
            $sub_category = $this->request->get['sub_category'];
        } else {
            $sub_category = '';
        }
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.title';
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }
        $url = '';
        if (isset($this->request->get['tag'])) {
            $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['description'])) {
            $url .= '&description=' . $this->request->get['description'];
        }
        if (isset($this->request->get['category_id'])) {
            $url .= '&category_id=' . $this->request->get['category_id'];
        }
        if (isset($this->request->get['sub_category'])) {
            $url .= '&sub_category=' . $this->request->get['sub_category'];
        }
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }
        $data['heading_title'] = $name;
        $this->document->setTitle($page_title);
        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = $limit;
        }
        $filter = array(
        'filter_post_type' => $type,
        'filter_tag'          => $tag,
        'filter_description'  => $description,
        'filter_category_id'  => $category_id,
        'filter_sub_category' => $sub_category,
        'sort'                => $sort,
        'order'               => $order,
        'start'               => ($page - 1) * $limit,
        'limit'               => $limit
        );
        $post_total = $this->model_extension_d_blog_module_post->getTypeTotalPosts($filter);
        $pagination = new Pagination();
        $pagination->total = $post_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('extension/d_blog_module/post/type&type='.$Gettype, '&page={page}');
        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($post_total - $limit)) ? $post_total : ((($page - 1) * $limit) + $limit), $post_total, ceil($post_total / $limit));
        //echo $page;
        // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
        if ($page == 1) {
          $this->document->addLink($this->url->link('extension/d_blog_module/post/type&type='.$Gettype, '', true), 'canonical');
        } else {
          $this->document->addLink($this->url->link('extension/d_blog_module/post/type&type='.$Gettype, 'page='. $page , true), 'canonical');
        }
        if ($page > 1) {
          $this->document->addLink($this->url->link('extension/d_blog_module/post/type&type='.$Gettype, (($page - 2) ? '&page='. ($page - 1) : ''), true), 'prev');
        }
        if ($limit && ceil($post_total / $limit) > $page) {
          $this->document->addLink($this->url->link('extension/d_blog_module/post/type&type='.$Gettype, 'page='. ($page + 1), true), 'next');
        }
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;
        $posts = $this->model_extension_d_blog_module_post->gettypePosts($filter);
        $data['posts'] = array();
        foreach ($posts as $post) {
            $data['posts'][] = $this->load->controller('extension/d_blog_module/post/thumb', $post['post_id']);
        }
        foreach ($data['posts'] as $post) {
            if ($post) {
                $category_recipe_info = array();
                $post_categories = $this->model_extension_d_blog_module_category->getCategoryByPostId($post['post_id']);
                foreach ($post_categories as $category_recipe) {
                    $category_recipe_info[] = array(
                        'category_id' => $category_recipe['category_id'],
                        'title' => $category_recipe['title'],
                        'href'  => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category_recipe['category_id'], 'SSL')
                    );
                }
                
                if (isset($category_recipe_info[0])) {
                    $parent_category = $category_recipe_info[0];
                }
                if ($parent_category) {
                    $parents = $this->model_extension_d_blog_module_category->getCategoryParents($parent_category['category_id']);
                    foreach ($parents as $category) {
                        $category_recipe_info[] = array(
                            'title' => $category['title'],
                            'href' => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL')
                        );
                    }                                
                }                
                
                $data['post'][] = array(
                'text'              => $post['title'],
                'categories'        => $category_recipe_info,
                'href'              => $this->url->link('extension/d_blog_module/post', 'post_id=' . $post['post_id']),
                'short_description' => utf8_substr(strip_tags(html_entity_decode($post['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']) . '...',
                'description' => utf8_substr(strip_tags(html_entity_decode($post['description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']) . '...',
                'thumb'             => $post['thumb'],
            );
            }
        }
        /*$main_categories = $this->model_extension_d_blog_module_category->getCategories();
        //print_r($main_categories);
        $mn_Cat = '';
        foreach($main_categories as $m_Cat){
            if(strpos($name, $m_Cat['meta_title']) !== false || strpos($name, $m_Cat['title'])!== false ){
                $mn_Cat = $m_Cat['category_id'];
            }
        }*/
        //categories
        $data['categories'] = array();
        $categories = $this->model_extension_d_blog_module_category->getCategories($category_id);	
                        
	$main_categories = $this->model_extension_d_blog_module_category->getCategoryParents($category_id);
        $current_category_parents_count =  count($main_categories);        
	$data['second_level_title'] = ''; 
        $data['second_level_id'] = '';          
        $main_cat_index = 0;    
	foreach($main_categories as $m_Cat){                      
            if($main_cat_index == 1) {
               $data['second_level_title'] = $m_Cat['title'];
               $data['second_level_id'] = $m_Cat['category_id'];    
            }
           $main_cat_index++;
        }                
                
        $this->load->model('tool/image');
               
            $sub_Cats = $this->model_extension_d_blog_module_category->getCategories($main_cat_id);//fetch 2nd level recipe categoreis
            $data['sub_Cat'] = array();
            if ($sub_Cats) {
                foreach ($sub_Cats as $sub_Cat) {
                
                    if( $data['second_level_id'] == $sub_Cat['category_id']) {//show 2nd level image on 3rd level category
			$data['second_level_img'] = $this->model_tool_image->resize($sub_Cat['image'], 50, 50);
                    }                
                
                    $data['sub_Cat'][] = array(
                        'category_id' => $sub_Cat['category_id'],
                        'image' => $this->model_tool_image->resize($sub_Cat['image'], 50, 50),
                        'title' => $sub_Cat['title'],
                        'href'  => $this->url->link('extension/d_blog_module/post/type&type=recipes', 'category_id=' . $sub_Cat['category_id'], 'SSL')
                    );
                }
            }
                
         //on recepie 3rd level categories we always have to show siblings
        if($data['second_level_id']) {       
            $categories = $this->model_extension_d_blog_module_category->getCategories($data['second_level_id']);     
        }
                     
        //Latest
      $this->load->model('catalog/product');
      $data['latestproducts'] = array();
      $results = $this->model_catalog_product->getLatestProducts(8);
      foreach ($results as $result) {
          if ($result['image']) {
              $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
          } else {
              $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
          }
          if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
              $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          } else {
              $price = false;
          }
          if ((float)$result['special']) {
              $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          } else {
              $special = false;
          }
          if ($this->config->get('config_tax')) {
              $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
          } else {
              $tax = false;
          }
          if ($this->config->get('config_review_status')) {
              $rating = (int)$result['rating'];
          } else {
              $rating = false;
          }
          $data['latestproducts'][] = array(
              'product_id'  => $result['product_id'],
              'thumb'       => $image,
              'name'        => $result['name'],
              'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
              'price'       => $price,
              'special'     => $special,
              'tax'         => $tax,
              'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
              'rating'      => $result['rating'],
              'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'])
          );
      }
      //Recipe and News
      $this->load->model('extension/d_blog_module/post');
      $this->load->model('extension/d_blog_module/category');
      $data['recipe_post'] = array();
      $data['recipe_posts'] = array();
      $data['recipes_link'] = $this->url->link('extension/d_blog_module/post/type&type=recipes');
      $data['news_link'] = $this->url->link('extension/d_blog_module/post/type&type=news');
      $recipe_filter = array('filter_post_type' => '1');
      /*$recipe_posts = $this->model_extension_d_blog_module_post->gettypePosts($recipe_filter);
      foreach ($recipe_posts as $recipe_post) {
          $data['recipe_posts'][] = $this->load->controller('extension/d_blog_module/post/thumb', $recipe_post['post_id']);
      }
      foreach ($data['recipe_posts'] as $recipe_loop) {
          if ($recipe_loop) {
              $category_recipe_info = array();
              $post_categories = $this->model_extension_d_blog_module_category->getCategoryByPostId($recipe_loop['post_id']);
              foreach ($post_categories as $category_recipe) {
                  $category_recipe_info[] = array(
                      'title' => $category_recipe['title'],
                      'href'  => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category_recipe['category_id'], 'SSL')
                  );
              }
              //print_r($recipe_loop);
              $data['recipe_post'][] = array(
              'text'              => $recipe_loop['title'],
              'categories'        => $category_recipe_info,
              'href'              => $this->url->link('extension/d_blog_module/post', 'post_id=' . $recipe_loop['post_id']),
              'short_description' => utf8_substr(strip_tags(html_entity_decode($recipe_loop['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']) . '...',
              'thumb'             => $recipe_loop['thumb'],
          );
          }
      }*/
      $news_filter = array('filter_post_type' => '2');
          $news_posts = $this->model_extension_d_blog_module_post->gettypePosts($news_filter);
          foreach ($news_posts as $news_post) {
              $data['news_posts'][] = $this->load->controller('extension/d_blog_module/post/thumb', $news_post['post_id']);
          }
          foreach ($data['news_posts'] as $news_loop) {
              if ($news_loop) {
                  //print_r($recipe_loop);
                  $data['news_post'][] = array(
                  'text'              => $news_loop['title'],
                  'href'              => $this->url->link('extension/d_blog_module/post', 'post_id=' . $news_loop['post_id']),
                  'short_description' => utf8_substr(strip_tags(html_entity_decode($news_loop['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']) . '...',
                  'thumb'             => $news_loop['thumb'],
              );
              }
          }
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
                if($Gettype == 'news') {//news
                    $news_categories = $this->model_extension_d_blog_module_category->getCategories(11);//show category dropdown on top
                    foreach($news_categories as $news_category) {
                        $data['news_categories'][] = array(
                            'category_id' => $news_category['category_id'],
                            'title' => $news_category['title'],
                            'href' => $this->url->link('extension/d_blog_module/category', 'category_id=' . $news_category['category_id'], 'SSL')
                        );
                    }
                    $data['all_new_link'] = $this->url->link('extension/d_blog_module/post/type&type=news', '', 'SSL');                
                    $this->response->setOutput($this->load->view('extension/d_blog_module/post_type_news', $data));
                } elseif($Gettype == 'recipes') {//recepie                
                    foreach ($categories as $category) {
                        $filter_data = array('filter_category_id' => $category['category_id'], 'filter_sub_category' => true);

                        if ($category['image']) {
                            $thumb = $this->model_tool_image->resize($category['image'], $this->setting['category']['sub_category_image_width'], $this->setting['category']['sub_category_image_height']);
                        } else {
                            $thumb = $this->model_tool_image->resize('placeholder.png', $this->setting['category']['sub_category_image_width'], $this->setting['category']['sub_category_image_height']);
                        }

                        $data['categories'][] = array(
                            'thumb' => $thumb,

                       'category_id' => $category['category_id'],

                            'title' => $category['title'] . ($this->setting['category']['sub_category_post_count'] ? ' (' . $this->model_extension_d_blog_module_post->getTotalPostsByCategoryId($category['category_id']) . ')' : ''),
                            'href'  => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL'),
                            'col'   => ($this->setting['category']['sub_category_col']) ? round(12 / $this->setting['category']['sub_category_col']) : 12
                        );
                    }                
                
                    $this->response->setOutput($this->load->view('extension/d_blog_module/post_type', $data));
                } else {
                    $this->response->setOutput($this->load->view('extension/d_blog_module/post_type', $data));
                }
        
    }
    public function postRestrict($post_id)
    {
        $url = '';
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_error'),
            'href' => $this->url->link('extension/d_blog_module/post', $url . '&post_id=' . $post_id, 'SSL')
        );

        $this->document->setTitle($this->language->get('text_error'));

        $data['heading_title'] = $this->language->get('text_restricted_access');

        $data['text_error'] = $this->language->get('text_contact_admin');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $this->response->setOutput($this->load->view('error/not_found', $data));
    }

    public function format($format, $json)
    {
        if ($format == 'json') {
            $this->response->addHeader('Content-Type: application/json');

            if (isset($this->request->get['callback'])) {
                $this->response->setOutput($this->request->get['callback'] . '(' . json_encode($json) . ');');
            } else {
                $this->response->setOutput(json_encode($json));
            }

            return true;
        }

        return false;
    }

    public function thumb($post_id)
    {
        if ($post_id) {

            $data['setting'] = $this->setting;

            $post = $this->model_extension_d_blog_module_post->getPost($post_id);

            if ($post) {
                $url = '';

                if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_blog_module/d_blog_module.css')) {
                    $this->document->addStyle('catalog/view/theme/' . $this->theme . '/stylesheet/d_blog_module/d_blog_module.css');
                } else {
                    $this->document->addStyle('catalog/view/theme/default/stylesheet/d_blog_module/d_blog_module.css');
                }

                $data['text_categories'] = $this->language->get('text_categories');
                $data['text_tags'] = $this->language->get('text_tags');
                $data['text_empty'] = $this->language->get('text_empty');
                $data['text_views'] = $this->language->get('text_views');
                $data['text_review'] = $this->language->get('text_review');
                $data['text_read_more'] = $this->language->get('text_read_more');
                $data['button_continue'] = $this->language->get('button_continue');

                if ($post['image']) {
                    $image = $this->model_tool_image->resize($post['image'], $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']);
                }
                $category_info = array();
                $post_categories = $this->model_extension_d_blog_module_category->getCategoryByPostId($post_id);

                foreach ($post_categories as $category) {
                    $category_info[] = array(
                        'title' => $category['title'],

                        'category_id' => $category['category_id'],                
             
                        'href'  => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category['category_id'], 'SSL')
                    );
                }



            if (isset($category_info[0])) {
                $parent_category = $category_info[0];
            }
            if ($parent_category) {
                $parents = $this->model_extension_d_blog_module_category->getCategoryParents($parent_category['category_id']);
                foreach ($parents as $category) {
                    $category_info[] = array(
                        'title' => $category['title'],
                        'href' => $this->url->link('extension/d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL')
                    );
                }                                
            }                
                                
            
                $rating = (isset($post['rating'])) ? $post['rating'] : FALSE;

                $tags = explode(',', $post['tag']);
                $data['tags'] = array();
                foreach ($tags as $tag) {
                    if ($tag) {
                        $data['tags'][] = array(
                            'text' => trim($tag),
                            'href' => $this->url->link('extension/d_blog_module/search', 'tag=' . trim($tag), 'SSL')
                        );
                    }
                }

                $data['post_id'] = $post_id;
                $data['thumb'] = $image;
                $data['title'] = utf8_substr($post['title'], 0, $this->setting['post_thumb']['title_length']);
                $data['categories'] = $category_info;
                $data['short_description'] = $this->setting['post']['style_short_description_display'] ? html_entity_decode($post['short_description'], ENT_QUOTES, 'UTF-8') : utf8_substr(strip_tags(html_entity_decode($post['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post_thumb']['short_description_length']) . '...';
                $data['description'] = utf8_substr(strip_tags(html_entity_decode($post['description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post_thumb']['description_length']) . '...';
                $data['rating'] = $rating;

                $author = $this->model_extension_d_blog_module_author->getAuthor($post['user_id']);
                $data['author'] = (!empty($author['name'])) ? $author['name'] : $this->language->get('text_anonymous');
                $data['author_link'] = $this->url->link('extension/d_blog_module/author', 'author_id=' . $post['user_id'], 'SSL');

                if ((isset($post['limit_access_user']) && $post['limit_access_user'])) {
                    //yes limit
                    if (!$this->customer->isLogged()) {
                        $this->postRestrictLabel($post_id, 'user');
                    } else {
                        //user is logged find in allowed
                        $allowed_users = explode(',', $post['limit_users']);
                        if (!in_array($this->customer->getId(), $allowed_users)) {
                            $this->postRestrictLabel($post_id, 'user');
                        }
                    }
                }
                if (isset($post['limit_access_user_group']) && $post['limit_access_user_group']) {
                    if (!$this->customer->isLogged()) {
                        $this->postRestrictLabel($post_id, 'group');
                    } else {
                        //user is logged find in allowed groups
                        $allowed_groups = explode(',', $post['limit_user_groups']);
                        if (!in_array($this->customer->getGroupId(), $allowed_groups)) {
                            $this->postRestrictLabel($post_id, 'group');
                        }
                    }
                }
				$data['type'] = $post['type'];
                $data['views'] = $post['viewed'];
                $data['review'] = $post['review'];
                $data['image_title'] = (!empty($post['image_title'])) ? $post['image_title'] : $data['title'];
                $data['image_alt'] = (!empty($post['image_alt'])) ? $post['image_title'] : $data['title'];
                $data['date_published'] = iconv(mb_detect_encoding(strftime($this->setting['post_thumb']['date_format'][$this->config->get('config_language_id')], strtotime($post['date_published']))), "utf-8//IGNORE", strftime($this->setting['post_thumb']['date_format'][$this->config->get('config_language_id')], strtotime($post['date_published'])));

                $data['date_published_short'] = strftime($this->language->get('date_format_short'), strtotime($post['date_published']));
                $data['date_published_day'] = strftime($this->setting['post_thumb']['date_format_day'], strtotime($post['date_published']));
                $data['date_published_month'] = strftime($this->setting['post_thumb']['date_format_month'], strtotime($post['date_published']));
                $data['date_published_year'] = strftime($this->setting['post_thumb']['date_format_year'], strtotime($post['date_published']));
                $data['href'] = $this->url->link('extension/d_blog_module/post', 'post_id=' . $post_id, 'SSL');

                return $data;
            } else {

                return false;
            }
        }
    }

    public function postRestrictLabel($post_id, $group = 'user')
    {
        $data['restrict_access'] = true;
        if (VERSION > '3') {
            $this->load->model('account/customer_group');
            $customer_group = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());
            if (isset($customer_group['name'])) {
                $data['restrict_access_label'] = sprintf($this->language->get('restrict_access_label_' . $group), $customer_group['name']);
            }
        } else {
            $data['restrict_access_label'] = $this->language->get('restrict_access_label_' . $group);

        }

    }

    public function savePost($setting)
    {
        $result = false;
        $this->load->model('extension/d_opencart_patch/user');

        if (!empty($setting['content']['post_description']) && !empty($setting['id'])) {
            $this->model_extension_d_blog_module_post->editPost($setting['id'], $setting['content']);
            $result = true;
        }

        return $result;
    }

    public function editPost()
    {
        $json = array();

        if (!empty($this->request->post['description'])) {
            $description = $this->request->post['description'];
        }

        if (!empty($this->request->get['id'])) {
            $post_id = $this->request->get['id'];
        }

        if (isset($description) && isset($post_id)) {

            $this->model_extension_d_blog_module_post->editPost($post_id, array('description' => $description));

            $json['success'] = 'success';
        } else {
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
