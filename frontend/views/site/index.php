<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'adminfin';
?>
<div class="site-index">
    <div class="container col-xxl-8 px-4">
        <div class="row flex-lg-row-reverse align-items-center g-5 pb-5">
            <div class="col-10 offset-1 col-sm-8 offset-sm-2 offset-lg-0 col-lg-6">
                <?= Html::img('/img/hero.jpg', [
                    'class' => 'd-block mx-lg-auto img-fluid',
                    'width' => '700',
                    'height' => '500',
                    'loading' => 'lazy'
                ]) ?>
            </div>
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold lh-1 mb-3">Bienvenido a adminFin</h1>
                <p class="lead">En adminFin nos dedicamos a la administración de fincas con un enfoque cercano y
                    honesto. Nuestro compromiso es ofrecer un servicio transparente y de calidad, adaptándonos siempre a
                    las necesidades de nuestros clientes. Con nosotros, su comunidad estará en buenas manos, con
                    profesionales comprometidos y dispuestos a brindarle una atención personalizada en cada paso del
                    camino. Confíe en adminFin para una gestión eficiente y confiable de tu propiedad.</p>
            </div>
        </div>
    </div>
</div>
