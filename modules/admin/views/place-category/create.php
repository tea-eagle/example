<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PlaceCategory */

$this->title = 'Добавление категории заведения';
$this->params['breadcrumbs'][] = ['label' => 'Категории заведений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
