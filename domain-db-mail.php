<?php

return [
    'aliases' => [
        '@siteFront' => 'https://adminfin.test',
        '@siteBack' => 'https://users.adminfin.test',
        '@domain' => '.adminfin.test',
    ],
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=adminfindb',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'dsn' => 'smtp://user:pass@smtp.example.com:port'
            ],
        ],
    ],
];
