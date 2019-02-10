<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="post">
        <div>
            <span><?= $model->creator ?></span>
            <span><?= $model->created ?></span>
        </div>

        <div class="content">
            <?= $model->body ?>
        </div>

        <div>
            <button role="button"> Перейти к модулю</button>
        </div>
    </div>
</div>
