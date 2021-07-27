<?php
/*
 *	location: admin/controller
 */

use \DrewM\MailChimp\MailChimp;

class ControllerExtensionDVisualDesignerModuleMailChimp extends Controller
{
    /**
    * module codename - keep it simple yet unique. add prefix
    */
    private $codename = 'mailchimp';

    private $route = 'extension/d_visual_designer_module/mailchimp';

    /**
     * share loaded language files and models with all methods
     */
    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }
    
    /**
     * returns the module block view
     */
    public function index($setting)
    {
        $data['final_text'] = html_entity_decode($setting['final_text'], ENT_QUOTES, 'UTF-8');

        return $data;
    }

    public function local($permission)
    {
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');


        $data['text_success_subscribe'] = $this->language->get('text_success_subscribe');

        $data['button_subscribe'] = $this->language->get('button_subscribe');

        return $data;
    }

    public function Subscribe()
    {
        $json = array();

        if (!empty($this->request->post['api'])) {
            $api = $this->request->post['api'];
        } else {
            $json['error'] = 'error';
        }
        if (!empty($this->request->post['list_id'])) {
            $list_id = $this->request->post['list_id'];
        } else {
            $json['error'] = 'error';
        }
        if (!empty($this->request->post['email'])) {
            $email = $this->request->post['email'];
        } else {
            $json['error'] = $this->language->get('error_email');
        }
        if (isset($this->request->post['firstname'])) {
            $firstname = $this->request->post['firstname'];
        } else {
            $json['error'] = 'error';
        }
        if (isset($this->request->post['lastname'])) {
            $lastname = $this->request->post['lastname'];
        } else {
            $json['error'] = 'error';
        }

        if (!isset($json['error'])) {
            include(DIR_SYSTEM.'library/MailChimp/MailChimp.php');

            $MailChimp = new MailChimp($api);
            
            $result = $MailChimp->post("lists/$list_id/members", array(
                'merge_fields' => array(
                    'FNAME' => $firstname,
                    'LNAME' => $lastname
                    ),
                'email_address' => $email,
                'status'        => 'subscribed',
                ));


            if (!empty($result['status']) && $result['status'] =='400') {
                $json['error'] = 'error1';
                if (!empty($result['title'])&& $result['title'] == 'Member Exists') {
                    $json['error'] = $this->language->get('error_subscribe_exists');
                }
                if (!empty($result['title'])&& $result['title'] == 'Invalid Resource') {
                    $json['error'] = $this->language->get('error_email_incorrect');
                }
            } else {
                $json['result'] = $result;
                $json['success'] = 'success';
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
