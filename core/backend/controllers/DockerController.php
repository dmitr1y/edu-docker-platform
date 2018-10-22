<?php

namespace backend\controllers;

use common\models\LoginForm;
use yii\web\Controller;

/**
 * Site DockerController
 */
class DockerController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


}
