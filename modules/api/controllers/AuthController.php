<?php

namespace app\modules\api\controllers;

use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\AccessControl;
use app\components\AuthSocialData;
use app\models\User;
use app\models\UserSocial;
use app\models\SignupForm;
use app\models\LoginFormApi;
use app\models\VkForm;

/**
 * Auth controller for the `api` module
 */
class AuthController extends Controller
{
    private $_email;
    private $_password;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['register', 'verify', 'u-login'],
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'roles' => ['@'],
                ]
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'except' => ['register', 'verify', 'u-login'],
            'auth' => function ($email, $password) {
                $user = User::find()->where(['email' => $email])->one();
                if ($user->validatePassword($password)) {
                    $this->_email = $email;
                    $this->_password = $password;
                    return $user;
                }
                return null;
            },
        ];
        return $behaviors;
    }

    public function actionRegister()
    {
        $response = new \stdClass();
        try {
            $post = \Yii::$app->request->post();

            if (!isset($post['email'], $post['password'])) {
                throw new \Exception("Не верное тело запроса");
            }

            $model = new SignupForm();
            $form = [
                'SignupForm' => [
                    'email'    => $post['email'],
                    'password' => $post['password'],
                ]
            ];
     
            if ($model->load($form)) {
                if ($user = $model->signup()) {
                    if (\Yii::$app->getUser()->login($user)) {
                        $response->success = true;
                    } else {
                        throw new \Exception("Ошибка регистрации");
                    }
                }
            }

            if ($model->hasErrors()) {
                foreach ($model->getErrors() as $key => $error) {
                    throw new \Exception($error[0]);
                }
            }

            if ($user) {
                $user->sendVerifyCodeToEmail();
            }

        } catch (\Exception $e) {
            $response->success = false;
            $response->err_code = $e->getCode();
            $response->err_description = $e->getMessage();
        } finally {
            return $response;
        }
    }

    public function actionVerify()
    {
        $response = new \stdClass();
        try {
            $post = \Yii::$app->request->post();

            if (!isset($post['email'], $post['code'])) {
                throw new \Exception("Не верное тело запроса");
            }

            $user = User::find()->where([
                'email' => $post['email'],
                'verify_code' => $post['code'],
                'status' => User::STATUS_NEW,
            ])->one();

            if (!$user || !$user->activate()) {
                throw new \Exception("Ошибка верификации");
            }

            $response->success = true;
            $response->token = $user->token;

        } catch (\Exception $e) {
            $response->success = false;
            $response->err_code = $e->getCode();
            $response->err_description = $e->getMessage();
        } finally {
            return $response;
        }
    }

    public function actionULogin()
    {
        $response = new \stdClass();
        try {
            $post = \Yii::$app->request->post();

            if (isset($post['vk_token'])) {
                $authSocialData = new AuthSocialData($post['vk_token']);
                $result = $authSocialData->getVkData();

                if (isset($result->error)) {
                    throw new \Exception($result->error->error_msg);
                }

                if (!isset($result->response, $result->response[0])) {
                    throw new \Exception("Ошибка получения данных");
                }

                $vkData = $result->response[0];

                $model = new VkForm();
                $form = [
                    'VkForm' => [
                        'social_user_id'    => $vkData->id,
                        'token' => $post['vk_token'],
                    ]
                ];

                if ($model->load($form)) {
                    if ($model->login()) {
                        $response->success = true;
                    }
                }

                if ($model->hasErrors()) {
                    foreach ($model->getErrors() as $key => $error) {
                        throw new \Exception($error[0]);
                    }
                }

                $user = \Yii::$app->user->identity;

                if (!$user->username) {
                    $user->username = "{$vkData->first_name} {$vkData->last_name}";
                }

                if (!$user->photo_id && $vkData->photo_max) {
                    $dform = new DownloadForm();
                    $dform->url = $vkData->photo_max;
                    if ($photo_id = $dform->execute()) {
                        $user->photo_id = $photo_id;
                    }
                }

                if (!$user->phone) {
                    if ($vkData->mobile_phone) {
                        $user->phone = preg_replace('/[^\d\+]/', '', $vkData->mobile_phone);
                    } elseif($vkData->home_phone) {
                        $user->phone = preg_replace('/[^\d\+]/', '', $vkData->home_phone);
                    }
                }

                $user->save();

                $response->token = \Yii::$app->user->identity->token;
            }

            if (isset($post['facebook_token'])) {
                $authSocialData = new AuthSocialData($post['facebook_token']);
                $result = $authSocialData->getFbData();

                $response->fb_data = $result;
            }

            if (isset($post['twitter_token'])) {
                // запрашиваем данные с twitter
            }

        } catch (\Exception $e) {
            $response->success = false;
            $response->err_code = $e->getCode();
            $response->err_description = $e->getMessage();
        } finally {
            return $response;
        }
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
        $response = new \stdClass();
        try {
            $post = \Yii::$app->request->post();

            $model = new LoginFormApi();
            $form = [
                'LoginFormApi' => [
                    'email'    => $this->_email,
                    'password' => $this->_password,
                ]
            ];

            if ($model->load($form)) {
                if ($model->login()) {
                    $response->success = true;
                }
            }

            if ($model->hasErrors()) {
                foreach ($model->getErrors() as $key => $error) {
                    throw new \Exception($error[0]);
                }
            }

            $response->token = \Yii::$app->user->identity->token;

        } catch (\Exception $e) {
            $response->success = false;
            $response->err_code = $e->getCode();
            $response->err_description = $e->getMessage();
        } finally {
            return $response;
        }
    }
}
