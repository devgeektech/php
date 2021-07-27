<?php
class ControllerExtensionDSEOModuleURLKeywordDVisualDesignerLanding extends Controller
{
    private $codename = 'd_visual_designer_landing';
    private $route = 'extension/d_seo_module_url_keyword/d_visual_designer_landing';
    private $config_file = 'd_visual_designer_landing';
    private $error = array();
    
    /*
    *	Functions for SEO Module URL Keyword.
    */
    public function edit_url_element($url_element_data)
    {
        $this->load->model($this->route);
        
        return $this->{'model_extension_d_seo_module_url_keyword_' . $this->codename}->editURLElement($url_element_data);
    }
    
    public function url_elements()
    {
        $this->load->language($this->route);
        $this->load->model($this->route);
        
        return $this->{'model_extension_d_seo_module_url_keyword_' . $this->codename}->getURLElements();
    }
    
    public function store_url_elements_links($store_url_elements)
    {
        $url_token = '';
        
        if (isset($this->session->data['token'])) {
            $url_token .= 'token=' . $this->session->data['token'];
        }
        
        if (isset($this->session->data['user_token'])) {
            $url_token .= 'user_token=' . $this->session->data['user_token'];
        }
        
        foreach ($store_url_elements as $store_id => $url_elements) {
            foreach ($url_elements as $url_element_key => $url_element) {
                if (strpos($url_element['route'], 'vdl_page_id') === 0) {
                    $route_arr = explode("vdl_page_id=", $url_element['route']);
                
                    if (isset($route_arr[1])) {
                        $page_id = $route_arr[1];
                        $store_url_elements[$store_id][$url_element_key]['link'] = $this->url->link('extension/module/d_visual_designer_landing/edit', $url_token . '&page_id=' . $page_id, true);
                    }
                }
            }
        }
        
        return $store_url_elements;
    }
}
