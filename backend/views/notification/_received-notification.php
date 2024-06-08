<?php
/** @var Notification $model */

use backend\models\Notification;
use powerkernel\fontawesome\Icon;
use yii\bootstrap5\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

?>
<div <?= 'class="p-2 m-2 position-relative rounded border '
. ($model->getStatus() == Notification::MESSAGE_STATUS_UNREAD
    ? 'border-warning bg-warning-subtle'
    : 'border-primary')
. '"' ?>
>
    <p class="position-absolute end-0 top-0 mt-1 me-2">
        <?php
        switch ($model->getStatus()) {
            case Notification::MESSAGE_STATUS_UNREAD:
                echo Html::a(Icon::widget(['name' => 'envelope']), Url::to(['notification/view', 'id' => $model->id]), [
                    'title' => 'Abrir notificación',
                    'data-toggle' => 'tooltip',
                ]);
                break;
            case Notification::MESSAGE_STATUS_READ:
                if (Yii::$app->user->identity->role === 'admin') {
                    echo Html::a(Icon::widget(['name' => 'envelope-open']), Url::to(['notification/undo-read', 'id' => $model->id]), [
                        'title' => 'Marcar como no leída',
                        'data-toggle' => 'tooltip',
                    ]);
                } else {
                    echo Html::tag('span', Icon::widget(['name' => 'envelope-open']), [
                        'title' => 'Leída',
                        'data-toggle' => 'tooltip',
                    ]);
                }
                break;
            case Notification::MESSAGE_STATUS_REPLIED:
                echo Html::tag('span', Icon::widget(['name' => 'reply']), [
                    'title' => 'Respondida',
                    'data-toggle' => 'tooltip',
                ]);
                break;
        }
        ?>
    </p>
    <p class="mb-0">
        <span><?= Html::a(StringHelper::truncateWords($model->title, 10, '...'), Url::to(['notification/view', 'id' => $model->id])) ?></span>
        <small class="text-small text-muted">(de <?= $model->sender->fullname ?>)</small>
        <?= Html::tag('span', Html::tag('small', Yii::$app->formatter->asRelativeTime($model->created_at)), [
            'class' => 'text-small text-muted',
            'title' => Yii::$app->formatter->asDatetime($model->created_at, 'full'),
            'data-toggle' => 'tooltip',
        ]) ?>
    </p>
</div>
