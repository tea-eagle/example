<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
use app\models\NewsCategory;
/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin([
        'timeout' => false,
        'enablePushState' => false,
        /*'clientOptions' => [
            'method' => 'POST',
        ],*/
    ]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $columns = [
        [
            'attribute' => 'title',
            'label' => 'Заголовок',
        ],
    ];

    if (\Yii::$app->user->can('admin')) {
        $filter = [];
        foreach (User::find()->all() as $key => $user) {
            $filter[$user->id] = "{$user->name} (ID: {$user->id})";
        }
        $columns[] = [
            'attribute' => 'user_id',
            'label' => 'Автор',
            'value' => function($model) {
                return "{$model->user->name} (ID: {$model->user->id})";
            },
            'filter' => $filter,
        ];
    }

    $columns[] = [
        'attribute' => 'category_id',
        'label' => 'Раздел',
        'value' => function($model) {
            return $model->category->name;
        },
        'filter' => ArrayHelper::map(NewsCategory::find()->asArray()->all(), 'id', 'name'),
    ];

    if (\Yii::$app->user->can('admin')) {
        $columns[] = [
            'attribute' => 'date_create',
            'label' => 'Дата создания',
            'format' => ['date', 'php:d.m.Y H:i:s'],
        ];
        $columns[] = [
            'attribute' => 'date_public',
            'label' => 'Дата публикации',
            'format' => ['date', 'php:d.m.Y H:i:s'],
        ];
    } else {
        $columns[] = [
            'attribute' => 'date_create',
            'label' => 'Дата создания',
            'format' => ['date', 'd MMMM yyyy'],
        ];
    }

    $filter = [
        'pending' => 'На модерации',
        'publish' => 'Опубликовано',
    ];
    if (\Yii::$app->user->can('admin')) {
        $filter['deleted'] = 'Удалено';
    }

    $columns[] = [
        'attribute' => 'status',
        'label' => 'Статус',
        'value' => function($model) {
            switch ($model->status) {
                case 'pending':
                    return 'На модерации';
                    break;
                case 'publish':
                    return 'Опубликовано';
                    break;
                case 'deleted':
                    return 'Удалено';
                    break;
            }
        },
        'filter' => $filter,
    ];

    // $columns[] = 'body:ntext';

    if (\Yii::$app->user->can('admin')) {
        $columns[] = ['class' => 'yii\grid\ActionColumn'];
    } else {
        $columns[] = ['class' => 'yii\grid\ActionColumn', 'template'=>'{view}  {delete}'];
    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
