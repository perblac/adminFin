<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Notification $model */

$this->title = 'Editar Notificación: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notificaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="notification-update container">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-6 mx-lg-auto">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
