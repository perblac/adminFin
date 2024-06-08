<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \frontend\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = 'Contacto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact container">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Si tiene alguna pregunta, sugerencia o simplemente desea ponerse en contacto con nosotros, por favor complete
        el siguiente formulario y estaremos encantados de responderle lo antes posible. ¡Gracias por visitar adminFin!
    </p>

    <div class="row">
        <div class="col-lg-5 mx-lg-auto">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'subject') ?>

            <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
