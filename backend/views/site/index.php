<?php

/** @var yii\web\View $this */

$this->title = 'adminFin - Zona de '.(Yii::$app->user->identity->role === 'admin' ? 'administración' : 'clientes');
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">adminFin</h1>
        <hr>
        <p class="lead">Zona de <?= Yii::$app->user->identity->role === 'admin' ? 'administración' : 'clientes' ?></p>
    </div>
</div>
