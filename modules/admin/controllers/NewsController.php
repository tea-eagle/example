<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\News;
use app\models\NewsSearch;
use app\models\UploadForm;
use app\models\DownloadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url'   => Url::base(true) . '/files/news/',
                'path'  => '@news-files',
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (!\Yii::$app->user->can('admin')) {
            if ($model->user_id !== \Yii::$app->user->identity->id || $model->status === News::STATUS_DELETED) {
                throw new \yii\web\ForbiddenHttpException('Нет прав на просмотр контента');
            }
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post())) {
            $upform = new UploadForm();
            $upform->image = UploadedFile::getInstance($model, 'fileImage');
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
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!\Yii::$app->user->can('admin')) {
            if ($model->user_id !== \Yii::$app->user->identity->id || $model->status === News::STATUS_DELETED) {
                throw new \yii\web\ForbiddenHttpException('Нет прав на просмотр контента');
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $upform = new UploadForm();
            $upform->image = UploadedFile::getInstance($model, 'fileImage');
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
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!\Yii::$app->user->can('admin')) {
            if ($model->user_id !== \Yii::$app->user->identity->id || $model->status === News::STATUS_DELETED) {
                throw new \yii\web\ForbiddenHttpException('Нет прав на просмотр контента');
            }
        }

        $model->status = 'deleted';
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
