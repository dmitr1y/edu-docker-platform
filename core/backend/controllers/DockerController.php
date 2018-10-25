<?php

namespace backend\controllers;

use common\models\app\Apps;
use common\models\LoginForm;
use common\models\mysql\AppsDbUsers;
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
        $stat = $this->getStats();
        return $this->render('index', ['stats' => $stat]);
    }

    private function getStats()
    {
        return [
            'online' => Apps::find()->where(['status' => 2])->count(),
            'offline' => Apps::find()->where(['status' => 0])->count(),
            'error' => Apps::find()->where(['status' => -1])->count(),
            'db_count' => AppsDbUsers::find()->count(),
        ];
    }

    public function actionSystem()
    {

    }
}
