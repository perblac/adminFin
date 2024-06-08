<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hola <?= Html::encode($user->fullname) ?>,</p>

    <p>Puede acceder al siguiente enlace para restablecer su contraseña:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
