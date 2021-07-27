<?php
/*
 *	location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleMailerLite extends Controller
{
     /**
     * module codename - keep it simple yet unique. add prefix
     */
     private $codename = 'mailerlite';

     private $route = 'extension/d_visual_designer_module/mailerlite';

    /**
     * share loaded language files and models with all methods
     */
    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/d_opencart_patch/user');
    }

    public function setting($setting)
    {

        $data['final_text'] = html_entity_decode($setting['final_text'], ENT_QUOTES, 'UTF-8');

        $data['lists'] = array();

        if (!empty($setting['api'])) {
            $mailer_client =  new d_mailerlite($setting['api']);

            $groups = $mailer_client->getGroups();

            if (!isset($groups["error"])) {
                foreach ($groups as $value) {
                    $data['lists'][$value['id']] = $value['name'];
                }
            }
        }

        return $data;
    }

    public function options()
    {
                
        $data['button_styles'] = array('primary', 'danger', 'warning', 'default', 'link', 'info', 'custom');

        $data['url_token'] = $this->model_extension_d_opencart_patch_user->getUrlToken();

        
        return $data;
    }

    public function local()
    {
        $data['entry_api'] = $this->language->get('entry_api');
        $data['entry_lists'] = $this->language->get('entry_lists');
        $data['entry_display_firstname'] = $this->language->get('entry_display_firstname');
        $data['entry_display_lastname'] = $this->language->get('entry_display_lastname');
        $data['entry_inline'] = $this->language->get('entry_inline');
        $data['entry_final_text'] = $this->language->get('entry_final_text');

        $data['entry_input_color_text'] = $this->language->get('entry_input_color_text');
        $data['entry_input_background_color'] = $this->language->get('entry_input_background_color');
        $data['entry_input_border_color'] = $this->language->get('entry_input_border_color');
        $data['entry_input_focus_border_color'] = $this->language->get('entry_input_focus_border_color');
        $data['entry_input_border_width'] = $this->language->get('entry_input_border_width');
        $data['entry_input_border_radius'] = $this->language->get('entry_input_border_radius');
        $data['entry_input_width'] = $this->language->get('entry_input_width');
        $data['entry_input_height'] = $this->language->get('entry_input_height');
        $data['entry_input_font_size'] = $this->language->get('entry_input_font_size');

        $data['entry_button_title'] = $this->language->get('entry_button_title');
        $data['entry_button_background_color'] = $this->language->get('entry_button_background_color');
        $data['entry_button_hover_background_color'] = $this->language->get('entry_button_hover_background_color');
        $data['entry_button_color_text'] = $this->language->get('entry_button_color_text');
        $data['entry_button_hover_color_text'] = $this->language->get('entry_button_hover_color_text');
        $data['entry_button_border_width'] = $this->language->get('entry_button_border_width');
        $data['entry_button_border_color'] = $this->language->get('entry_button_border_color');
        $data['entry_button_hover_border_color'] = $this->language->get('entry_button_hover_border_color');
        $data['entry_button_border_radius'] = $this->language->get('entry_button_border_radius');
        $data['entry_button_font_size'] = $this->language->get('entry_button_font_size');
        $data['entry_button_width'] = $this->language->get('entry_button_width');
        $data['entry_button_height'] = $this->language->get('entry_button_height');
        $data['entry_button_style'] = $this->language->get('entry_button_style');

        $data['help_api'] = $this->language->get('help_api');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');

        $data['button_refresh'] = $this->language->get('button_refresh');

        return $data;
    }

    public function getList()
    {
        $json = array();

        if (!empty($this->request->post['api'])) {
            $api = $this->request->post['api'];
        }

        if (isset($api)) {

            $mailer_client =  new d_mailerlite($api);

            $groups = $mailer_client->getGroups();

            $json['lists'] = array();

            if (!isset($groups["error"])) {
                foreach ($groups as $value) {
                    $json['lists'][$value['id']] = $value['name'];
                }
            }
            $json['success'] = 'success';
        } else {
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json, JSON_FORCE_OBJECT));
    }
}
