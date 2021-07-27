<?php

class ControllerExtensionModuleDBlogModuleTags extends Controller {
    private $id = 'd_blog_module_tags';
    private $route = 'extension/module/d_blog_module_tags';
    private $sub_versions = array('lite', 'light', 'free');
    private $mbooth = '';
    private $prefix = '';
    private $config_file = '';
    private $error = array(); 
    private $debug = false;
    private $setting = array();

    public function __construct($registry) {
        parent::__construct($registry);
        if(!isset($this->user)){
            if(VERSION >= '2.2.0.0'){
                $this->user = new Cart\User($registry);
            }else{
                $this->user = new User($registry);
            }
        }

        $this->load->model('extension/d_blog_module/category');
        $this->load->model('extension/module/d_blog_module_tags');
        $this->load->model('extension/module/d_blog_module');

        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);
        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id.'_setting', $this->config->get('config_store_id'),$this->config_file);

        $config_file = $this->model_extension_module_d_blog_module->getConfigFile('d_blog_module', $this->sub_versions);
        $d_blog_module_setting = $this->model_extension_module_d_blog_module->getConfigData('d_blog_module', 'd_blog_module_setting', $this->config->get('config_store_id'),$config_file);

        $this->setting = $this->setting + $d_blog_module_setting;
    }


    public function index() {
        if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_blog_module/d_blog_module.css')) {
            $this->document->addStyle('catalog/view/theme/'.$this->theme.'/stylesheet/d_blog_module/d_blog_module.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_blog_module/d_blog_module.css');
        }

        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_blog_module/theme/'.$this->setting['theme'].'.css');


        $setting = $this->config->get($this->id.'_setting');
        if(isset($setting[$this->config->get('config_language_id')]['name'])){
            $data['heading_title'] = $setting[$this->config->get('config_language_id')]['name'];
        }
        else{
            $data['heading_title'] = '';
        }
        
        $tags = $this->model_extension_module_d_blog_module_tags->getTags();

        $data['tags'] = array();


        foreach ($tags as $tag) {
            if($tag!='')
            {
                $data['tags'][] = array(
                    'title'       => $tag,
                    'count_post'  => $this->model_extension_module_d_blog_module_tags->TotalPostsByTag($tag),
                    'href'        => $this->url->link('extension/d_blog_module/search', 'tag=' . $tag, 'SSL')
                    );
            }
        }


        usort($data['tags'], array($this, "cmp"));

        $data['setting'] = $this->setting;

        $data['entry_qty'] = $this->language->get('entry_qty');
        $data['entry_author'] = $this->language->get('entry_author');
        $data['entry_reply_to'] = $this->language->get('entry_reply_to');
        $data['text_cancel'] = $this->language->get('text_cancel');

        $status = $this->config->get($this->id.'_status');

        if($status)
        {

            if(empty($data['tags'])) {
                return;
            } else {
                return $this->load->view('extension/module/d_blog_module_tags', $data);
            }
            
        }

    }
    private function cmp($a, $b) {
        if ($a['count_post'] == $b['count_post']) {
            return 0;
        }
        return ($a['count_post'] > $b['count_post']) ? -1 : 1;
    }



}
