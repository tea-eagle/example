<?php
$this->title = 'Административная панель';
?>
<div class="admin-default-index">
    <h1><?= $this->title ?></h1>
    <p>
        Здравствуйте, <?= \Yii::$app->user->isGuest ? 'Гость' : \Yii::$app->user->identity->name ?>!
    </p>
</div>
