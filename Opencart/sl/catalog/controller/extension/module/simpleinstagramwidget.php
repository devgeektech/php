<?php

class ControllerExtensionModulesimpleinstagramwidget extends Controller
{

    const API_URL = 'https://graph.instagram.com/';

    const API_TOKEN_REFRESH_URL = 'https://graph.instagram.com/refresh_access_token';

    private $_mediaFields = 'caption, id, media_type, media_url, permalink, thumbnail_url, timestamp, username';

    private $_timeout = 90000;

    private $_connectTimeout = 20000;

    private function _makeOAuthCall($apiHost, $params, $method = 'POST')
    {
        $paramString = null;

        if (isset($params) && is_array($params)) {
            $paramString = '?' . http_build_query($params);
        }

        $apiCall = $apiHost . (('GET' === $method) ? $paramString : null);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiCall);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->_timeout);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $jsonData = curl_exec($ch);

        curl_close($ch);

        if (! $jsonData) {
            return false;
        }

        $result = json_decode($jsonData);

        if (isset($result->error)) {
            return false;
        }

        if (isset($result->access_token)) {
            return $result->access_token;
        }

        return false;
    }

    protected function _makeCall($function, $params = null, $method = 'GET', $access_token)
    {
        $authMethod = '?access_token=' . $access_token;

        $paramString = null;

        if (isset($params) && is_array($params)) {
            $paramString = '&' . http_build_query($params);
        }

        $apiCall = self::API_URL . $function . $authMethod . (('GET' === $method) ? $paramString : null);

        $headerData = array(
            'Accept: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiCall);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->_connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->_timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $jsonData = curl_exec($ch);

        if (! $jsonData) {
            return false;
        }

        list ($headerContent, $jsonData) = explode("\r\n\r\n", $jsonData, 2);

        curl_close($ch);

        $result = json_decode($jsonData);

        if (isset($result->error)) {
            return false;
        }

        if (isset($result->data)) {
            return $result->data;
        }

        return false;
    }

    public function refreshToken($token, $tokenOnly = false)
    {
        $apiData = array(
            'grant_type' => 'ig_refresh_token',
            'access_token' => $token
        );

        $result = $this->_makeOAuthCall(self::API_TOKEN_REFRESH_URL, $apiData, 'GET');

        return ! $tokenOnly ? $result : $result->access_token;
    }

    private function getUserMedia($id = 'me', $limit = 0, $access_token)
    {
        $params = [
            'fields' => $this->_mediaFields
        ];

        if ($limit > 0) {
            $params['limit'] = $limit;
        }

        return $this->_makeCall($id . '/media', $params, "GET", $access_token);
    }

    private function getImageName($url)
    {
        if (strpos($url, '?') !== false) {
            $t = explode('?', $url);
            $url = $t[0];
        }
        $pathinfo = pathinfo($url);
        $name = $pathinfo['filename'] . '.' . $pathinfo['extension'];

        return $name;
    }

    private function download_img($url)
    {
        $name = $this->getImageName($url);

        $filename = DIR_IMAGE . 'catalog/instagram/' . $name;

        if (file_exists($filename)) {
            if (filesize($filename) > 0)
                return 'catalog/instagram/' . $name;
        }

        // Создаем дирректорию
        if (! file_exists(DIR_IMAGE . 'catalog/instagram')) {
            mkdir(DIR_IMAGE . 'catalog/instagram/', 0777, true);
        }

        $ch = curl_init($url);
        $fp = fopen($filename, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        if (filesize($filename) > 0) {
            return 'catalog/instagram/' . $name;
        }

        if (file_put_contents($filename, file_get_contents($url))) {
            clearstatcache();
            if (filesize($filename) > 0) {
                return 'catalog/instagram/' . $name;
            }
        }

        return false;
    }

    public function index($setting)
    {
        $this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
        $this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/simpleinstagramwidget.css');

        $data = $this->cache->get('simpleinstagramwidget.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . $setting['module_id']);

        if (! $data) {

            $this->load->model('tool/image');

            $access_token = $setting['access_token'];

            // refreshToken
            $access_token = $this->refreshToken($access_token);
            if ($access_token) {
                $this->load->model('extension/module/simpleinstagramwidget');
                $this->model_extension_module_simpleinstagramwidget->refreshToken($setting['module_id'], $access_token);
            }

            $data = array();

            if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
                $data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
                $data['account_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['account_name'], ENT_QUOTES, 'UTF-8');
            } else {
                $data['heading_title'] = $this->language->get('heading_title');
                $data['account_name'] = "";
            }

            $data['account_url'] = $setting['account_url'];

            $data['items_desktop'] = 3;
            $data['items_tablet'] = 2;
            $data['items_mobile'] = 1;

            if ($setting['items_desktop']) {
                $data['items_desktop'] = $setting['items_desktop'];
            }
            if ($setting['items_tablet']) {
                $data['items_tablet'] = $setting['items_tablet'];
            }
            if ($setting['items_mobile']) {
                $data['items_mobile'] = $setting['items_mobile'];
            }

            if ($setting['limit']) {
                $limit = $setting['limit'];
            } else {
                $limit = 5;
            }

            $data['images'] = array();

            $result = $this->getUserMedia('me', $limit, $access_token);

            if ($result) {
                foreach ($result as $post) {
                    $image = $this->download_img($post->media_url);
                    if ($image) {
                        
                        $image = $this->model_tool_image->resize($image, $setting['width'], $setting['height']);
                       
                        $caption = "";
                        if (isset($post->caption)) {
                            $caption = $post->caption;
                        }                        
                        
                        $data['images'][] = array(
                            'title' => $caption,
                            'link' => $post->permalink,
                            'image' => $image,
                            'time' => date($this->language->get('date_format_short'), strtotime($post->timestamp))
                        );
                    }
                }
            }

            $this->cache->set('simpleinstagramwidget.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . $setting['module_id'], $data);
        }

        return $this->load->view('extension/module/simpleinstagramwidget', $data);
    }

    public function redirect_uri()
    {
        $json = array();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}