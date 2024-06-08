<?php

/** @var yii\web\View $this */

/** @var backend\models\Notification $model */

use backend\models\Conversation;
use powerkernel\fontawesome\Icon;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$this->title = StringHelper::truncateWords($model->getTitle(), 10, '...');
$this->params['breadcrumbs'][] = ['label' => 'Mis Notificaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$senderName = $model->sender_id == Yii::$app->user->getId() ? 'tí' : $model->sender->fullname;
$receiverName = $model->receiver_id == Yii::$app->user->getId() ? 'tí' : $model->receiver->fullname;
?>

<div class="notification-view container">
    <div class="row">
        <div class="col-lg-8 mx-lg-auto">
            <h4 class="font-monospace text-small text-muted text-end border-dark-subtle border-bottom">
                <?= Html::encode($model->getTypeValue($model->getType())) ?>
            </h4>
            <h1><?= Html::encode($model->getTitle()) ?></h1>
            <p class="text-small text-muted">(de <?= $senderName ?> a <?= $receiverName ?>)
                <?= Html::tag('span', Html::tag('small', Yii::$app->formatter->asRelativeTime($model->created_at)), [
                    'class' => 'text-small text-muted',
                    'title' => Yii::$app->formatter->asDatetime($model->created_at, 'full'),
                    'data-toggle' => 'tooltip',
                ]) ?></p>
            <hr>
            <p>
                <?= $model->message ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 mx-lg-auto d-flex gap-2 justify-content-center">
            <?= $model->receiver_id === Yii::$app->user->getId()
            && $model->status !== $model::MESSAGE_STATUS_REPLIED
            && $model->getConversation()->one()->getStatus() === Conversation::CONVERSATION_STATUS_OPEN ?
                Html::a(Icon::widget([
                        'name' => 'reply',
                        'size' => 'fa-lg',
                        'options' => [
                            'class' => 'pe-2'
                        ]
                    ]) . 'Responder', Url::to([
                    'notification/reply',
                    'id' => $model->id
                ]), [
                    'class' => 'btn btn-primary'
                ])
                : '' ?>
            <?= Html::a(Icon::widget([
                    'name' => 'eye',
                    'size' => 'fa-lg',
                    'options' => [
                        'class' => 'pe-2'
                    ]
                ]) . ' Ver conversación', Url::to([
                'notification/view-conversation',
                'id' => $model->getConversation()->one()->id
            ]), [
                'class' => 'btn btn-primary'
            ]) ?>
            <?= $model->receiver_id === Yii::$app->user->getId()
            && $model->status === $model::MESSAGE_STATUS_READ
            && Yii::$app->user->identity->role === 'admin' ?
                Html::a(Icon::widget([
                        'name' => 'envelope',
                        'size' => 'fa-lg',
                        'options' => [
                            'class' => 'pe-2'
                        ]
                    ]) . ' Marcar como no leída', Url::to([
                    'notification/undo-read',
                    'id' => $model->id
                ]), [
                    'class' => 'btn btn-primary'
                ])
                : '' ?>
        </div>
    </div>
</div>
