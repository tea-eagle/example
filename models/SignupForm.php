<?php
 
namespace app\models;
 
use Yii;
use yii\base\Model;
 
/**
 * Signup form
 */
class SignupForm extends Model
{
 
    public $login;
    public $username;
    public $email;
    public $password;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            // ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Такой email уже зарегистрирован'],
            ['email', 'string', 'min' => 2, 'max' => 255],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    public function getVerifiedUser($email)
    {
        return User::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE]);
    }

    public function getUnVerifiedUser($email)
    {
        return User::findOne(['email' => $email, 'status' => User::STATUS_NEW]);
    }
 
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        if ($user = $this->getVerifiedUser($this->email)) {
            $this->addError($user->email, 'Пользователь с таким email уже зарегистрирован');
            return;
        }

        $user = $this->getUnVerifiedUser($this->email);
    
        if (!$user) {
            $user = new User();
            $user->email = $this->email;
        }
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateToken();
        $user->generateVerifyCode();
        if ($user->save()) {
            $authManager = \Yii::$app->authManager;

            $userRole = $authManager->getRole('user');
            if (!$authManager->getAssignment('user', $user->id)) {
                $authManager->assign($userRole, $user->id);
            }
        }
        return $user ? $user : null;
    }
 
}