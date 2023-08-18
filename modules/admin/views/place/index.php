<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\PlaceCategory;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PlaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заведения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить заведение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'name',
            [
                'attribute' => 'category_id',
                'label' => 'Раздел',
                'value' => function($model) {
                    return $model->category->name;
                },
                'filter' => ArrayHelper::map(PlaceCategory::find()->asArray()->all(), 'id', 'name'),
            ],
            /*[
                'attribute' => 'image_id',
                'label' => 'Изображение',
                'format' => 'html',
                'filter' => false,
                'value' => function($model) {
                    return $model->image ? $model->image->tumbnail : '';
                }
            ],*/

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
