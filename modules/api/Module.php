<?php

namespace app\modules\api;

/**
 * api module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
        \Yii::$app->urlManager->enableStrictParsing = false;

        date_default_timezone_set('Asia/Vladivostok');

        $date = date('d-m-Y');

        $headers = \Yii::$app->request->headers;

        file_put_contents(\Yii::getAlias('@webroot') . "/logs/api--{$date}.txt", var_export([
            'datetime' => date('Y-m-d H:i:s'),
            'url' => \Yii::$app->request->url,
            'method' => \Yii::$app->request->method,
            'user_host' => \Yii::$app->request->userHost,
            'user_ip' => \Yii::$app->request->userIP,
            'user-agent' => $headers->get('user-agent'),
            'accept' => $headers->get('accept'),
            'x-real-ip' => $headers->get('x-real-ip'),
            // 'headers' => $headers,
            // 'cookie' => \Yii::$app->request->cookies,
            'params' => \Yii::$app->request->bodyParams,
        ], true), FILE_APPEND);

        $delemiter = PHP_EOL . '=============================================================================' . PHP_EOL;
        file_put_contents(\Yii::getAlias('@webroot') . "/logs/api--{$date}.txt", $delemiter, FILE_APPEND);
    }
}
