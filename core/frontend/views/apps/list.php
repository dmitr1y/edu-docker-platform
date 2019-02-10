<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:06
 */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="list-group">
        <?php
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_listViewApp',
        ]);
        ?>
    </div>
</div>
