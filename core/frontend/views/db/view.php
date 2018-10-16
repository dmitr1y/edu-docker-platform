<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 16.10.18
 * Time: 20:11
 */

use yii\helpers\Html;

/** @var \common\models\mysql\AppsDbUsers $model */

$this->params['breadcrumbs'][] = 'Apps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table">
        <tr>
            <td>Username</td>
            <td><?= $model->username ?></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><?= $model->user_password ?></td>
        </tr>
        <tr>
            <td>Database</td>
            <td><?= $model->database ?></td>
        </tr>
    </table>
    <form class="form-group" action="/db/remove" method="post">
        <input class="btn btn-info" type="submit" name="action" value="Edit"/>
        <input class="btn btn-danger" type="submit" name="action" value="Remove"/>
        <input type="hidden" name="id" value="<?= $model->id ?>"/>
        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->csrfToken ?>"/>
    </form>
    <code><?= __FILE__ ?></code>
</div>
