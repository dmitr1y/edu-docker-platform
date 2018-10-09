<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 21:45
 */

namespace api\modules\v1\controllers;


use yii\rest\Controller;

class DefaultController extends Controller
{

    public function actionIndex()
    {
        return 'api/v1';
    }
}
