<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use app\models\PlaceCategory;
use yii\filters\auth\HttpBearerAuth;

/**
 * PlaceCategoryController implements the CRUD actions for PlaceCategory model.
 */
class PlaceCategoryController extends ActiveController
{
    public $modelClass = 'app\models\PlaceCategory';

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
