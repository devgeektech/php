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
    }

    public function options()
    {
        $data['download_url'] = str_replace('&amp;', '&', $this->url->link($this->route . '/download'));
        return $data;
    }

    public function download()
    {
        if (isset($this->request->get['download_id'])) {
            $download_id = $this->request->get['download_id'];
        } else {
            $download_id = 0;
        }

        $download_info = $this->model_extension_d_visual_designer_module_file->getDownload($download_id);

        if ($download_info) {
            $file = DIR_DOWNLOAD . $download_info['filename'];
            $mask = basename($download_info['mask']);

            if (!headers_sent()) {
                if (file_exists($file)) {
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));

                    if (ob_get_level()) {
                        ob_end_clean();
                    }

                    readfile($file, 'rb');

                    exit();
                } else {
                    exit('Error: Could not find file ' . $file . '!');
                }
            } else {
                exit('Error: Headers already sent out!');
            }
        } else {
            exit('Error: Could not find file !');
        }
    }
}
