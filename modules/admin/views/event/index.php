<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\EventCategory;
use app\models\User;
use app\models\Place;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'События';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить событие', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?php
    $columns = [
        'name',
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
        'filter' => ArrayHelper::map(EventCategory::find()->asArray()->all(), 'id', 'name'),
    ];

    $columns[] = [
        'attribute' => 'place_id',
        'value' => 'place.name',
        'filter' => ArrayHelper::map(Place::find()->asArray()->all(), 'id', 'name'),
    ];

    $columns[] = [
        'attribute' => 'date_start',
        'format' => ['date', 'php:d.m.Y'],
        'filter' => DatePicker::widget([
            'name' => 'EventSearch[date_start]',
            'type' => DatePicker::TYPE_INPUT,
            'value' => $searchModel->date_start,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose'=>true,
                'format' => 'php:d.m.Y',
            ],
            'convertFormat' => true,
        ]),
    ];

    $columns[] = [
        'attribute' => 'date_end',
        'format' => ['date', 'php:d.m.Y'],
        'filter' => DatePicker::widget([
            'name' => 'EventSearch[date_end]',
            'type' => DatePicker::TYPE_INPUT,
            'value' => $searchModel->date_end,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose'=>true,
                'format' => 'php:d.m.Y',
            ],
            'convertFormat' => true,
        ]),
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
