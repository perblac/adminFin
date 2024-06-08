<?php
/** @var yii\web\View $this */
/** @var Conversation $model */
/** @var User $sender */
/** @var User $receiver */

/** @var ArrayDataProvider $dataProvider */

use backend\models\Conversation;
use common\models\User;
use powerkernel\fontawesome\Icon;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\LinkPager;
use yii\data\ArrayDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = ['label' => 'Mis Notificaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Conversación ' . Yii::$app->formatter->asDatetime($model->updated_at, 'long');
?>
<div class="container-fluid">
    <h3>Conversación
        iniciada <?=
        Yii::$app->formatter->asRelativeTime($model->created_at)
        ?> <?=
        $model->getStatus() === Conversation::CONVERSATION_STATUS_CLOSED ?
            'y concluida ' . Yii::$app->formatter->asRelativeTime($model->updated_at)
            : ''
        ?></h3>

    <div class="row">
        <div class="col-6 col-lg-4 offset-lg-1 border-bottom border-light-subtle text-start">
            <?= $sender->getFullname() ?>
        </div>
        <div class="col-6 col-lg-4 offset-lg-2 border-bottom border-light-subtle text-end">
            <?= $receiver->getFullname() ?>
        </div>
    </div>
    <?php Pjax::begin(); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'pager' => [
            'class' => LinkPager::class,
        ],
        'itemView' => function ($model) use ($sender) {
            return $this->render('_conversation-item', [
                'model' => $model,
                'sender' => $sender,
            ]);
        }
    ])
    ?>

    <?php if ($model->getStatus() === Conversation::CONVERSATION_STATUS_CLOSED): ?>
        <div class="row">
            <div class="col-12 border-top border-dark-subtle text-center">
                <?= Html::tag('p', Icon::widget(['name' => 'lock', 'size' => 'fa-lg',]), [
                    'title' => 'Esta conversación está cerrada',
                    'data-toggle' => 'tooltip',
                ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($model->getStatus() === Conversation::CONVERSATION_STATUS_OPEN): ?>
        <?=
        Yii::$app->user->identity->getRole() === 'admin' ?
            Html::a(Icon::widget([
                    'name' => 'lock',
                    'size' => 'fa-lg',
                    'options' => [
                        'class' => 'pe-2'
                    ]
                ]) . ' Cerrar conversación', Url::to([
                'notification/close-conversation',
                'id' => $model->id
            ]), ['class' => 'btn btn-primary'])
            : ''
        ?>
    <?php endif; ?>
    <?php if ($model->getStatus() === Conversation::CONVERSATION_STATUS_CLOSED): ?>
        <?=
        Yii::$app->user->identity->getRole() === 'admin' ?
            Html::a(Icon::widget([
                    'name' => 'lock-open',
                    'size' => 'fa-lg',
                    'options' => [
                        'class' => 'pe-2'
                    ]
                ]) . ' Reabrir conversación', Url::to([
                'notification/reopen-conversation',
                'id' => $model->id
            ]), ['class' => 'btn btn-primary'])
            : ''
        ?>
    <?php endif; ?>
    <?php Pjax::end(); ?>
</div>
