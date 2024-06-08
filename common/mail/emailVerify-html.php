<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var string $pass */

$verifyLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <h3>¡Bienvenido <?= Html::encode($user->fullname) ?>!</h3>

    <p>Su usuario es <?= Html::encode($user->username) ?> y su contraseña <?= Html::encode($pass) ?>.</p>

    <p>Siga el siguiente enlace para verificar su correo electrónico:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
