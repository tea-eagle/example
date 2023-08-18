<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Place */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заведения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="place-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'name',
            [
                'attribute' => 'category_id',
                'label' => 'Раздел',
                'value' => function($model) {
                    return $model->category->name;
                },
            ],
            'description:ntext',
            [
                'attribute' => 'image_id',
                'label' => 'Изображение',
                'format' => 'html',
                'filter' => false,
                'value' => function($model) {
                    return $model->image ? $model->image->tumbnail : '';
                }
            ],
            'address',
            'phone',
            'site',
            'location',
            'menu:ntext',
            'timetable:ntext',
            'average_check',
        ],
    ]) ?>

</div>
