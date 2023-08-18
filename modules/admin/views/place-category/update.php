<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PlaceCategory */

$this->title = 'Редактирование категории заведений: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории заведений', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="place-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
