<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use vova07\imperavi\Widget;
use app\models\News;
use app\models\NewsCategory;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Заголовок') ?>

    <?php //= $form->field($model, 'image_id')->fileInput()->label('Изображение') ?>

    <?php echo $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(NewsCategory::find()->asArray()->all(), 'id', 'name')
    )->label('Категория'); ?>

    <?php if (\Yii::$app->user->can('admin')) {
        echo $form->field($model, 'status')->dropDownList([
            News::STATUS_PENDING => 'На модерации',
            News::STATUS_PUBLISH => 'Опубликовано',
            News::STATUS_DELETED => 'Удалено'
        ])->label('Статус');
    } ?>

    <?php if ($model->image_id) { ?>
        <?= $model->image->tumbnail; ?>
    <?php } ?>

    <?= $form->field($model, 'fileImage')->fileInput(['value' => ''])->hint('Допустимые расширения файла: *.jpg, *.png, *.jpeg, *.gif')->label('Изображение') ?>

    <?php echo $form->field($model, 'body')->widget(Widget::class, [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => Url::to(['/admin/news/image-upload']),
            'plugins' => [
                'imagemanager',
                'fullscreen',
            ],
        ],
    ])->label('Текст'); ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
