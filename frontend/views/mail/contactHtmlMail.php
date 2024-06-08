<?php
/** @var ContactForm $model */

use frontend\models\ContactForm;

?>
<div class="container">
    <small class="text-muted mb-2">Email desde el formulario de contacto de AdminFin</small>
    <h2>Nombre: <?= $model->name ?></h2>
    <h3 class="mb-2">Asunto: <?= $model->subject ?></h3>
    <div class="mb-2">
        <p>Mensaje:</p>
        <p><?= $model->body ?></p>
    </div>
    <div class="mb-2">
        <p>Email: <?= $model->email ?></p>
    </div>
    <div class="text-muted text-small">
        <p>
            ip: <?= Yii::$app->request->userIP ?>
        </p>
        <p>
            agent: <?= Yii::$app->request->userAgent ?>
        </p>
    </div>
</div>

