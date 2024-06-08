<?php

/** @var yii\web\View $this */

use yii\bootstrap5\LinkPager;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var ArrayDataProvider $dataProviderReceived */
/** @var ArrayDataProvider $dataProviderSent */

$this->title = 'Mis Notificaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-notifications">
    <div class="row justify-content-between">
        <h1 class="col-6 text-nowrap"><?= Html::encode($this->title) ?></h1>
        <div class="col-6 text-end align-middle">
            <?= Html::a('Nueva notificación', Url::to(['notification/send']), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div>
        <h3 class="border-bottom border-dark-subtle">
            Recibidas
        </h3>
        <div class="row">
            <div class="col-lg-8 mx-lg-auto">
                <?php Pjax::begin(); ?>
                <?= ListView::widget([
                    'dataProvider' => $dataProviderReceived,
                    'layout' => "{items}\n{pager}",
                    'pager' => [
                        'class' => LinkPager::class,
                    ],
                    'itemView' => function ($model) {
                        return $this->render('_received-notification', [
                            'model' => $model,
                        ]);
                    }
                ])
                ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
    <div>
        <h3 class="border-bottom border-dark-subtle">
            Enviadas
        </h3>
        <div class="row">
            <div class="col-lg-8 mx-lg-auto">
                <?php Pjax::begin(); ?>
                <?= ListView::widget([
                    'dataProvider' => $dataProviderSent,
                    'layout' => "{items}\n{pager}",
                    'pager' => [
                        'class' => LinkPager::class,
                    ],
                    'itemView' => function ($model) {
                        return $this->render('_sent-notification', [
                            'model' => $model,
                        ]);
                    }
                ])
                ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
