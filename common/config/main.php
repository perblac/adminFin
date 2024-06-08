<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@siteFront' => 'https://adminfin.test',
        '@siteBack' => 'https://users.adminfin.test',
        '@socialMedia' => '@common/config/social-media.php',
    ],
    'language' => 'es_ES',
    'name' => 'adminFin',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'formatter' => [
            'class' => \yii\i18n\Formatter::class,
            'timeZone' => 'Europe/Madrid',
        ],
    ],
];
