<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventCategory */

$this->title = 'Добавление категории событий';
$this->params['breadcrumbs'][] = ['label' => 'Категории событий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
