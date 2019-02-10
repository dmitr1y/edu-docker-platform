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

    <p>Выберите приложение из <a href="/apps/list">каталога</a> или <a href="/apps/create">создайте свое</a>. </p>

    <code><?= __FILE__ ?></code>
</div>
