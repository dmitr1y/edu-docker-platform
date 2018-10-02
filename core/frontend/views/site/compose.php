<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Compose';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    foreach ($model as $key => $value) {
//        $arr=json_encode($value, JSON_PRETTY_PRINT);
        echo "<h3>$key</h3>";
        echo "<pre>";
        print_r($value);
        echo "</pre>";
        echo "<br>";
    }
    ?>

</div>
