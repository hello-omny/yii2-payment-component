<?php

use yii\helpers\ArrayHelper;

return [
    'id' => 'payment-test',
    'name' => 'Payment test',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__) . '/var',

    'aliases' => [
        '@vendor' => dirname(__DIR__) . '/vendor',
        '@runtime' => dirname(__DIR__) . '/var',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'bootstrap' => [
        'log',
    ],

    'container' => [],

    'components' => [
        'db' => ArrayHelper::merge(
            [
                'class' => 'yii\db\Connection',
                'charset' => 'utf8',
                'dsn' => 'mysql:host=localhost;dbname=payment_test',
                'username' => 'root',
                'password' => 'password',
                'queryCache' => 'cache',
                'enableQueryCache' => true,
                'schemaCache' => 'cache',
                'schemaCacheDuration' => 60,
                'enableSchemaCache' => true,
            ],
            is_file(__DIR__ . '/config/local/db.php') ? require __DIR__ . '/config/local/db.php' : []
        ),

//        'user' => [
//            'identityClass' => User::class,
//            'enableAutoLogin' => true,
//            'loginUrl' => ['user/login'],
//        ],

        'cache' => ['class' => 'yii\caching\DummyCache',],
        'log' => ['class' => 'yii\log\Dispatcher',],
    ],

    'params' => [],
];
