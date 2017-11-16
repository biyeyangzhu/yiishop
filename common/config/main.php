<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager'=>[
            'class'=>'yii\rbac\DbManager',
        ],
       /* 'redis'=>[
            'class'=>'yii\redis\Connection',
            'hostname'=>'127.0.0.1',
            'port'=>'6379',
            'database'=>'0'
        ],
        'cache'=>[
            'class'=>'yii\redis\Cache',
        ],
        'session'=>[
            'class'=>'yii\redis\Session',
        ]*/
    ],
];
