<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use app\models\User;
use yii\filters\auth\HttpBearerAuth;

/**
 * AccountController implements the CRUD actions for User model.
 */
class AccountController extends ActiveController
{
    public $modelClass = 'app\models\User';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions() 
    { 
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        return \Yii::$app->user->identity;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action != 'index') {
            throw new \yii\web\ForbiddenHttpException('У вас нет прав на просмотр контента.');
        }
    }
}
