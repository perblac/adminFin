<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var ResetPasswordForm $model */

use backend\models\ResetPasswordForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reenviar correo electrónico de verificación';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-resend-verification-email">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Introduzca su dirección de correo electrónico. Se le enviará un correo electrónico de verificación.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>