<?php
/** @var Notification $model */

/** @var User $sender */

use backend\models\Notification;
use common\models\User;
use powerkernel\fontawesome\Icon;
use yii\bootstrap5\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

?>
<div class="row">
    <?php $send = $model->sender_id == $sender->id; ?>
    <div <?= 'tabindex="0" class="conversation-item-wrapper focusable col-10 col-md-8 col-lg-6 '
    . ($send ? 'offset-lg-1"' : 'offset-2 offset-md-4 offset-lg-5 text-end"') ?>
    >
        <div <?= 'class="conversation-item p-2 m-2 position-relative rounded border '
        . ($model->getReceiver()->one()->getId() == Yii::$app->user->identity->getId()
        && $model->getStatus() == Notification::MESSAGE_STATUS_UNREAD
            ? 'border-warning bg-warning-subtle'
            : 'border-primary')
        . '"' ?>
        >
            <?= Html::tag(
                'p',
                $model->status === 'unread' ?
                    Icon::widget(['name' => 'envelope']) :
                    (
                    $model->status === 'read' ?
                        Icon::widget(['name' => 'envelope-open']) :
                        Icon::widget(['name' => 'reply'])
                    ),
                [
                    'class' => 'position-absolute mt-1 top-0' . ($send ? ' end-0 me-2' : ' start-0 ms-2'),
                    'title' => $model->getStatus() == Notification::MESSAGE_STATUS_UNREAD
                        ? 'No leída'
                        : ($model->getStatus() == Notification::MESSAGE_STATUS_READ
                            ? 'Leída'
                            : 'Respondida'),
                    'data-toggle' => 'tooltip',
                ]
            ) ?>
            <p class="mb-0">
                <span><?= Html::a(
                        StringHelper::truncateWords($model->title, 10, '...'),
                        Url::to(['notification/view', 'id' => $model->id])
                    ) ?>
                </span>
                <?= Html::tag('span', Html::tag('small', Yii::$app->formatter->asRelativeTime($model->created_at)), [
                    'class' => 'text-small text-muted',
                    'title' => Yii::$app->formatter->asDatetime($model->created_at, 'full'),
                    'data-toggle' => 'tooltip',
                ]) ?>
            </p>
            <?php
            if ($model->getReceiver()->one()->getId() == Yii::$app->user->identity->getId()
                && $model->getStatus() == Notification::MESSAGE_STATUS_UNREAD) {
                echo Html::tag('small', '&lt;notificación sin abrir&gt;', [
                    'class' => 'mb-0 truncate'
                ]);
            } else {
                echo Html::tag('p', Html::tag('small', $model->getMessage()), [
                    'class' => 'mb-0 truncate'
                ]);
            }
            ?>
        </div>
    </div>
</div>
