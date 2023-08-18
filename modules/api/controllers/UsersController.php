<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use app\models\User;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

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
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        throw new \yii\web\ForbiddenHttpException('У вас нет прав на просмотр контента.');
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'view') {
            if ($model->id !== \Yii::$app->user->id) {
                throw new \yii\web\ForbiddenHttpException('У вас нет прав на просмотр контента.');
            }
        }
    }
}
