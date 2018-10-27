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


        echo 'добавляем разрешение "userAppManage"\n';
        $userAppManage = $auth->createPermission('userAppManage');
        $userAppManage->description = 'Manage user app\n';
        $auth->add($userAppManage);

        echo 'добавляем разрешение "viewApps"\n';
        $viewApps = $auth->createPermission('viewApps');
        $viewApps->description = 'View apps catalog\n';
        $auth->add($viewApps);

        echo 'добавляем разрешение "userDockerManage"\n';
        $userDockerManage = $auth->createPermission('userDockerManage');
        $userDockerManage->description = 'Manage user applications in the Docker\n';
        $auth->add($userDockerManage);

        echo 'добавляем роль "user" и даём роли разрешение "createApp", "updateApp" и "viewApps"\n';
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $userAppManage);
        $auth->addChild($user, $viewApps);
        $auth->addChild($user, $userDockerManage);

        echo 'добавляем разрешение "userManage"\n';
        $userManage = $auth->createPermission('userManage');
        $userManage->description = 'User management\n';
        $auth->add($userManage);

        echo 'добавляем разрешение "webConsole"\n';
        $webConsole = $auth->createPermission('webConsole');
        $webConsole->description = 'Web console\n';
        $auth->add($webConsole);

        echo 'добавляем разрешение "sysDockerManage"\n';
        $sysDockerManage = $auth->createPermission('sysDockerManage');
        $sysDockerManage->description = 'Docker application core management\n';
        $auth->add($sysDockerManage);

        echo 'добавляем разрешение "categoryManage"\n';
        $categoryManage = $auth->createPermission('categoryManage');
        $categoryManage->description = 'Category management\n';
        $auth->add($categoryManage);

        echo 'добавляем разрешение "userManage"\n';
        $dbManage = $auth->createPermission('dbManage');
        $dbManage->description = 'Database management\n';
        $auth->add($dbManage);

        echo 'добавляем роль "admin" и даём роли разрешение "updateApp"\n';
        echo 'а также все разрешения роли "user"\n';
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $userManage);
        $auth->addChild($admin, $webConsole);
        $auth->addChild($admin, $sysDockerManage);
        $auth->addChild($admin, $userDockerManage);
        $auth->addChild($admin, $categoryManage);
        $auth->addChild($admin, $dbManage);

//        echo 'Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()\n';
//        echo 'обычно реализуемый в модели User.\n';
//       $auth->assign($user, 2);
        $auth->assign($admin, 1);

        return 0;
    }
}
