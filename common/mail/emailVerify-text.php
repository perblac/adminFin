<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var string $pass */

$verifyLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
¡Bienvenido <?= $user->fullname ?>!

Su usuario es <?= $user->username ?> y su contraseña <?= $pass ?>.

Siga el siguiente enlace para verificar su correo electrónico:

<?= $verifyLink ?>
