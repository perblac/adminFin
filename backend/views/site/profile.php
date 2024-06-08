<?php

/** @var yii\web\View $this */

/** @var User $model */

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

$this->title = 'Mi Perfil';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile container">
    <div class="row">
        <div class="col-lg-6">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(['id' => 'formProfile']) ?>
            <?= $form->field($model, 'fullname')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'address') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'phone1') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'phone2') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
    <div class="row mt-2">
        <div class="col-lg-6">
            <div class="form-group">
                <?= Html::a(
                    'Restablecer Contraseña',
                    Url::to('request-password-reset'),
                    ['class' => 'btn btn-primary', 'name' => 'reset-password-button']
                ) ?>
            </div>
        </div>
    </div>
</div>
