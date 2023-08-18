<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        &nbsp;
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'email:email',
            [
                'label' => 'Роли',
                'value' => function($model) {
                    return $model->rolesString;
                }
            ],
            'nicename',
            'username',
            'phone',
            [
                'attribute' => 'photo',
                'format' => 'html',
                'value' => function($model) {
                    return $model->photo ? $model->thumbnail : '';
                }
            ],
            [
                'attribute' => 'status',
                'filter' => [
                    User::STATUS_NEW => 'Новый',
                    User::STATUS_ACTIVE => 'Подтвержден',
                    User::STATUS_BLOCKED => 'Заблокирован',
                ],
                'value' => function($model) {
                    switch ($model->status) {
                        case User::STATUS_NEW:
                            return 'Новый';
                            break;
                        case User::STATUS_ACTIVE:
                            return 'Подтвержден';
                            break;
                        case User::STATUS_BLOCKED:
                            return 'Заблокирован';
                            break;
                    }
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d.m.Y H:i:s'],
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
