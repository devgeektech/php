<?php

class d_mailerlite {
    private $api_key;
    private $validator;
    private $url = 'http://api.mailerlite.com/api/v2';
    
    public function __construct($api_key) {
        $this->api_key = $api_key;
    }

    public function getGroups() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->url.'/groups');
        if(!empty($this->api_key))
        {
            $headers = array('X-MailerLite-ApiKey: '.$this->api_key);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $data = curl_exec($ch);
        curl_close($ch);
        if(!empty($data)){
            $data = json_decode($data, true);
        }
        return $data;
    }

    public function addSubscription($group_id, $data){
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->url."/groups/{$group_id}/subscribers");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, "10");
        curl_setopt($ch, CURLOPT_TIMEOUT, "30");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        if(!empty($this->api_key))
        {
            $headers = array('X-MailerLite-ApiKey: '.$this->api_key,
                "content-type: application/json",);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        $data = curl_exec($ch);
        curl_close($ch);
        if(!empty($data)){
            $data = json_decode($data, true);
        }
        return $data;
    }
}
?>