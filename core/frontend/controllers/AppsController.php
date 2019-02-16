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
use dektrium\user\filters\AccessRule;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class AppsController extends Controller
{
//    private $data_path = "/storage/user_app_data";

    const ACTION_REMOVE = 'Удалить';
    const ACTION_START = 'Запустить';
    const ACTION_STOP = 'Остановить';

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
                            'index', 'manage', 'manager', 'create',
                            'create-static', 'create-dynamic', 'list',
                            'detail', 'my-apps'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
//                'denyCallback' => function () {
//                    Yii::$app->user->setReturnUrl(Yii::$app->request->url);
//                    return Yii::$app->response->redirect(['site/login']);
//                },
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->title = "Обзор приложения";

        return $this->render('index');
    }

    /**
     * @param null $id
     * @param null $logFlag
     * @param null $db_info
     * @return string|\yii\web\Response
     */
    public function actionManage($id = null, $logFlag = null, $db_info = null)
    {
        if (empty($id)) {
            return $this->redirect(['apps/create']);
        }

        $model = Apps::find()->where(['id' => $id])->one();
        $log = null;

        if (!empty($logFlag)) {
            $log = AppsLog::findOne(['appId' => $model->id]);
        }

        Yii::$app->view->title = $model->name;
        return $this->render('viewApp', ['model' => $model, 'log' => $log, 'db_info' => $db_info]);
    }

    /**
     * Управление приложением
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionManager()
    {
        $appName = Yii::$app->request->post('app');
        $action = Yii::$app->request->post('action');
        $id = Yii::$app->request->post('id');

        if (!isset($appName) || empty($appName)) {
            throw  new NotFoundHttpException("Приложение не найдено");
        }

        $app = Apps::findOne(['id' => $id, 'deleted' => 0]);

        if (!$app) {
            throw new NotFoundHttpException("Приложение не найдено");
        }

        $dockerApp = DockerApps::findOne(['app_id' => $app->id]);
        $staticApp = StaticApps::findOne(['app_id' => $app->id]);

//        $appName = DockerService::prepareServiceName($app->name);
        $appName = "app" . $app->id;

//        todo Проверка запущен ли nginx и БД

        switch ($action) {
            case AppsController::ACTION_START:
                if (empty($dockerApp) && empty($staticApp)) {
                    throw new NotFoundHttpException();
                }

                Yii::$app->queue->push(new RunDockerService([
                    'serviceName' => $appName,
                    'appModel' => $dockerApp
                ]));
                Yii::$app->queue->push(new CreateNginxConf([
                    'serviceName' => $appName,
                    'servicePort' => $dockerApp->port,
                ]));
                break;
            case AppsController::ACTION_STOP:
                Yii::$app->queue->push(new RemoveNginxConf([
                    'serviceName' => $appName
                ]));
                Yii::$app->queue->push(new StopDockerService([
                    'serviceName' => $appName,
                    'appModel' => $dockerApp
                ]));
                break;
            case AppsController::ACTION_REMOVE:
//                todo Удаление образа (автоочистка каждый день), Dockerfile, БД

                if (!empty($dockerApp)) {
                    Yii::$app->queue->push(new RemoveDockerService([
                        'serviceName' => $appName,
                        'appModel' => $dockerApp
                    ]));
                } else {
                    if (empty($staticApp)) {
                        throw new NotFoundHttpException();
                    }
                    $staticApp->remove();
                }
                Yii::$app->queue->push(new RemoveNginxConf([
                    'serviceName' => $appName
                ]));

                $app->deleted = 1;
                $app->save();
                return $this->render('removed');
            default:
                throw  new BadRequestHttpException("Неизвестное действие над приложением");
        }

//       todo Вырезать из лога "storage_"
//        todo Добавить перенос строк
        $appLog = AppsLog::findOne(['appId' => $id]);
        $log_flag = empty($appLog);

        return $this->redirect(['apps/manage', 'logFlag' => $log_flag, 'id' => $id]);
    }

    /**
     * Отображение страницы создания приложения
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->view->title = "Создание своего приложения";
        $model = new Apps();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->owner_id = Yii::$app->user->id;
            $model->save();

            $url = null;
            $dbRequire = Yii::$app->request->post('database');

            switch ($model->type) {
                case Apps::STATIC_TYPE:
                    $url = 'apps/create-static';
                    break;
                case Apps::DYNAMIC_TYPE:
                    $url = 'apps/create-dynamic';
                    break;
                default:
                    return $this->redirect(['apps/create']);
            }

            return $this->redirect([$url, 'id' => $model->id, 'dbRequire' => $dbRequire]);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Отображение формы создания динамическог оприложения
     *
     * @param null $id
     * @param null $dbRequire
     * @return string|\yii\web\Response
     * @throws InternalErrorException
     * @throws NotFoundHttpException
     */
    public function actionCreateDynamic($id = null, $dbRequire = null)
    {
        if (empty($id)) {
            throw new NotFoundHttpException();
        }

        Yii::$app->view->title = 'Создание динамического приложения';
        $model = new DockerApps();
        $app = Apps::findOne(['id' => $id]);
//        if (!empty($app->url) || DockerApps::findOne(['app_id' => $id]))
//            throw new ForbiddenHttpException();
        $modelUpload = new DockerfileUploadForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $modelUpload->dockerfile = UploadedFile::getInstance($model, 'dockerfile');
            $model->app_id = $id;
            $model->service_name = "app" . $app->id;
            $model->save();
            $dockerfilePath = $modelUpload->upload(Yii::$app->user->id, $model->service_name);

//            todo проверка содержимого файла через exec("file ")

            if (!empty($dockerfilePath)) {
                $model->dockerfile = $dockerfilePath;
                $model->save();
            }

            if (!($url = $this->createCompose($model))) {
                throw new InternalErrorException();
            }

            $app->url = $url;
            $app->save();

            if ($dbRequire) {
                return $this->redirect(['db/create', 'id' => $app->id]);
            }

            return $this->redirect(['apps/manage', 'id' => $app->id]);
        }
        return $this->render('createDynamic', ['model' => $model, 'modelUpload' => $modelUpload]);
    }

    /**
     * Отображение формы создания статического приложения
     *
     * @param null $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function actionCreateStatic($id = null)
    {
        Yii::$app->view->title = 'Создание статического приложения';
        $model = new StaticApps();
        $modelUpload = new StaticAppUploadForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if (empty($id)) {
                throw new ForbiddenHttpException();
            }

            $app = Apps::findOne(['id' => $id, 'deleted' => 0]);

            if (empty($app)) {
                throw new NotFoundHttpException();
            }

            $modelUpload->app = UploadedFile::getInstance($model, 'path_to_index');
            $model->app_id = $app->id;

            $appPath = $modelUpload->upload(Yii::$app->user->id, $app->id);
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
     * Отображение списка приложений
     *
     * @return string
     */
    public function actionList()
    {
        $this->view->title = "Каталог приложений";
        $appsQuery = Apps::find()->where(["deleted" => 0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $appsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('list', ['dataProvider' => $dataProvider]);
    }

    /**
     * Отображение страницы с ифнормацией о приложении
     *
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDetail($id = null)
    {
        if (empty($id)) {
            throw  new NotFoundHttpException("Приложение не найдено.");
        }

        $model = Apps::findOne(['id' => $id, 'deleted' => 0]);

        if (!$model) {
            throw new NotFoundHttpException("Приложение не найдено.");
        }

        return $this->render('_listViewAppDetail', ['model' => $model]);
    }

    /**
     * Отображение приложений пользователя
     *
     * @return string
     */
    public function actionMyApps()
    {
        $this->view->title = "Мои приложения";
        $appsQuery = Apps::find()->where(['owner_id' => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $appsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('myList', ['dataProvider' => $dataProvider]);
    }

    /**
     * Создание сервиса в docker-compose
     *
     * @param $appModel DockerApps
     * @return bool
     */
    private function createCompose($appModel)
    {
        $compose = new DockerCompose();
        $service = new DockerService();

        $service->name = strtolower(preg_replace('/\s+/', '-', $appModel->service_name));

//        todo Добавление пути к Dockerfile
        if (!empty($appModel->image)) {
            $service->image = $appModel->image;
//            TODO saving container data to volumes
//            $service->volumes=["'".$this->data_path."/".Yii::$app->user->id."/". DockerService::prepareServiceName($appModel->service_name).":/path/to_data/in_container"];
        } else {
            if (empty($appModel->dockerfile)) {
                return false;
            }

            $service->build = str_replace('Dockerfile', '', $appModel->dockerfile);
        }

        $compose->setService($service->getService());
        if ($compose->save()) {
            return '/app/' . DockerService::prepareServiceName($appModel->service_name) . '/';
        }

        return false;
    }

    /**
     * Создание статического приложения
     *
     * @param $appModel Apps
     * @param string $index_name
     */
    private function createStatic($appModel, $index_name = "index.html")
    {
        $conf = new NginxConf();
        $conf->serviceName = "app" . $appModel->id;
        $conf->createStatic(Yii::$app->user->id, $index_name);
        $appModel->url = '/' . Yii::$app->params['app_host'] . '/app' . $appModel->id . '/';
        $appModel->save();
    }
}
