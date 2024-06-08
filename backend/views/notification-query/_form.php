<?php

/** @var yii\web\View $this */
/** @var Notification $model */
/** @var ActiveForm $form */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use backend\models\Notification;

$optionConv = $model->getOptionConversations();
$optionConv[0] = 'Nueva';
?>

<div class="notification-form">

    <?php
    $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'sender_id')->dropDownList($model->getOptionUsers(), ['prompt' => 'Escoja emisor']) ?>

    <?= $form->field($model, 'receiver_id')->dropDownList($model->getOptionUsers(), ['prompt' => 'Escoja receptor']) ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList($model->getOptionTypes()) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getOptionStatuses()) ?>

    <?= $form->field($model, 'conversation')->dropDownList($optionConv) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
