<?php

namespace frontend\controllers;

use Yii;

class AuthController extends \yii\web\Controller
{
//    public function beforeAction($action)
//    {
//        if (in_array($action->id, ['incoming'])) {
//            $this->enableCsrfValidation = false;
//        }
//        return parent::beforeAction($action);
//    }
//
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [],
                'actions' => [
                    'incoming' => [
                        'Origin' => ['*'],
                        'Access-Control-Request-Method' => ['GET'],
                        'Access-Control-Request-Headers' => ['*'],
                        'Access-Control-Allow-Credentials' => null,
                        'Access-Control-Max-Age' => 86400,
                        'Access-Control-Expose-Headers' => [],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
//        $data = null;
//        $data ['COOKIE'] = $_SERVER['HTTP_COOKIE'];
//        $data ['user id'] = Yii::$app->user->id;
//        $data['session'] = $session = Yii::$app->session->getId();
//        $data ['guest check'] = Yii::$app->user->isGuest;
//        file_put_contents(realpath(\Yii::$app->basePath . '/../../storage/user_apps') . '/auth.txt', json_encode($data, JSON_PRETTY_PRINT));

        $response_code = 403;
        if (!Yii::$app->user->isGuest) {
            $response_code = 200;
        }
        Yii::$app->response->statusCode = $response_code;
    }
}
