<?php

namespace app\components;

use yii\base\BaseObject;

class AuthSocialData extends BaseObject
{
    private $token;
    private $vk_api_url = 'https://api.vk.com/method/';
    private $fb_api_url = 'https://graph.facebook.com/';

    public function __construct($token, $config = [])
    {
        $this->token = $token;

        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        // ... инициализация происходит после того, как была применена конфигурация.
    }

    public function getVkData()
    {
        $url = "users.get?fields=photo_max,has_mobile,contacts,has_photo&access_token={$this->token}&v=5.103";

        return $this->getContent($this->vk_api_url . $url, 'get');
    }

    public function getFbData()
    {
        $url = "me?fields=id,name,email,picture&access_token={$this->token}";

        return $this->getContent($this->fb_api_url . $url, 'get');
    }

    private function getContent($url, $query_type = 'post', $postData = '')
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => ($query_type == 'post' ? true : false),
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_FORBID_REUSE => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $result = json_decode(curl_exec($curl));

        if (!$result) {
            $result = curl_error($curl);
        }

        return $result;
    }
}