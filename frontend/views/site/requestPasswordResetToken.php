<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var PasswordResetRequestForm $model */

use frontend\models\PasswordResetRequestForm;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Solicitar restablecimiento de contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor, introduzca su dirección de correo electrónico. Se le enviará un enlace para restablecer la contraseña.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
