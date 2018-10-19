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
//    public function behaviors()
//    {
//        return [
//            'corsFilter' => [
//                'class' => \yii\filters\Cors::className(),
//                'cors' => [],
//                'actions' => [
//                    'incoming' => [
//                        'Origin' => ['*'],
//                        'Access-Control-Request-Method' => ['GET'],
//                        'Access-Control-Request-Headers' => ['*'],
//                        'Access-Control-Allow-Credentials' => null,
//                        'Access-Control-Max-Age' => 86400,
//                        'Access-Control-Expose-Headers' => [],
//                    ],
//                ],
//            ],
//        ];
//    }

//    public function init()
//    {
//        parent::init();
//        \Yii::$app->user->enableSession = false;
//    }
//
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => CompositeAuth::className(),
//            'authMethods' => [
//                HttpBasicAuth::className(),
//                HttpBearerAuth::className(),
//                QueryParamAuth::className(),
//            ],
//        ];
//        return $behaviors;
//    }

    public function actionIndex($args = null)
    {
        $data = null;
//        $data['args'] = $args;
//        $data['headers'] = $headers = Yii::$app->response->headers;
//        $data['username'] = $username = Yii::$app->user->id;
//        $authHeader = Yii::$app->request->getHeaders();
        $data['request'] = $req = Yii::$app->request;
//        $data['authUser'] = Yii::$app->request->authUser;
        $data['$_SERVER'] = $_SERVER;
//        $data['session'] = Yii::$app->session;
        $data['$_COOKIES'] = Yii::$app->response->cookies;

        file_put_contents(realpath(\Yii::$app->basePath . '/../../storage/user_apps') . '/auth.txt', json_encode($data, JSON_PRETTY_PRINT));

//        $headers->add('X-Username', $username);

        Yii::$app->response->statusCode = 200;
    }
}
