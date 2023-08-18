<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nicename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        User::STATUS_NEW => 'Новый',
        User::STATUS_ACTIVE => 'Подтвержден',
        User::STATUS_BLOCKED => 'Заблокирован',
    ]) ?>

    <div class="form-group">
        <?php if ($model->errors) {
            foreach ($model->errors as $key => $error) {
                printf("<div class='event-error'>%s</div>", $error[0]);
            }
        } ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
