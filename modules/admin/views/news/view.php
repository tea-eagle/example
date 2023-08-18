<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php if (\Yii::$app->user->can('admin')) { ?>
        <p>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } else { ?>
        <br>
    <?php } ?>

    <?php
    $columns = [
        [
            'attribute' => 'title',
            'label' => 'Заголовок',
        ],
    ];

    if (\Yii::$app->user->can('admin')) {
        $columns[] = [
            'attribute' => 'user_id',
            'label' => 'Автор',
            'value' => function($model) {
                return "{$model->user->name} (ID: {$model->user->id})";
            }
        ];
    }

    $columns[] = [
        'attribute' => 'category_id',
        'label' => 'Раздел',
        'value' => function($model) {
            return $model->category->name;
        }
    ];

    $columns[] = [
        'attribute' => 'date_create',
        'label' => 'Дата создания',
        'format' => ['date', 'd MMMM yyyy'],
    ];

    if (\Yii::$app->user->can('admin')) {
        $columns[] = [
            'attribute' => 'date_public',
            'label' => 'Дата публикации',
            'format' => ['date', 'php:d.m.Y H:i:s'],
        ];
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
        }
    ];

    $columns[] = [
        'attribute' => 'image_id',
        'label' => 'Изображение',
        'format' => 'html',
        'value' => function($model) {
            return $model->image ? $model->image->tumbnail : '';
        }
    ];

    $columns[] = [
        'attribute' => 'body',
        'label' => 'Текст',
        'format' => 'html',
    ];
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $columns,
    ]) ?>

</div>
