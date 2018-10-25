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
use common\models\app\DockerApps;
use common\models\app\StaticApps;
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
use PHPUnit\Framework\MockObject\BadMethodCallException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class AppsController extends Controller
{
    private const domain = "app";

    public function actionIndex()
    {
        $this->view->title = " Discover apps";

        return $this->render('index');
    }

    public function actionManage($id = null, $logFlag = null)
    {
        if (empty($id))
            return $this->redirect(['apps/create']);

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
            throw  new \yii\web\NotFoundHttpException();

        $app = Apps::findOne(['id' => $id]);
        $dockerApp = DockerApps::findOne(['app_id' => $app->id]);
        $staticApp = StaticApps::findOne(['app_id' => $app->id]);
        $appName = DockerService::prepareServiceName($app->name);

        $log = null;

//        todo Проверка запущен ли nginx и БД

        switch ($action) {
            case 'Run':
                if (empty($dockerApp))
                    throw new NotFoundHttpException();

                Yii::$app->queue->push(new RunDockerService([
                    'serviceName' => $appName,
                    'appModel' => $dockerApp
                ]));
                Yii::$app->queue->push(new CreateNginxConf([
                    'serviceName' => $appName,
                    'servicePort' => $dockerApp->port,
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
                if (!empty($dockerApp))
                    Yii::$app->queue->push(new RemoveDockerService([
                        'serviceName' => $appName,
                        'appModel' => $dockerApp
                    ]));
                else {
                    if (empty($staticApp))
                        throw new NotFoundHttpException();
                    $staticApp->remove();
                }
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
        return $this->redirect(['apps/manage', 'logFlag' => true, 'id' => $id]);
    }

    public function actionCreate()
    {
        $this->view->title = "Create your app";
        $model = new Apps();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->owner_id = Yii::$app->user->id;
            $model->save();

            $url = null;

            switch ($model->type) {
                case 0:
                    $url = 'apps/create-static';
                    break;
                case 1:
                    $url = 'apps/create-dynamic';
                    break;
                default:
                    return $this->redirect(['apps/create']);
                    break;
            }
            return $this->redirect([$url, 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionCreateDynamic($id = null)
    {
        if (empty($id))
            throw new NotFoundHttpException();


        Yii::$app->view->title = 'Create dynamic app';
        $model = new DockerApps();
        $app = Apps::findOne(['id' => $id]);
        if (!empty($app->url) || DockerApps::findOne(['app_id' => $id]))
            throw new ForbiddenHttpException();
        $modelUpload = new DockerfileUploadForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $modelUpload->dockerfile = UploadedFile::getInstance($model, 'dockerfile');
            $model->app_id = $id;
            $model->service_name = $app->name;
            $model->save();
            $dockerfilePath = $modelUpload->upload(Yii::$app->user->id, $model->id);
            if (!empty($dockerfilePath)) {
                $model->dockerfile = $dockerfilePath;
                $model->save();
            }
            if (!$url = $this->createCompose($model))
                throw new BadMethodCallException();

            $app->url = $url;
            $app->save();
            return $this->redirect(['apps/manage', 'id' => $app->id]);
        }
        return $this->render('createDynamic', ['model' => $model, 'modelUpload' => $modelUpload]);
    }

    public function actionCreateStatic($id = null)
    {
        Yii::$app->view->title = 'Create static app';
        $model = new StaticApps();
        $modelUpload = new StaticAppUploadForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if (empty($id))
                throw new ForbiddenHttpException();

            $app = Apps::findOne(['id' => $id]);

            if (empty($app))
                throw new NotFoundHttpException();

            $modelUpload->app = UploadedFile::getInstance($model, 'path_to_index');
            $model->app_id = $app->id;

            $appPath = $modelUpload->upload(Yii::$app->user->id, DockerService::prepareServiceName($app->name));
            if (!empty($appPath)) {
                $model->path_to_index = $appPath;
            }

            $model->save();
            $this->createStatic($app);
            return $this->redirect(['apps/manage', 'id' => $app->id]);
        }
        return $this->render('createStatic', ['model' => $model]);
    }

    /**
     * @param $appModel DockerApps
     * @return bool
     */
    private function createCompose($appModel)
    {
        $compose = new DockerCompose();
        $service = new DockerService();

        $service->name = strtolower(preg_replace('/\s+/', '-', $appModel->service_name));
//        todo Добавление пути к Dockerfile
        if (!empty($appModel->image))
            $service->image = $appModel->image;
        else
            if (!empty($appModel->file))
                $service->build = str_replace('Dockerfile', '', $appModel->file);
            else
                return false;

        $compose->setService($service->getService());
        if ($compose->save())
            return '/' . $this::domain . '/' . DockerService::prepareServiceName($appModel->service_name);
        return null;
    }

    /**
     * @param $appModel Apps
     */
    private function createStatic($appModel)
    {
        $conf = new NginxConf();
        $conf->serviceName = DockerService::prepareServiceName($appModel->name);
        $conf->createStatic(Yii::$app->user->id);
        $appModel->url = '/' . $this::domain . '/' . DockerService::prepareServiceName($appModel->name);
//        $appModel->status = 2;
        $appModel->save();
    }

    public function actionList()
    {
        $this->view->title = "Apps catalog";
        $appsQuery = Apps::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $appsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('list', ['dataProvider' => $dataProvider]);
    }

    public function actionDetail($id = null)
    {
        if (empty($id))
            throw  new \yii\web\NotFoundHttpException();

        $model = Apps::findOne(['id' => $id]);

        return $this->render('_listViewAppDetail', ['model' => $model]);
    }

    public function actionMyApps()
    {
        $this->view->title = "My apps";
        $appsQuery = Apps::find()->where(['owner_id' => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $appsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('myList', ['dataProvider' => $dataProvider]);
    }
}
