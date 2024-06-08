<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use backend\models\Notification;
use backend\models\Conversation;

/** @var yii\web\View $this */
/** @var Notification $model */
/** @var Notification $originalNotification */
/** @var Conversation $conversation title */

$this->title = 'Responder';
$this->params['breadcrumbs'][] = ['label' => 'Notificaciones', 'url' => ['notification/index']];
$this->params['breadcrumbs'][] = [
    'label' => StringHelper::truncateWords($originalNotification->title, 10, '...'),
    'url' => Url::to(['notification/view', 'id' => $originalNotification->id])
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-send container">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-6 mx-lg-auto">
            <?= $this->render('_form-reply', [
                'model' => $model,
                'originalNotification' => $originalNotification,
                'conversation' => $conversation,
            ]) ?>
        </div>
    </div>
</div>