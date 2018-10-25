<?php

namespace backend\controllers;

use backend\models\docker\DockerHealth;
use common\models\app\DockerApps;
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
            'online' => DockerApps::find()->where(['status' => 2])->count(),
            'offline' => DockerApps::find()->where(['status' => 0])->count(),
            'error' => DockerApps::find()->where(['status' => -1])->count(),
            'db_count' => AppsDbUsers::find()->count(),
        ];
    }

    public function actionSystem()
    {

    }

    public function actionContainers()
    {
        $stat = $this->getStats();
        $ps = DockerHealth::getStatus();
        return $this->render('index', ['stats' => $stat, 'ps' => $ps]);
    }

}
