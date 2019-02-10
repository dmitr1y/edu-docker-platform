<?php

namespace frontend\controllers;

use dektrium\user\filters\AccessRule;
use Yii;
use yii\filters\AccessControl;

/**
 * Проверка авторизации пользователя
 *
 * Class AuthController
 * @package frontend\controllers
 */
class AuthController extends \yii\web\Controller
{
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
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Проверка авторизирован ли пользователь
     */
    public function actionIndex()
    {
        $response_code = 403;
        if (!Yii::$app->user->isGuest) {
            $response_code = 200;
        }
        Yii::$app->response->statusCode = $response_code;
    }
}
