<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate-queue' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                'yii\queue\db\migrations',
            ],
        ],
        'migrate-rbac' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => "@yii/rbac/migrations",
            'migrationNamespaces' => [
                'yii\rbac\migrations',
            ],
        ],
        'migrate-user' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => "@vendor/dektrium/yii2-user/migrations",
            'migrationNamespaces' => [
                'dektrium\user\migrations\Migration',
            ],
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
//        'authManager' => [
//            'class' => 'yii\rbac\DbManager',
//        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableRegistration' => false,
            'enableConfirmation' => false,
        ],
        'rbac' => ['class' => 'dektrium\rbac\RbacConsoleModule'],
    ],
    'params' => $params,
];
