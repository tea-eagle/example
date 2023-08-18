<?php

namespace app\modules\api\controllers;

// use Yii;
use yii\rest\Controller;
// use app\models\Place;
// use app\models\PlaceSearchApi;
// use app\models\UploadForm;
use yii\filters\auth\HttpBearerAuth;
// use yii\web\NotFoundHttpException;
// use yii\filters\VerbFilter;
// use yii\web\UploadedFile;

/**
 * PhotoAlbumController implements the CRUD actions for Place model.
 */
class PhotoAlbumController extends Controller
{
    // public $modelClass = 'app\models\Place';

    // public $serializer = [
    //     'class' => 'yii\rest\Serializer',
    //     'collectionEnvelope' => 'items',
    // ];

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
    // public function actions() 
    // { 
    //     $actions = parent::actions();
    //     unset($actions['create']);
    //     $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
    //     return $actions;
    // }

    /**
     * {@inheritdoc}
     */
    // public function prepareDataProvider() 
    // {
    //     $searchModel = new PlaceSearchApi();
    //     return $searchModel->search(\Yii::$app->request->queryParams);
    // }

    // public function actionCreate()
    // {
    //     $response = new \stdClass();
    //     $model = new News();
    //     if ($model->load(['Place' => \Yii::$app->request->post()])) {
    //         $upform = new UploadForm();
    //         $upform->image = UploadedFile::getInstanceByName('fileImage');
    //         if ($upform->image && $upform->validate()) {
    //             if ($media_id = $upform->upload()) {
    //                 $model->image_id = $media_id;
    //             }
    //             foreach ($upform->errors as $value) {
    //                 foreach ($value as $error) {
    //                     $model->addError('fileImage', $error);
    //                 }
    //             }
    //         }

    //         if ($model->save()) {
    //             $response->id = $model->id;
    //         }
    //     }

    //     return $model ? $model : null;
    // }

    public function actionIndex() {
        $q = new \stdClass();
        $q->items = [
                [
                    "id" => 1,
                    "name" => "Cherry Cloud",
                    "date" => "01.01.2020",
                    "image" => "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                    "photos" => [
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                    ],
                ],
                [
                    "id" => 2,
                    "name" => "Lorem ipsum",
                    "date" => "10.10.2020",
                    "image" => "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                    "photos" => [
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                        "http://nl.dvfx.ru/uploads/media_225e97d52d2b9b7.jpg",
                    ],
                ],
            ];
        $q->_links = [
            "self" => [
                "href" => "http://nl.dvfx.ru/api/photo-album&page=1"
            ]
        ];
        $q->_meta = [
            "totalCount" => 1,
            "pageCount" => 1,
            "currentPage" => 1,
            "perPage" => 20,
        ];
        return $q;
    }
}
