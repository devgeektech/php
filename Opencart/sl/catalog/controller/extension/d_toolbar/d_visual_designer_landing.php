<?php
class ControllerExtensionDToolbarDVisualDesignerLanding extends Controller
{
    private $codename = 'd_visual_designer_landing';
    private $route = 'extension/d_toolbar/d_visual_designer_landing';
    private $config_file = 'd_visual_designer_landing';
    private $error = array();
    
    /*
    *	Functions for Toolbar.
    */
    public function toolbar_config($route)
    {
        $this->load->model($this->route);
        $this->load->model('extension/module/'.$this->codename);
        
        $data = array();
        if ($route == 'extension/module/d_visual_designer_landing') {

            if (isset($this->request->get['variation_id']) && !isset($this->request->get['page_id'])) {
                $variation_id = (int)$this->request->get['variation_id'];
                $page_info = $this->{'model_extension_module_'.$this->codename}->getPageByVariation($variation_id);
                if(!empty($page_info)){
                    $page_id = $page_info['page_id'];
                }
                else{
                    $page_id = 0;
                }
            } elseif (isset($this->request->get['page_id'])) {
                $page_id = (int)$this->request->get['page_id'];
            } else {
                $page_id = 0;
            }

            if ($page_id) {
                $data['route'] = 'vdl_page_id=' . $page_id;
                
                if (VERSION >= '3.0.0.0') {
                    $data['edit'] = $this->{'model_extension_d_toolbar_' . $this->codename}->link('extension/module/d_visual_designer_landing/edit', 'user_token=' . $this->session->data['user_token'] . '&page_id=' . $page_id, true);
                } else {
                    $data['edit'] = $this->{'model_extension_d_toolbar_' . $this->codename}->link('extension/module/d_visual_designer_landing/edit', 'token=' . $this->session->data['token'] . '&page_id=' . $page_id, true);
                }
            }
        }
        
        return $data;
    }
}
