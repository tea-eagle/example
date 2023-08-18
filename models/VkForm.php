<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * VkForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class VkForm extends Model
{
    public $social_user_id;
    public $token;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['social_user_id', 'token'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'social_user_id' => 'Идентификатор во вконтакте',
            'token' => 'Токен вконтакте',
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 3600*24*30);
        }
        return false;
    }

    public function register()
    {
        $user = new User();
        $user->setPassword(mt_rand(111111, 999999));
        $user->generateAuthKey();
        $user->generateToken();
        $user->generateVerifyCode();
        if ($user->save()) {
            $user->activate();

            $authManager = \Yii::$app->authManager;

            $userRole = $authManager->getRole('user');
            if (!$authManager->getAssignment('user', $user->id)) {
                $authManager->assign($userRole, $user->id);
            }

            $userSocial = new UserSocial();
            $userSocial->user_id = $user->id;
            $userSocial->social_user_id = (string) $this->social_user_id;
            $userSocial->type = 'vk';
            $userSocial->token = $this->token;
            if (!$userSocial->save()) {
                $user = null;
            }
        }
        return $user ? $user : null;
    }

    /**
     * Finds user by [[social_user_id]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserSocial::findBySocialId($this->social_user_id, 'vk');
        }

        if (!$this->_user) {
            $this->_user = $this->register();
        }

        return $this->_user;
    }
}
