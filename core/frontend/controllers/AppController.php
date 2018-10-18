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
use common\models\docker\DockerService;
use common\models\docker\RemoveDockerService;
use common\models\docker\RunDockerService;
use common\models\docker\StopDockerService;
use common\models\DockerfileUploadForm;
use common\models\nginx\CreateNginxConf;
use common\models\nginx\NginxConf;
use common\models\nginx\RemoveNginxConf;
use common\models\StaticAppUploadForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;

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
                    'appModel' => $app,
                    'userId' => Yii::$app->user->id
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

        $appLog->log = $log;
        $appLog->save();
        return $this->redirect(['app/index', 'logFlag' => true, 'id' => $id]);
    }

    public function actionCreate()
    {
        Yii::$app->view->title = 'Create';
        $model = new Apps();
        $modelUpload = new DockerfileUploadForm();


        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->isUnique()) {
            $modelUpload->dockerfile = UploadedFile::getInstance($model, 'file');
            $model->save();
            $dockerfilePath = $modelUpload->upload(Yii::$app->user->id, $model->id);
            if (!empty($dockerfilePath)) {
                $model->file = $dockerfilePath;
                if (!empty($model->image))
                    $model->image = null;
            }
            $model->save();
            $this->createCompose($model);
            return $this->redirect(['app/index', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model, 'modelUpload' => $modelUpload]);
    }

    public function actionCreateStatic()
    {
        Yii::$app->view->title = 'Create static app';
        $model = new Apps();
        $modelUpload = new StaticAppUploadForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->isUnique()) {
            $modelUpload->app = UploadedFile::getInstance($model, 'file');
            $model->save();
            $appPath = $modelUpload->upload(Yii::$app->user->id, DockerService::prepareServiceName($model->name));
            if (!empty($appPath)) {
                $model->file = $appPath;
            }
            $model->save();
            $this->createStatic($model);
            return $this->redirect(['app/index', 'id' => $model->id]);
        }
        return $this->render('createStatic', ['model' => $model]);
    }

    /**
     * @param $appModel Apps
     * @return bool
     */
    private function createCompose($appModel)
    {
        $compose = new DockerCompose();
        $service = new DockerService();

        $service->name = strtolower(preg_replace('/\s+/', '-', $appModel->name));
//        todo Добавление пути к Dockerfile
        if (!empty($appModel->image))
            $service->image = $appModel->image;
        else
            if (!empty($appModel->file))
                $service->build = str_replace('Dockerfile', '', $appModel->file);
            else
                return false;

        $compose->setService($service->getService());
        $compose->save();

        $appModel->url = DockerService::prepareServiceName($appModel->name) . '.' . $this::domain;
        return $appModel->save();
    }

    /**
     * @param $appModel Apps
     */
    private function createStatic($appModel)
    {
        $conf = new NginxConf();
        $conf->serviceName = DockerService::prepareServiceName($appModel->name);
        $conf->createStatic(Yii::$app->user->id);
        $appModel->url = DockerService::prepareServiceName($appModel->name) . '.' . $this::domain;
        $appModel->status = 2;
        $appModel->save();
    }

    public function actionList()
    {
        $this->view->title = "Apps catalog";
        $appsQuery = Apps::find()->where(['status' => 2]);
        $dataProvider = new ActiveDataProvider([
            'query' => $appsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('list', ['dataProvider' => $dataProvider]);
    }
}
