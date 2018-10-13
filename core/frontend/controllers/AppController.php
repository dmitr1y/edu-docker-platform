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
use common\models\docker\DockerCompose;
use common\models\docker\DockerComposeManager;
use common\models\docker\DockerService;
use common\models\docker\RemoveDockerService;
use common\models\docker\RunDockerService;
use common\models\docker\StopDockerService;
use common\models\nginx\CreateNginxConf;
use common\models\nginx\NginxConf;
use common\models\nginx\RemoveNginxConf;
use Yii;
use yii\db\StaleObjectException;
use yii\queue\db\Queue;
use yii\web\Controller;

class AppController extends Controller
{
    private const domain = "apps.localhost";

    public function actionIndex($id = null, $logFlag = null)
    {
        if (empty($id))
            return $this->redirect(['app/create']);

        $model = Apps::find()->where(['id' => $id])->one();
        $log = null;
        if (!empty($logFlag)) {
            if (!empty($id))
                $log = AppsLog::findOne(['appId' => $model->id]);
        }

        Yii::$app->view->title = $model->name;
        return $this->render('viewApp', ['model' => $model, 'log' => $log]);
    }

    public function actionManager()
    {
        $appName = Yii::$app->request->post('app');
        $action = Yii::$app->request->post('action');
        $id = Yii::$app->request->post('id');

//        todo Вывод ошибки "приложение не найдено"
        if (!isset($appName) || empty($appName))
            return $this->redirect(['app/index']);

        $app = Apps::findOne(['id' => $id]);
//        $appName=DockerService::prepareServiceName($app->name);

        $log = null;

//        todo Проверка запущен ли nginx и БД

        switch ($action) {
            case 'Run':
                Yii::$app->queue->push(new RunDockerService([
                    'serviceName' => $appName,
                    'appModel' => $app
                ]));
                Yii::$app->queue->push(new CreateNginxConf([
                    'serviceName' => $appName,
                    'servicePort' => $app->port,
                ]));
//                return $this->render('processing');

                break;
            case 'Stop':
                Yii::$app->queue->push(new RemoveNginxConf([
                    'serviceName' => $appName
                ]));
                Yii::$app->queue->push(new StopDockerService([
                    'serviceName' => $appName,
                    'appModel' => $app
                ]));
                break;
            case 'Remove':
//                todo Удаление образа (автоочистка каждый день), Dockerfile, БД
                Yii::$app->queue->push(new RemoveDockerService([
                    'serviceName' => $appName,
                    'appModel' => $app
                ]));
                Yii::$app->queue->push(new RemoveNginxConf([
                    'serviceName' => $appName
                ]));
                return $this->render('removed');
                break;
            default:
                throw  new \yii\web\NotFoundHttpException();
                break;
        }

//       todo Вырезать из лога "storage_"
//        todo Добавить перенос строк
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
        $service = new DockerService();

        $service->name = strtolower(preg_replace('/\s+/', '-', $model->name));
//        todo Добавление пути к Dockerfile
        $service->image = $model->image;

        $compose->setService($service->getService());
        $compose->save();

        $model->url = DockerService::prepareServiceName($model->name) . '.' . $this::domain;
        $model->save();
    }
}
