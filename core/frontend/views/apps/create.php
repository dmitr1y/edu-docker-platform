<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:06
 */

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['/apps/list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group-vertical">
        <a class="btn btn-primary" href="/apps/create-dynamic">Create dynamic app</a>
        <a class="btn btn-primary" href="/apps/create-static">Create static app</a>
    </div>
</div>
