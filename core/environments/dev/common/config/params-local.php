<?php
return [
//    Данные доступа к основной БД
    'mainDbUsername' => 'root',
    'mainDbPassword' => 'password',
    'mainDbHost' => 'mysql_db',
    'mainDbName' => 'edu_db',

//    Данные доступа к БД для пользовательских приложений
    'userDbUsername' => 'root',
    'userDbPassword' => 'password',
    'userDbHost' => 'mysql',
    'userDbName' => 'apps_db',

//    Настройки mailer
    'mailHost' => '',
    'noreplyEmail' => '',
    'noreplyEmailPass' => '',
    'noreplyEmailTitle' => '',

//    Префикс для пользовательских приложений hostname/app/some-app
//    При изменениях также необходимо изменить в docker/nginx/confs/default.conf
    'app_host' => 'app',
];
