<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 16.10.18
 * Time: 21:04
 */

namespace frontend\controllers;


use common\models\mysql\AppsDbUsers;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;

class DbController extends Controller
{
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
                        'actions' => ['create', 'view', 'remove'],
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
    public function actionCreate()
    {
        Yii::$app->view->title = 'Create database for your app';
        $model = new AppsDbUsers();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->owner_id = Yii::$app->user->id;
            $model->save();
            return $this->redirect(['app/view', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionView($id = null)
    {
        if (empty($id))
            throw  new \yii\web\NotFoundHttpException();
        $model = AppsDbUsers::findOne(['id' => $id]);
        if (empty($model))
            throw  new \yii\web\NotFoundHttpException();
        return $this->render('view', ['model' => $model]);
    }

    public function actionRemove()
    {
        $id = Yii::$app->request->post('id');
        $action = Yii::$app->request->post('action');
        if (empty($id) || empty($action))
            throw  new \yii\web\NotFoundHttpException();

        $model = AppsDbUsers::findOne(['id' => $id]);

        if (empty($model))
            throw  new \yii\web\NotFoundHttpException();

        switch ($action) {
            case 'Remove':
                try {
                    $model->delete();
                } catch (StaleObjectException $e) {
                } catch (\Throwable $e) {
                }
                return $this->redirect(['db/create']);
                break;
            case 'Edit':
                break;
            default:
                throw  new \yii\web\NotFoundHttpException();
                break;
        }
        return $this->redirect(['db/create']);
    }
}
