<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 16.10.18
 * Time: 21:04
 */

namespace frontend\controllers;


use common\models\app\Apps;
use common\models\mysql\AppsDbUsers;
use dektrium\user\filters\AccessRule;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

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
                        'actions' => ['create', 'remove'],
                        'allow' => true,
                        'roles' => ['@', 'user'],
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

    /**
     * Create database for app
     * @param integer $id - app id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionCreate($id = null)
    {
        Yii::$app->view->title = 'Database';
        $model = new AppsDbUsers();

        $app = Apps::findOne(['id' => $id]);

        if (empty($app) || !empty(AppsDbUsers::findOne(['app_id' => $id])) || $app->owner_id !== Yii::$app->user->id)
            throw new ForbiddenHttpException();

        $model->owner_id = Yii::$app->user->id;
        $model->app_id = $id;
        $model->database = $app->name . "db";
        $model->username = $app->name;
        $model->user_password = $this->generateStrongPassword();
        $model->save();

        return $this->render('view', ['model' => $model, 'app' => $app]);
    }

    /**
     * Author: https://gist.github.com/tylerhall/521810
     * Generates a strong password of N length containing at least one lower case letter,
     * one uppercase letter, one digit, and one special character. The remaining characters
     * in the password are chosen at random from those four sets.
     *
     * The available characters in each set are user friendly - there are no ambiguous
     * characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
     * makes it much easier for users to manually type or speak their passwords.
     *
     * Note: the $add_dashes option will increase the length of the password by
     * floor(sqrt(N)) characters.
     * @param int $length
     * @param bool $add_dashes
     * @param string $available_sets
     * @return bool|string
     */
    private function generateStrongPassword($length = 16, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if (strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if (strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if (strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
//        if (strpos($available_sets, 's') !== false)
//            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if (!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    public function actionView($id = null)
    {
        if (empty($id))
            throw  new \yii\web\NotFoundHttpException();

        $model = AppsDbUsers::findOne(['id' => $id]);

        if (empty($model))
            throw  new \yii\web\NotFoundHttpException();

        if ($model->owner_id !== Yii::$app->user->id)
            throw new ForbiddenHttpException();

//        todo security: enter user password for showing db pass

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
