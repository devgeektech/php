<?php
/*
*location: admin/controller
*/

class ControllerExtensionDVisualDesignerModuleFeedback extends Controller
{
    private $codename = 'feedback';
    private $route = 'extension/d_visual_designer_module/feedback';

    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
    }

    public function index($setting)
    {
        if (!empty($setting['code'])) {
            $data['code'] = html_entity_decode($setting['code'], ENT_QUOTES, 'UTF-8');
        } else {
            $data['code'] = '';
        }

        return $data;
    }

    public function local($permission)
    {
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_phone'] = $this->language->get('entry_phone');
        $data['entry_comment'] = $this->language->get('entry_comment');

        $data['button_send'] = $this->language->get('button_send');

        $data['text_success_send'] = $this->language->get('text_success_send');
        $data['text_click_download'] = $this->language->get('text_click_download');

        return $data;
    }

    public function options($permission)
    {
        $data = array();

        $data['language_id'] = $this->config->get('config_language_id');

        return $data;
    }

    public function send()
    {
        $json = array();

        if (!empty($this->request->post['email']) && (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL) === false)) {
            $email = $this->request->post['email'];
        } else {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (!empty($this->request->post['comment']) && (strlen($this->request->post['comment']) > 10 & strlen($this->request->post['comment']) < 3000)) {
            $comment = $this->request->post['comment'];
        } else {
            $this->error['comment'] = $this->language->get('error_comment');
        }

        if (!empty($this->request->post['name']) && (strlen($this->request->post['name']) > 3 & strlen($this->request->post['name']) < 32)) {
            $name = $this->request->post['name'];
        } else {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (isset($this->request->post['fields'])) {
            $fields = $this->request->post['fields'];
        } else {
            $fields = array();
        }

        if (isset($this->request->post['phone'])) {
            $phone = $this->request->post['phone'];
        }

        if (!$this->error) {
            $message = '';

            $message .= "<p>" . $this->language->get('entry_name') . " - " . $name . "</p>";

            if (!empty($fields)) {
                foreach ($fields as $field) {
                    $message .= "<p>" . $field['name'] . " - " . $field['value'] . "</p>";
                }
            }

            $message .= "<p>" . $this->language->get('entry_comment') . " - " . $comment . "</p>";

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->request->post['email']);
            $mail->setSender(html_entity_decode($name, ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $name), ENT_QUOTES, 'UTF-8'));
            $mail->setHTML($message);
            $mail->send();
            if (isset($this->request->post['link'])) {
                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                $mail->setTo($this->request->post['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
                $mail->setSubject(html_entity_decode($this->language->get('email_subject_link'), ENT_QUOTES, 'UTF-8'));
                $mail->setHTML($this->request->post['link']);
                $mail->send();
            }
            $json['success'] = 'success';
        } else {
            $json['errors'] = $this->error;
            $json['error'] = 'error';
        }

        $this->response->setOutput(json_encode($json));
    }
}
