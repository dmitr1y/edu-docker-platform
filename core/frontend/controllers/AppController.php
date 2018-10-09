<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:05
 */

namespace frontend\controllers;


use Yii;
use yii\web\Controller;

class AppController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->view->title = 'App';
        return $this->render('index');
    }

    public function actionStart()
    {

    }

    public function actionStop()
    {

    }

    public function actionCreate()
    {
        Yii::$app->view->title = 'Create';
        return $this->render('create');
    }

    public function actionRemove()
    {

    }
}
