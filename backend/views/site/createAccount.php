<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var CreateAccountForm $model */

use backend\models\CreateAccountForm;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Registrar nuevo cliente';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup container">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor rellene los siguientes campos:</p>
    <div class="row">
        <div class="col-lg-6 mx-lg-auto">
            <?php $form = ActiveForm::begin(['id' => 'form-create-account']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'fullname') ?>
            <?= $form->field($model, 'address') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 ms-lg-auto">
            <?= $form->field($model, 'phone1') ?>
        </div>
        <div class="col-lg-3 me-lg-auto">
            <?= $form->field($model, 'phone2') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mx-lg-auto">
            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
