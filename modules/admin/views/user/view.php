<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Заблокировать', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите заблокировать пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
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
        ],
    ]) ?>

</div>
