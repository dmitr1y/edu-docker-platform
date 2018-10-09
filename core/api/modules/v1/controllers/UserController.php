<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 18:28
 */

namespace api\modules\v1;

use yii\rest\Controller;

class UserController extends Controller
{
    public $modelClass = 'dektrium\user\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['class'] = HttpBearerAuth::className();
        $behaviors['authenticator']['only'] = ['update'];
        return $behaviors;
    }

    public function actionLogin()
    {

    }

    public function actionLogout()
    {

    }

    public function actionRegister()
    {

    }
}
