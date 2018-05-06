<?php
return [
    'timeZone' => 'Europe/Kiev',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@absolute_uploads' => dirname(dirname(dirname(__DIR__))) . '/web/uploads',
        '@temp' => '/web/temp',
        '@absolute_temp' => dirname(dirname(dirname(__DIR__))) . '/web/temp',
        '@root' => __DIR__ . '/../../..',
        '@uploads' => '/web/uploads',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'defaultTimeZone' => 'Europe/Kiev',
       ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql.zzz.com.ua;port=3306;dbname=scifi',
            'username' => 'scifi',
            'password' => 'Q6600g',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'messageConfig' => [
                'from' => ['spinesfan@ukr.net' => 'SaF robot'],
            ],
            'useFileTransport' => false,
            'transport' => [
                'class' =>  'Swift_SmtpTransport',
                'host' => 'smtp.ukr.net',
                'username' => 'spinesfan@ukr.net',
                'password' => 'q6600g470',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],

    ],
];
