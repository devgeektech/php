<?php
class ControllerExtensionEventDVisualDesignerLanding extends Controller {
    private $codename = 'd_visual_designer';

    private $route = 'extension/module/d_visual_designer';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->model($this->route);
    }

    public function view_variation_before(&$view, &$data, &$output)
    {
        if(isset($data['description'])){
            $designer_data = array(
                'config' => 'd_visual_designer_landing',
                'content' => $data['description'],
                'header' => &$data['header'],
                'field_name' => 'description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => $data['variation_id']
                );
            $data['description'] = $this->load->controller('extension/'.$this->codename.'/designer', $designer_data);
            
            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8');
        }
    }

    public function eventSuccess(){
        if(!empty($this->session->data['order_id'])){
            $products = $this->cart->getProducts();
            foreach ($products as $product) {
                if(!empty($this->session->data['d_visual_designer_landing'][$product['product_id']])){
                    $description_id = $this->session->data['d_visual_designer_landing'][$product['product_id']];

                    $this->load->model('extension/d_visual_designer_module/conversion');
                    $this->model_extension_d_visual_designer_module_conversion->addConversion($description_id, 'buy');
                    break;
                }
            }
        }
        unset($this->session->data['d_visual_designer_landing']);
    }

}
