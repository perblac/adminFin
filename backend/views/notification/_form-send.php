<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use backend\models\Notification;

/** @var yii\web\View $this */
/** @var Notification $model */
/** @var yii\bootstrap5\ActiveForm $form */

$optionReceivers = Yii::$app->user->identity->role === 'admin' ? $model->getOptionUsers('client') : $model->getOptionUsers('admin');
?>

<div class="notification-form">

    <?php
    $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'receiver_id')->dropDownList($optionReceivers, ['prompt' => 'Escoja destinatario']) ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList($model->getOptionTypes()) ?>

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
