<?php

namespace backend\controllers;

use backend\models\docker\DockerHealth;
use common\models\app\DockerApps;
use common\models\docker\RunDockerService;
use common\models\docker\StopDockerService;
use common\models\mysql\AppsDbUsers;
use common\models\nginx\CreateNginxConf;
use common\models\nginx\RemoveNginxConf;
use dektrium\user\filters\AccessRule;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site DockerController
 */
class DockerController extends Controller
{
    const STATUS_ERROR = -1;
    const STATUS_STOP = 0;
    const STATUS_UP = 2;

    const ACTION_START = 'start';
    const ACTION_STOP = 'stop';
    const ACTION_LOG = 'log';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'containers',
                            'images',
                            'volumes',
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?', '@'],
                    ],
                ],
                'denyCallback' => function () {
                    Yii::$app->user->setReturnUrl(Yii::$app->request->url);
                    return Yii::$app->response->redirect(['site/login']);
                },
            ],
        ];
    }

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

    /**
     * Отображение статуса контейнеров
     *
     * @return array
     */
    private function getStats()
    {
        return [
            'online' => DockerApps::find()->where(['status' => DockerController::STATUS_UP])->count(),
            'offline' => DockerApps::find()->where(['status' => DockerController::STATUS_STOP])->count(),
            'error' => DockerApps::find()->where(['status' => DockerController::STATUS_ERROR])->count(),
            'db_count' => AppsDbUsers::find()->count(),
        ];
    }

    public function actionSystem()
    {

    }

    /**
     * Отображение списка контейнеров Docker
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionContainers()
    {
        $ps = DockerHealth::getContainersList();

        $form_manager = Yii::$app->request->post('manager');
        $form_service = Yii::$app->request->post('service');

        if (isset($form_manager, $form_service)) {
            $dockerApp = DockerApps::findOne(['service_name' => $form_service]);

            if (empty($dockerApp)) {
                throw new NotFoundHttpException();
            }

            switch ($form_manager) {
                case DockerController::ACTION_START:
                    Yii::$app->queue->push(new RunDockerService([
                        'serviceName' => $form_service,
                        'appModel' => $dockerApp
                    ]));
                    Yii::$app->queue->push(new CreateNginxConf([
                        'serviceName' => $form_service,
                        'servicePort' => $dockerApp->port,
                    ]));
                    break;
                case DockerController::ACTION_STOP:
                    Yii::$app->queue->push(new RemoveNginxConf([
                        'serviceName' => $form_service,
                    ]));
                    Yii::$app->queue->push(new StopDockerService([
                        'serviceName' => $form_service,
                        'appModel' => $dockerApp,
                    ]));
                    break;
                case DockerController::ACTION_LOG:
                    break;
                default:
                    throw new NotFoundHttpException();
                    break;
            }
        }

        return $this->render('containers', ['ps' => $ps]);
    }

    /**
     * Отображение списка образов Docker
     *
     * @return string
     */
    public function actionImages()
    {
        $ps = DockerHealth::getImagesList();

        return $this->render('images', ['ps' => $ps]);
    }

}
