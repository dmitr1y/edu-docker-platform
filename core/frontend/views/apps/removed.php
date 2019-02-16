<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 12.10.18
 * Time: 21:49
 */

use yii\helpers\Html;

//$this->title = 'App';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-success autocollapse">
        <div class="panel-heading clickable">
            <h3 class="panel-title">
                Выполнено!
            </h3>
        </div>
        <div class="panel-body">
            Приложение успешно удалено.
        </div>
    </div>

    <a href="/apps/create" class="btn btn-info">Создать новое</a>
    <a href="/apps/list" class="btn btn-info">Каталог приложений</a>

</div>
