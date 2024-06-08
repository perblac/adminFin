<?php

/** @var View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\View;

AppAsset::register($this);
$unreadNotifications = Yii::$app->user->identity->getUnreadNotifications();
$numUnread = sizeof($unreadNotifications);
$menuNotificationsLabel =
    $numUnread > 0
    ? 'Notificaciones <span class="top-0 badge rounded-pill bg-danger border border-light">'.($numUnread>9?'9+':$numUnread).'</span>'
    : 'Notificaciones';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' =>  Yii::$app->urlManagerFrontEnd->createUrl(['/']),
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Inicio', 'url' => ['/site/index']],
    ];
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'admin') {
        $menuItems[] = ['label' => 'Perfil', 'url' => ['/site/profile']];
        $menuItems[] = ['label' => 'Panel de Control', 'items' => [
            ['label' => $menuNotificationsLabel, 'url' => ['/notification/index']],
            ['label' => 'Registrar cliente', 'url' => ['/site/create-account']],
            ['label' => 'Administrar usuarios', 'url' => ['/user/index']],
            ['label' => 'Administrar notificaciones', 'url' => ['/notification-query/index']],
        ]];
        } elseif (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'client') {
        $menuItems[] = ['label' => 'Perfil', 'url' => ['/site/profile']];
        $menuItems[] = ['label' => $menuNotificationsLabel, 'url' => ['/notification/index']];

    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-light login text-decoration-none']]),['class' => ['d-flex']]);
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
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-end text-small">Web &copy; <?= Html::encode(Yii::$app->params['developer']) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
