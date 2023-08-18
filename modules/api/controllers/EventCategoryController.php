<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use app\models\EventCategory;
use yii\filters\auth\HttpBearerAuth;

/**
 * EventCategoryController implements the CRUD actions for EventCategory model.
 */
class EventCategoryController extends ActiveController
{
    public $modelClass = 'app\models\EventCategory';

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
