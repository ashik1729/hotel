<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
//                ['http_address' => 'http://localhost/capon'],
//                ['http_address' => '127.0.0.1:80/capon'],
                ['http_address' => '127.0.0.1:9200'],
            // configure more hosts if you have a cluster
            ],
//            'autodetectCluster' => false,
            'dslVersion' => 7, // default is 5
        ],
        'CommonRequest' => [
            'class' => 'common\components\CommonRequest'
        ],
        'MailRequest' => [
            'class' => 'common\components\MailRequest'
        ],
        'ManageRequest' => [
            'class' => 'common\components\ManageRequest'
        ],
        'NotificationManager' => [
            'class' => 'common\components\NotificationManager'
        ],
        'LogManagement' => [
            'class' => 'common\components\LogManagement'
        ],
        'Products' => [
            'class' => 'common\components\Products'
        ],
        'Currency' => [
            'class' => 'common\components\Currency'
        ],
        'Order' => [
            'class' => 'common\components\Order'
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'class' => 'common\components\Request',
            'web' => '/frontend/web',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
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
                'user/register' => 'user/register',
                'user/login' => 'user/login',
                'log-out' => 'user/log-out',
                'user/get-new-auth-token' => 'user/get-new-auth-token',
                'user/forgot-password' => 'user/forgot-password',
                'email-updation' => 'user/email-updation',
                'user/activation-success' => 'user/activation-success',
                'notification' => 'notification/notification',
                'notification/<id:\w+(-\w+)*>' => 'notification/notification',
                'notification-type' => 'notification/notification-type',
                'features' => 'features/index',
                'file-assets' => 'home/file-assets',
                'image-types' => 'home/image-types',
                'image-types/<id:\w+(-\w+)*>' => 'home/image-types',
                'get-franchise' => 'franchise/get-franchise',
                'get-access-token' => 'franchise/get-access-token',
                'business-category' => 'category/index',
                'business-category/<id:\w+(-\w+)*>' => 'category/index',
                'business-type' => 'home/business-type',
                'marketing-banners' => 'marketing-banners/index',
                'marketing-banners/<id:\w+(-\w+)*>' => 'marketing-banners/index',
            ],
        ],
    ],
    'params' => $params,
];
