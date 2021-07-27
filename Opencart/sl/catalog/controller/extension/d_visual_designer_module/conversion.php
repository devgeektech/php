<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleConversion extends Controller {
    private $codename = 'conversion';
    private $route = 'extension/d_visual_designer_module/conversion';

    public function __construct($registry) {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/d_opencart_patch/user');
    }
    public function index($setting){

        $conversion_info = $this->{'model_extension_d_visual_designer_module_'.$this->codename}->getConversions($setting['description_id']);

        $data['view'] = !empty($conversion_info['view'])?$conversion_info['view']:0;
        $data['conversion'] = !empty($conversion_info[$setting['type_conversion']])?$conversion_info[$setting['type_conversion']]:0;

        if($data['view'] > 0){
            $data['conversion_percentage'] = round($data['conversion']/$data['view']*100, 1);
        }
        else{
            $data['conversion_percentage'] = 0;
        }

        if (!$this->model_extension_d_opencart_patch_user->isLogged()) {
            $data['permission'] = false;
        }
        else{
            $data['permission'] = true;
        }

        if($data['view'] > 0){
            $data['conversion_percentage'] = round($data['conversion']/$data['view']*100, 1);
        }
        else{
            $data['conversion_percentage'] = 0;
        }

        $data['selector_link'] = html_entity_decode($setting['selector_link'], ENT_QUOTES, 'UTF-8');

        return $data;
    }
    
    public function local($permission) {
        $data['text_viewed'] = $this->language->get('text_viewed');
        $data['text_conversions'] = $this->language->get('text_conversions');
        $data['text_conversion_percentage'] = $this->language->get('text_conversion_percentage');

        return $data;
    }

    public function scripts($permission){
        $data = array();

        $data[] = 'catalog/view/javascript/d_visual_designer_landing/conversion.js';

        return $data;
    }

    public function styles($permission) {
        $data = array();

        if($permission){
            $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer_landing/conversion/conversion.css';
        }

        return $data;
    }

    public function addConversion(){
        $json = array();
        if(!empty($this->request->post['type'])){
            $type = $this->request->post['type'];
        }

        if(!empty($this->request->post['description_id'])){
            $description_id = $this->request->post['description_id'];
        }

        if(!empty($description_id) && !empty($type)){
            $this->{'model_extension_d_visual_designer_module_'.$this->codename}->addConversion($description_id, $type);
            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addConversionBuy(){
        $json = array();

        if(!empty($this->request->post['description_id'])){
            $description_id = $this->request->post['description_id'];
        }

        if(!empty($this->request->post['product_id'])){
            $product_id = $this->request->post['product_id'];
        }

        if(isset($product_id) && isset($description_id)){
            if(!isset($this->session->data['d_visual_designer_landing'])){
                $this->session->data['d_visual_designer_landing'] = array();
            }
            $this->session->data['d_visual_designer_landing'][$product_id] = $description_id;
            
            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}