<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\Event;
use app\models\EventCategory;
use app\models\Place;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */

$date_start = $model->date_start ? $model->date_start : date('d.m.Y');
$date_end = $model->date_end ? $model->date_end : date('d.m.Y');
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(EventCategory::find()->asArray()->all(), 'id', 'name')
    )->label('Категория'); ?>

    <?php if (\Yii::$app->user->can('admin')) {
        echo $form->field($model, 'status')->dropDownList([
            Event::STATUS_PENDING => 'На модерации',
            Event::STATUS_PUBLISH => 'Опубликовано',
            Event::STATUS_DELETED => 'Удалено'
        ])->label('Статус');
    } ?>

    <?= $form->field($model, 'place_id')->dropDownList(
        ArrayHelper::map(Place::find()->asArray()->all(), 'id', 'name')
    ) ?>

    <?php if ($model->image_id) { ?>
        <?= $model->image->tumbnail; ?>
    <?php } ?>

    <?= $form->field($model, 'fileImage')->fileInput(['value' => ''])->hint('Допустимые расширения файла: *.jpg, *.png, *.jpeg, *.gif')->label('Изображение') ?>
    
    <div class="form-group">
        <label class="control-label" for="field_date_start">Дата начала</label>
        <?= DatePicker::widget([
            'id' => 'field_date_start',
            'name' => 'Event[date_start]',
            'type' => DatePicker::TYPE_INPUT,
            'value' => $date_start,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy'
            ]
        ]); ?>
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <label class="control-label" for="field_date_end">Дата завершения</label>
        <?= DatePicker::widget([
            'id' => 'field_date_end',
            'name' => 'Event[date_end]',
            'type' => DatePicker::TYPE_INPUT,
            'value' => $date_end,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy'
            ]
        ]); ?>
        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'time_start')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showMeridian' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'time_end')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showMeridian' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_send_notofication')->checkbox() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'organizer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'age_restrictions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

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

    <br>

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
