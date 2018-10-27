<?php
$params = require(__DIR__ . '/params-local.php');

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'queue', // Компонент регистрирует свои консольные команды
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'queue' => [
            'as log' => \yii\queue\LogBehavior::class,
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // Компонент подключения к БД или его конфиг
            'tableName' => '{{%queue}}', // Имя таблицы
            'channel' => 'default', // Выбранный для очереди канал
            'mutex' => \yii\mutex\MysqlMutex::class, // Мьютекс для синхронизации запросов
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . $params['mainDbHost'] . ';dbname=' . $params['mainDbName'],
            'username' => $params['mainDbUsername'],
            'password' => $params['mainDbPassword'],
            'charset' => 'utf8',
        ],
        'db2' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . $params['userDbHost'] . ';port=3307;dbname=' . $params['userDbName'],
            'username' => $params['userDbUsername'],
            'password' => $params['userDbPassword'],
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
//            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $params['mailHost'],
                'username' => $params['noreplyEmail'],
                'password' => $params['noreplyEmailPass'],
                'port' => 587,
                'encryption' => 'TLS',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => [$params['noreplyEmail'] => $params['noreplyEmailTitle']],
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableConfirmation' => true,
            'enableUnconfirmedLogin' => false,
            'mailer' => [
                'sender' => [$params['noreplyEmail'] => $params['noreplyEmailTitle']],
            ]
        ],
        'rbac' => ['class' => 'dektrium\rbac\RbacWebModule'],
    ],
];
