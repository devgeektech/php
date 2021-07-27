<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleConversion extends Controller
{
    private $codename = 'conversion';
    private $route = 'extension/d_visual_designer_module/conversion';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model($this->route);
    }
    
    public function index($setting)
    {
        $conversion_info = $this->{'model_extension_d_visual_designer_module_'.$this->codename}->getConversions($setting['description_id']);

        $data['view'] = !empty($conversion_info['view'])?$conversion_info['view']:0;
        $data['conversion'] = !empty($conversion_info[$setting['type_conversion']])?$conversion_info[$setting['type_conversion']]:0;
        if ($data['view'] > 0) {
            $data['conversion_percentage'] = round($data['conversion']/$data['view']*100, 1);
        } else {
            $data['conversion_percentage'] = 0;
        }
        return $data;
    }
    
    public function setting($setting)
    {
        $data['selector_link'] = html_entity_decode($setting['selector_link'], ENT_QUOTES, 'UTF-8');

        return $data;
    }

    public function options()
    {
        $data = array();
        $data['types'] = array(
            'all' => $this->language->get('text_type_all'),
            'cart' => $this->language->get('text_type_cart'),
            'buy' => $this->language->get('text_type_buy'),
            'subscription' => $this->language->get('text_type_subscription'),
            'feedback' => $this->language->get('text_type_feedback'),
            'link' => $this->language->get('text_type_link')
            );

        $this->load->model('extension/d_opencart_patch/user');
        $data['token'] = $this->{'model_extension_d_opencart_patch_user'}->getUrlToken();

        return $data;
    }

    public function local()
    {
        $data = array();

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_selector_link'] = $this->language->get('entry_selector_link');


        $data['text_viewed'] = $this->language->get('text_viewed');
        $data['text_conversions'] = $this->language->get('text_conversions');
        $data['text_conversion_percentage'] = $this->language->get('text_conversion_percentage');

        return $data;
    }
}
