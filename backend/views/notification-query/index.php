<?php

use backend\models\Notification;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\NotificationQuery $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Administrar notificaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Notificación', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sender',
                'label' => 'Emisor',
                'value' => 'sender.username',
            ],
            [
                'attribute' => 'receiver',
                'label' => 'Receptor',
                'value' => 'receiver.username',
            ],
            [
                'attribute' => 'type',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => '(todos)      ',
                ],
                'filter' => $searchModel->getOptionTypes(),
                'value' => function ($model) {
                    return $model->getTypeValue($model->type);
                }
            ],
            [
                'attribute' => 'status',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => '(todos)',
                ],
                'filter' => $searchModel->getOptionStatuses(),
                'value' => function ($model) {
                    return $model->getStatusValue($model->status);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'attribute' => 'conversation',
                'value' => function ($model) {
                    $conversation = $model->getConversation()->one();
                    return $conversation ? $conversation->id : 'null conversation';
                },
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Notification $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
