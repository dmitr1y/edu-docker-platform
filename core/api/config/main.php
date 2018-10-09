<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'homeUrl' => '/',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            // following line will restrict access to admin controller from frontend application
            'as frontend' => 'dektrium\user\filters\FrontendFilter',
        ],
        'v1' => [
            'class' => 'api\modules\v1\Module',
            'basePath' => '@app/modules/v1',
        ],
    ],
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
//        'user' => [
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
//        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
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
                ['class' => 'yii\rest\UrlRule', 'controller' => 'app'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/app', 'v1/user', 'v1/default']],
            ],
        ],
    ],
    'params' => $params,
];
