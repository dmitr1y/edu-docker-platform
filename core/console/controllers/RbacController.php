<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 27.10.18
 * Time: 17:39
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();


        echo 'добавляем разрешение "userAppManage"' . PHP_EOL;
        $userAppManage = $auth->createPermission('userAppManage');
        $userAppManage->description = 'Управление пользовательскими приложениями' . PHP_EOL;
        $auth->add($userAppManage);

        echo 'добавляем разрешение "viewApps"' . PHP_EOL;
        $viewApps = $auth->createPermission('viewApps');
        $viewApps->description = 'Просмотр каталога приложений' . PHP_EOL;
        $auth->add($viewApps);

        echo 'добавляем разрешение "userDockerManage"' . PHP_EOL;
        $userDockerManage = $auth->createPermission('userDockerManage');
        $userDockerManage->description = 'Управление пользовательскими Docker контейнерами' . PHP_EOL;
        $auth->add($userDockerManage);

        echo 'добавляем роль "user" и даём роли разрешение "createApp", "updateApp" и "viewApps"' . PHP_EOL;
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $userAppManage);
        $auth->addChild($user, $viewApps);
        $auth->addChild($user, $userDockerManage);

        echo 'добавляем разрешение "userManage"' . PHP_EOL;
        $userManage = $auth->createPermission('userManage');
        $userManage->description = 'Управление пользователями' . PHP_EOL;
        $auth->add($userManage);

        echo 'добавляем разрешение "webConsole"' . PHP_EOL;
        $webConsole = $auth->createPermission('webConsole');
        $webConsole->description = 'Web консоль' . PHP_EOL;
        $auth->add($webConsole);

        echo 'добавляем разрешение "sysDockerManage"' . PHP_EOL;
        $sysDockerManage = $auth->createPermission('sysDockerManage');
        $sysDockerManage->description = 'Управление Docker сервисами платформы' . PHP_EOL;
        $auth->add($sysDockerManage);

        echo 'добавляем разрешение "categoryManage"n';
        $categoryManage = $auth->createPermission('categoryManage');
        $categoryManage->description = 'Управление категориями';
        $auth->add($categoryManage);

        echo 'добавляем разрешение "userManage"' . PHP_EOL;
        $dbManage = $auth->createPermission('dbManage');
        $dbManage->description = 'Управление БД' . PHP_EOL;
        $auth->add($dbManage);

        echo 'добавляем роль "admin" и даём роли разрешение "updateApp"' . PHP_EOL;
        echo 'а также все разрешения роли "user"' . PHP_EOL;
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $userManage);
        $auth->addChild($admin, $webConsole);
        $auth->addChild($admin, $sysDockerManage);
        $auth->addChild($admin, $userDockerManage);
        $auth->addChild($admin, $categoryManage);
        $auth->addChild($admin, $dbManage);

//        echo 'Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()'.PHP_EOL;
//        echo 'обычно реализуемый в модели User.'.PHP_EOL;
//       $auth->assign($user, 2);
        echo 'добавляем роль "admin" пользователю с id=1' . PHP_EOL;
        $auth->assign($admin, 1);

        return 0;
    }
}
