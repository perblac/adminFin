<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Sobre Nosotros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                    aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <?= Html::img(['/img/carousel1.jpg'], [
                    'aria-hidden' => 'true',
                    'focusable' => 'false'
                ]) ?>
                <div class="container">
                    <div class="carousel-caption text-start">
                        <h1>¡Bienvenidos a <?= Yii::$app->name ?>!</h1>
                        <p>Somos un equipo de administradores de fincas colegiados con más de 20 años de experiencia en
                            el sector. Nuestro compromiso es brindar un servicio integral y personalizado a nuestros
                            clientes, garantizando una gestión eficiente y transparente de sus propiedades.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <?= Html::img(['/img/carousel2.jpg'], [
                    'aria-hidden' => 'true',
                    'focusable' => 'false'
                ]) ?>
                <div class="container">
                    <div class="carousel-caption">
                        <p>En <?= Yii::$app->name ?>, nos dedicamos a gestionar de manera eficaz todos los aspectos
                            relacionados con la administración de fincas, desde la gestión de los gastos comunes hasta
                            la resolución de incidencias, siempre con el objetivo de garantizar la tranquilidad y el
                            bienestar de nuestros clientes.</p>
                        <p>Vea un presupuesto aproximado en base a su comunidad:</p>
                        <p><a class="btn btn-lg btn-primary" href="<?= Url::to('/site/presupuesto') ?>">Presupuesto</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <?= Html::img(['/img/carousel3.jpg'], [
                    'aria-hidden' => 'true',
                    'focusable' => 'false'
                ]) ?>
                <div class="container">
                    <div class="carousel-caption text-end">
                        <h1>Contacte con nosotros</h1>
                        <p>Nuestra experiencia y profesionalismo nos avalan. ¡Confíe en <?= Yii::$app->name ?> para la
                            gestión de su comunidad y disfrute de un servicio de calidad y confianza!</p>
                        <p><a class="btn btn-lg btn-primary"
                              href="<?= Url::to('/site/about') ?>">Contacto</a></p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <div class="container marketing">

        <div class="row">
            <div class="col-lg-4">
                <?= Html::img('/img/juan.jpg', [
                    'class' => 'rounded-circle',
                    'width' => 140,
                    'height' => 140,
                    'aria-label' => 'Juan Pérez Rodríguez, administrador de fincas',
                    'focusable' => false
                ]) ?>

                <h2>Juan Pérez Rodríguez</h2>
                <p>Administrador de fincas con más de 10 años de experiencia en el sector inmobiliario. Juan se destaca
                    por su eficiencia en la gestión de comunidades de vecinos y su capacidad para resolver conflictos de
                    manera eficaz. Su compromiso con la transparencia y la ética profesional lo convierten en un activo
                    invaluable para adminFin.</p>
            </div>
            <div class="col-lg-4">
                <?= Html::img('/img/marina.jpg', [
                    'class' => 'rounded-circle',
                    'width' => 140,
                    'height' => 140,
                    'aria-label' => 'Marina García Nuñez, administrativa',
                    'focusable' => false
                ]) ?>

                <h2>Marina García Nuñez</h2>
                <p>Siempre sonriente y dispuesta a ayudar a sus compañeros, Marina se encarga de gestionar los aspectos
                    administrativos de la empresa con eficacia y profesionalismo. Su pasión por su trabajo y su
                    compromiso con la excelencia la hacen destacar en su rol dentro de la compañía.</p>
            </div>
            <div class="col-lg-4">
                <?= Html::img('/img/joseluis.jpg', [
                    'class' => 'rounded-circle',
                    'width' => 140,
                    'height' => 140,
                    'aria-label' => 'José Luis Fernández Montoya, gerente y fundador de adminFin',
                    'focusable' => false
                ]) ?>

                <h2>José Luis Fernández Montoya</h2>
                <p>Gerente y fundador de adminFin. Con más de 20 años de experiencia en el sector, José Luis es conocido
                    por su integridad y dedicación hacia sus clientes. Siempre comprometido con brindar un servicio de
                    excelencia, se destaca por su capacidad para resolver problemas de manera eficiente y su pasión por
                    ayudar a sus clientes a maximizar el valor de sus propiedades. Sin duda, José Luis es el aliado
                    perfecto para aquellos que buscan una administración eficaz y confiable para sus fincas.</p>
            </div>
        </div>

        <!-- START THE FEATURETTES -->

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7">
                <h2 class="featurette-heading">Optimiza la gestión de tu comunidad. <span
                            class="text-muted">Expertos en garantizar tu tranquilidad</span>
                </h2>
                <p class="lead">¡Confía en adminFin para mantener tu propiedad en perfectas condiciones y con una
                    gestión eficiente!</p>
            </div>
            <div class="col-md-5">
                <?= Html::img('/img/featurette1.jpg', [
                    'class' => 'featurette-image img-fluid mx-auto',
                    'width' => 500,
                    'height' => 500,
                    'focusable' => false
                ]) ?>
            </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7 order-md-2">
                <h2 class="featurette-heading">La solución integral para la administración de fincas. <span
                            class="text-muted">Profesionalidad y transparencia.</span></h2>
                <p class="lead">Con adminFin, tendrás a tu disposición un equipo de expertos que se ocupará de todos los
                    aspectos de la administración de tu finca, de forma eficaz y transparente.</p>
            </div>
            <div class="col-md-5 order-md-1">
                <?= Html::img('/img/featurette2.jpg', [
                    'class' => 'featurette-image img-fluid mx-auto',
                    'width' => 500,
                    'height' => 500,
                    'focusable' => false
                ]) ?>
            </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7">
                <h2 class="featurette-heading">Garantiza la rentabilidad de tu propiedad. <span class="text-muted">Maximiza el valor de tu inversión.</span>
                </h2>
                <p class="lead">AdminFin se encarga de optimizar los recursos de tu finca para garantizar su
                    rentabilidad y mantenimiento a largo plazo.</p>
            </div>
            <div class="col-md-5">
                <?= Html::img('/img/featurette3.jpg', [
                    'class' => 'featurette-image img-fluid mx-auto',
                    'width' => 500,
                    'height' => 500,
                    'focusable' => false
                ]) ?>
            </div>
        </div>

        <hr class="featurette-divider">

        <!-- /END THE FEATURETTES -->
    </div>
