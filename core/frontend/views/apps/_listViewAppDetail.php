<?php

/**
 * @var \common\models\app\Apps $model
 */

$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['/apps/list']];
$this->params['breadcrumbs'][] = $this->title = ucfirst($model->name);
?>

<div class="post">
    <h3><?= ucfirst(strip_tags($model->name)) ?></h3>
    <small><?= 'Author' . ', ' . $model->timestamp ?></small>
    <p>
        <?= $model->description ?>
    </p>
    <a class="btn btn-primary" href="<?= $model->url ?>" target="_blank">Go to app</a>
</div>
