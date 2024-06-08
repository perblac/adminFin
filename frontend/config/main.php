<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'adminfin',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-adminfin',
                'httpOnly' => true,
                'domain' => Yii::getAlias('@domain'),
            ],
        ],
        'session' => [
            'name' => 'adminfin-login',
            'cookieParams' => [
                'httpOnly' => true,
                'domain' => Yii::getAlias('@domain'),
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'privacy-policy' => 'site/privacy-policy',
            ],
        ],
        'urlManagerBackEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '@siteBack',
            'hostInfo' => '@siteBack',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];
