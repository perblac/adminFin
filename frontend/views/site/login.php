<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var LoginForm $model */

use common\models\LoginForm;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login container">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor rellene los siguientes campos para acceder:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Si ha olvidado su contraseña, puede <?= Html::a('restablecerla', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
