<?php
/*
 *    location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleFile extends Controller
{
    private $codename = 'file';
    private $route = 'extension/d_visual_designer_module/file';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/d_opencart_patch/url');
    }

    public function setting($setting)
    {
        $data['filename'] = '';

        if (!empty($setting['download_id'])) {
            $download_info = $this->model_extension_d_visual_designer_module_file->getDownload($setting['download_id']);
            if (!empty($download_info)) {
                $data['filename'] = $download_info['mask'];
            }
        }

        return $data;
    }

    public function local()
    {
        $data['entry_filename'] = $this->language->get('entry_filename');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_icon_library'] = $this->language->get('entry_icon_library');
        $data['entry_icon'] = $this->language->get('entry_icon');
        $data['entry_icon_size'] = $this->language->get('entry_icon_size');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['text_loading'] = $this->language->get('text_loading');

        return $data;
    }

    public function options()
    {
        $data['upload_url'] = $this->model_extension_d_opencart_patch_url->ajax($this->route . '/upload');
        return $data;
    }

    public function upload()
    {
        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', $this->route)) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

                // Validate the filename length
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file extension types
                $allowed = array();

                $extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

                $filetypes = explode("\n", $extension_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Allowed file mime types
                $allowed = array();

                $mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

                $filetypes = explode("\n", $mime_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            $file = $filename . '.' . token(32);

            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

            $json['filename'] = $filename;

            $newData = array(
                'filename' => $file,
                'mask' => $filename
            );

            $json['download_id'] = $this->model_extension_d_visual_designer_module_file->addDownload($newData);

            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
