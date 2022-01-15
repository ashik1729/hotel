<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'products' => [
            'class' => 'backend\modules\products\Module',
            'layout' => '@backend/views/layouts/main',
//            'roles' => ['Admin', 'Editor'],
//            'defaultPageLayout' => '@@backend/layouts/main',
//            'availableRoutes' => [
//                'site/index' => 'Index Route',
//            ],
//            'availableViews' => [
//                '@backend/views/site/index.php' => 'Index View',
//            ],
        ],
        //ok
        'order' => [
            'class' => 'backend\modules\order\Module',
            'layout' => '@backend/views/layouts/main',
        ],
        'users' => [
            'layout' => '@backend/views/layouts/main',
            'class' => 'backend\modules\users\Module',
        ],
        'filemanagement' => [
            'class' => 'backend\modules\filemanagement\Module',
            'layout' => '@backend/views/layouts/main',
        ],
        'marketing' => [
            'class' => 'backend\modules\marketing\Module',
            'layout' => '@backend/views/layouts/main',
        ],
        'masters' => [
            'class' => 'backend\modules\masters\Module',
        ],
    ],
    'components' => [
        'SelectCategory' => [
            'class' => 'common\components\SelectCategory',
        ],
//        'IdentitySwitcher' => [
//            'class' => 'common\components\IdentitySwitcher'
//        ],
        'CommonRequest' => [
            'class' => 'common\components\CommonRequest'
        ],
        'Order' => [
            'class' => 'common\components\Order'
        ],
        'Currency' => [
            'class' => 'common\components\Currency'
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
        'FileManagement' => [
            'class' => 'common\components\FileManagement'
        ],
        'Products' => [
            'class' => 'common\components\Products'
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'class' => 'common\components\Request',
            'web' => '/backend/web',
            'adminUrl' => '/admin',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
        ],
        'user' => [
            'identityClass' => 'common\models\UserAdmin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
