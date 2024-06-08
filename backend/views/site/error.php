<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception*/

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        El error indicado se produjo mientras el servidor Web procesaba su solicitud.
    </p>
    <p>
        Póngase en contacto con nosotros si cree que se trata de un error del servidor. Gracias.
    </p>

</div>
