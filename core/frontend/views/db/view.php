<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 16.10.18
 * Time: 20:11
 */

use yii\bootstrap\Alert;
use yii\helpers\Html;

/** @var \common\models\mysql\AppsDbUsers $model */
/** @var \common\models\app\Apps $app */

$this->params['breadcrumbs'][] = 'Apps';
$this->params['breadcrumbs'][] = $app->name;
$this->params['breadcrumbs'][] = $this->title;

echo Alert::widget([
    'options' => [
        'class' => 'alert-warning',
    ],
    'body' => '<strong>Warning!</strong> Please write down the access data. They can no longer be seen.'
]); ?>

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

    <div class="form-group">
        <a href="/apps/manage?<?= $app->id ?>" class="btn btn-info">Got to <?= $app->name ?></a>
    </div>
</div>
