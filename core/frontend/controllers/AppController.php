<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:05
 */

namespace frontend\controllers;

use common\models\app\Apps;
use common\models\app\AppsQuery;
use common\models\docker\DockerCompose;
use common\models\docker\DockerComposeManager;
use common\models\docker\DockerService;
use common\models\nginx\NginxConf;
use Yii;
use yii\web\Controller;

class AppController extends Controller
{
    public function actionIndex($id = null, $log = null)
    {


        if (empty($id))
            return $this->redirect(['app/create']);

        $model = Apps::find()->where(['id' => $id])->one();

        Yii::$app->view->title = $model->name;
        return $this->render('viewApp', ['model' => $model, 'log' => $log]);
    }

    public function actionStart($service = null)
    {
        $composeManager = new DockerComposeManager();
        $log = null;
        if (!empty($service))
            $log = $composeManager->up($service);
        else
            $log = $composeManager->up();
        return $this->redirect(['app/index', 'log' => $log]);
    }

    public function actionStop()
    {

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

    public function actionRemove()
    {

    }
}
