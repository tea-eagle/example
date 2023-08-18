<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use app\models\NewsCategory;
use yii\filters\auth\HttpBearerAuth;

/**
 * NewsCategoryController implements the CRUD actions for NewsCategory model.
 */
class NewsCategoryController extends ActiveController
{
    public $modelClass = 'app\models\NewsCategory';

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
}
