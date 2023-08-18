<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use vova07\imperavi\Widget;
use app\models\Place;
use app\models\PlaceCategory;

/* @var $this yii\web\View */
/* @var $model app\models\Place */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="place-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(PlaceCategory::find()->asArray()->all(), 'id', 'name')
    )->label('Категория'); ?>

    <?php if ($model->image_id) { ?>
        <?= $model->image->tumbnail; ?>
    <?php } ?>

    <?= $form->field($model, 'fileImage')->fileInput(['value' => ''])->hint('Допустимые расширения файла: *.jpg, *.png, *.jpeg, *.gif')->label('Изображение') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'instagram')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location')->hiddenInput(['id' => 'location_field']) ?>
    
    <script src="https://api-maps.yandex.ru/2.1/?apikey=3a32b3a8-4a7b-4d52-957e-589bf3c9bcfe&lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        function init(){
            var map = new ymaps.Map("map", {
                center: [<?= $model->latitude ? $model->latitude : 37.930969856578926 ?>, <?= $model->longitude ? $model->longitude : 46.09807322096234 ?>],
                zoom: 14
            });
            var myPlacemark = new ymaps.Placemark([<?= $model->latitude ? $model->latitude : 37.930969856578926 ?>, <?= $model->longitude ? $model->longitude : 46.09807322096234 ?>], {balloonContent: '<?= $model->name ?>'});
            map.geoObjects.add(myPlacemark);
            myPlacemark.balloon.open();

            var searchControl = new ymaps.control.SearchControl({
                options: {
                    float: 'right',
                    floatIndex: 100,
                    noPlacemark: true,
                    fitMaxWidth: true
                }
            });
            map.controls.add(searchControl);

            var searchControl = map.controls.get('searchControl');
            searchControl.events.add('resultselect', function (e) {
                let coords = searchControl.getResultsArray()[e.get('index')].geometry.getCoordinates();
                $('#location_field').val(coords.toString());
            }, this);
        }
    </script>
    <body>
        <div id="map" style="width: 100%; height: 400px"></div>
    </body>

    <?= $form->field($model, 'menu')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'timetable')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'average_check')->textInput(['type' => 'number', 'maxlength' => true, 'step' => '0.01']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
