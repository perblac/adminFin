<?php

/** @var View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use powerkernel\fontawesome\Icon;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php
        $this->head();
        $this->registerJs(
            '$(document).ready(function() {
                    cookieBanner.init();
                });
            ');
        ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => 'Inicio', 'url' => ['/site/index']],
            ['label' => 'Sobre Nosotros', 'url' => ['/site/about']],
            ['label' => 'Presupuesto', 'url' => ['/site/presupuesto']],
            ['label' => 'Contacto', 'url' => ['/site/contacto']],
        ];
        if (!Yii::$app->user->isGuest) {
            $menuItems[] = [
                'label' => 'Zona de ' . (Yii::$app->user->identity->role === 'admin' ? 'administración' : 'clientes'),
                'url' => Yii::$app->urlManagerBackEnd->createUrl(['/'])
            ];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => $menuItems,
            'encodeLabels' => false,
        ]);
        if (Yii::$app->user->isGuest) {
            echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => ['btn btn-light login text-decoration-none']]), ['class' => ['d-flex']]);
        } else {
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-light logout text-decoration-none text-center']
                )
                . Html::endForm();
        }
        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <div class="float-end text-small">
                <span><?= Html::a('Política de Privacidad', Url::to('privacy-policy')) ?></span>
                <span>Web &copy; <?= Html::encode(Yii::$app->params['developer']) ?> <?= date('Y') ?></span>
            </div>
            <p class="float-start">
                <?php
                $socialMedia = require(Yii::getAlias('@socialMedia'));
                foreach ($socialMedia as $network => $url) {
                    echo Html::tag(
                        'span', Html::a(
                        Icon::widget(
                            ['name' => $network, 'prefix' => 'fab', 'size' => 'fa-2x', 'options' => [
                                'class' => 'text-dark social-media-icon'
                            ]]
                        ), $url
                    ), ['class' => 'mx-2']
                    );
                }
                ?>
            </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
