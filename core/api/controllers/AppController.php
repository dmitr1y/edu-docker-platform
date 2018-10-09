<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 15:57
 */

namespace api\controllers;


use yii\rest\Controller;

class AppController extends Controller
{
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        unset($behaviours['access']);
        return $behaviours;
    }

    public function actionIndex()
    {
        return 'api/app';
    }

    public function actionStart()
    {

    }
}
