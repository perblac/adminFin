<?php
/** @var Notification $model */

use backend\models\Notification;
use powerkernel\fontawesome\Icon;
use yii\bootstrap5\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

?>
<div class="p-2 m-2 position-relative rounded border border-primary">
    <?= Html::tag(
        'p',
        $model->getStatus() == Notification::MESSAGE_STATUS_UNREAD ?
            Icon::widget(['name' => 'envelope']) :
            (
            $model->getStatus() == Notification::MESSAGE_STATUS_READ ?
                Icon::widget(['name' => 'envelope-open']) :
                Html::a(Icon::widget(['name' => 'reply']), Url::to(['notification/view', 'id' => $model->response_id]), [
                    'title' => 'Ver respuesta',
                    'data-toggle' => 'tooltip',
                ])
            ),
        [
            'class' => 'position-absolute end-0 top-0 mt-1 me-2',
            'title' => $model->getStatus() == Notification::MESSAGE_STATUS_UNREAD
                ? 'No leída'
                : ($model->getStatus() == Notification::MESSAGE_STATUS_READ
                    ? 'Leída'
                    : ''),
            'data-toggle' => 'tooltip',
        ]
    ) ?>
    <p class="mb-0">
        <span><?= Html::a(
                StringHelper::truncateWords($model->title, 10, '...'),
                Url::to(['notification/view', 'id' => $model->id])
            ) ?>
        </span>
        <small class="text-small text-muted">(a <?= $model->receiver->fullname ?>)</small>
        <?= Html::tag('span', Html::tag('small', Yii::$app->formatter->asRelativeTime($model->created_at)), [
            'class' => 'text-small text-muted',
            'title' => Yii::$app->formatter->asDatetime($model->created_at, 'full'),
            'data-toggle' => 'tooltip',
        ]) ?>
    </p>
</div>
