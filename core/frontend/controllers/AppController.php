<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:05
 */

namespace frontend\controllers;

use common\models\app\Apps;
use common\models\app\AppsLog;
use common\models\app\AppsQuery;
use common\models\docker\DockerCompose;
use common\models\docker\DockerComposeManager;
use common\models\docker\DockerService;
use common\models\nginx\NginxConf;
use Yii;
use yii\web\Controller;

class AppController extends Controller
{
    public function actionIndex($id = null, $logFlag = null)
    {
        if (empty($id))
            return $this->redirect(['app/create']);

        $model = Apps::find()->where(['id' => $id])->one();

        if (!empty($logFlag)) {
            if (!empty($id))
                $log = AppsLog::findOne(['appId' => $model->id]);
        }

        Yii::$app->view->title = $model->name;
        return $this->render('viewApp', ['model' => $model, 'log' => $log]);
    }

    public function actionManager()
    {
        $app = Yii::$app->request->post('app');
        $action = Yii::$app->request->post('action');
        $id = Yii::$app->request->post('id');

        if (!isset($app) || empty($app))
            return $this->redirect(['app/index']);
        $log = null;
        $composeManager = new DockerComposeManager();
        $composeManager->up('nginx');
        switch ($action) {
            case 'Run':
                $log = $composeManager->up($app);
                break;
            case 'Stop':
                $log = $composeManager->stop($app);
                break;
            case 'Remove':
                $log = $composeManager->down($app);
                break;
            default:
                break;
        }

        $appLog = AppsLog::findOne(['appId' => $id]);
        if (empty($appLog)) {
            $appLog = new AppsLog();
            $appLog->appId = $id;
        }

        $appLog->build = $log;
        $appLog->save();
        return $this->redirect(['app/index', 'logFlag' => true, 'id' => $id]);
    }

    public function actionCreate()
    {
        Yii::$app->view->title = 'Create';
        $model = new Apps();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $this->createCompose($model);
            return $this->redirect(['app/index', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @param $model Apps
     */
    private function createCompose($model)
    {
        $compose = new DockerCompose();
        $nginxConf = new NginxConf();
        $service = new DockerService();
        $nginxConf->proxyPort = 8000;

        $nginxConf->serviceName = strtolower(preg_replace('/\s+/', '-', $model->name));
        $service->image = "crccheck/hello-world";
        $nginxConf->proxyServer = $service->name = strtolower(preg_replace('/\s+/', '-', $model->name));
        $compose->addService($service->getService());
        $nginxConf->create();
        $compose->save();
        $model->url = strtolower(preg_replace('/\s+/', '-', $model->name)) . '.apps.localhost';
        $model->save();
    }
}
