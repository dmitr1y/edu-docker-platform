<?php

namespace backend\controllers;

use backend\models\docker\DockerHealth;
use common\models\app\DockerApps;
use common\models\docker\RunDockerService;
use common\models\docker\StopDockerService;
use common\models\LoginForm;
use common\models\mysql\AppsDbUsers;
use common\models\nginx\CreateNginxConf;
use common\models\nginx\RemoveNginxConf;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        $ps = DockerHealth::getContainersList();

        $form_manager = Yii::$app->request->post('manager');
        $form_service = Yii::$app->request->post('service');

        if (isset($form_manager, $form_service)) {
            $dockerApp = DockerApps::findOne(['service_name' => $form_service]);

            if (empty($dockerApp))
                throw new NotFoundHttpException();

            switch ($form_manager) {
                case 'start':
                    Yii::$app->queue->push(new RunDockerService([
                        'serviceName' => $form_service,
                        'appModel' => $dockerApp
                    ]));
                    Yii::$app->queue->push(new CreateNginxConf([
                        'serviceName' => $form_service,
                        'servicePort' => $dockerApp->port,
                    ]));
                    break;
                case 'stop':
                    Yii::$app->queue->push(new RemoveNginxConf([
                        'serviceName' => $form_service,
                    ]));
                    Yii::$app->queue->push(new StopDockerService([
                        'serviceName' => $form_service,
                        'appModel' => $dockerApp,
                    ]));
                    break;
                case 'log':
                    break;
                default:
                    throw new NotFoundHttpException();
                    break;
            }
        }

        return $this->render('containers', ['ps' => $ps]);
    }

    public function actionImages()
    {
        $ps = DockerHealth::getImagesList();

        return $this->render('images', ['ps' => $ps]);
    }

}
