<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Notification $model */

$this->title = 'Nueva Notificación';
$this->params['breadcrumbs'][] = ['label' => 'Notificaciones', 'url' => ['notification/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-send container">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-6 mx-lg-auto">
            <?= $this->render('_form-send', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
