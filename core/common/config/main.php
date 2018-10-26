<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'queue', // Компонент регистрирует свои консольные команды
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        'authManager' => [
//            'class' => 'yii\rbac\DbManager',
//        ],
        'queue' => [
            'as log' => \yii\queue\LogBehavior::class,
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // Компонент подключения к БД или его конфиг
            'tableName' => '{{%queue}}', // Имя таблицы
            'channel' => 'default', // Выбранный для очереди канал
            'mutex' => \yii\mutex\MysqlMutex::class, // Мьютекс для синхронизации запросов
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableConfirmation' => false,
            'enableUnconfirmedLogin' => true,
//            'controllerMap' => [
//                'security' => [
//                    'class' => \dektrium\user\controllers\SecurityController::className(),
//                    'on ' . \dektrium\user\controllers\SecurityController::EVENT_AFTER_LOGIN => function ($e) {
//                        if (!\Yii::$app->session->has("backURL")) {
//                            Yii::$app->response->redirect(array(preg_split("/" . \Yii::$app->request->getHostName() . "/", \Yii::$app->request->referrer)))->send();
//                        } else
//                            Yii::$app->response->redirect(array('/site/index'))->send();
//                        Yii::$app->end();
//                    }
//                ],
//            ],
        ],
        'rbac' => ['class' => 'dektrium\rbac\RbacWebModule'],
    ],
];
