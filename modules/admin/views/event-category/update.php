<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventCategory */

$this->title = 'Редактирование категории событий: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории событий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="event-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
