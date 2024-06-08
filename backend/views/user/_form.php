<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="user-form container">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-6 mx-lg-auto">

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'fullname') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'address') ?>
            <?= $form->field($model, 'phone1') ?>
            <?= $form->field($model, 'phone2') ?>
            <?= $form->field($model, 'role')->dropDownList(['admin' => 'Administrador', 'client' => 'Cliente']) ?>
            <?= $form->field($model, 'status')->dropDownList([10 => 'Activa', 9 => 'Inactiva', 0 => 'Eliminada']) ?>

            <div class="form-group mt-2">
                <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
