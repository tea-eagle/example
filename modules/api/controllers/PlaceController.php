<?php

namespace app\modules\api\controllers;

// use Yii;
use yii\rest\ActiveController;
use app\models\Place;
use app\models\PlaceSearchApi;
use app\models\UploadForm;
use yii\filters\auth\HttpBearerAuth;
// use yii\web\NotFoundHttpException;
// use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PlaceController implements the CRUD actions for Place model.
 */
class PlaceController extends ActiveController
{
    public $modelClass = 'app\models\Place';

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
        unset($actions['create']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataProvider() 
    {
        $searchModel = new PlaceSearchApi();
        return $searchModel->search(\Yii::$app->request->queryParams);
    }

    public function actionCreate()
    {
        $response = new \stdClass();
        $model = new News();
        if ($model->load(['Place' => \Yii::$app->request->post()])) {
            $upform = new UploadForm();
            $upform->image = UploadedFile::getInstanceByName('fileImage');
            if ($upform->image && $upform->validate()) {
                if ($media_id = $upform->upload()) {
                    $model->image_id = $media_id;
                }
                foreach ($upform->errors as $value) {
                    foreach ($value as $error) {
                        $model->addError('fileImage', $error);
                    }
                }
            }

            if ($model->save()) {
                $response->id = $model->id;
            }
        }

        return $model ? $model : null;
    }
}
