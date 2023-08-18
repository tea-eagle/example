<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-view">

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
        'name',
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
        'format' => ['date', 'php:d.m.Y H:i:s'],
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

    $columns[] = 'place.name';
    $columns[] = [
        'attribute' => 'date_start',
        'format' => ['date', 'php:d.m.Y'],
    ];
    $columns[] = [
        'attribute' => 'date_end',
        'format' => ['date', 'php:d.m.Y'],
    ];
    $columns[] = [
        'attribute' => 'time_start',
    ];
    $columns[] = [
        'attribute' => 'time_end',
    ];
    $columns[] = 'email:email';
    $columns[] = [
        'attribute' => 'is_send_notofication',
        'value' => function($model) {
            return $model->is_send_notofication ? 'Да' : 'Нет';
        }
    ];
    $columns[] = 'description:ntext';
    $columns[] = 'organizer';
    $columns[] = 'age_restrictions';
    $columns[] = [
        'attribute' => 'price',
        'value' => function($model) {
            if ($model->price) {
                return $model->price . ' ₽';
            }
        }
    ];
    $columns[] = 'phone';
    $columns[] = 'address';
    $columns[] = 'location';
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $columns,
    ]) ?>

</div>
