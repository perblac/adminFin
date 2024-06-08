<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use backend\models\Notification;
use backend\models\Conversation;

/** @var yii\web\View $this */
/** @var Notification $model */
/** @var Notification $originalNotification */
/** @var Conversation $conversation */
/** @var yii\bootstrap5\ActiveForm $form */

?>

<div class="notification-form">
    <p>En respuesta al mensaje:</p>
    <?= Html::tag('p', $originalNotification->getTitle() )?>
    <?= Html::tag('p', $originalNotification->getMessage() )?>
    <p>de: <?= $originalNotification->getSender()->one()->fullname ?></p>

    <?php
    $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList($model->getOptionTypes()) ?>

    <?= Yii::$app->user->identity->getRole() === 'admin' ? \yii\bootstrap5\Html::checkbox('checkboxCloseConversation', false, ['label' => 'Cerrar conversación'])  : '' ?>

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
