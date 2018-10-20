<?php

/**
 * @var \common\models\app\Apps $model
 */
?>

<div class="post">
    <h3><?= ucfirst(strip_tags($model->name)) ?></h3>
    <small><?= 'Author' . ', ' . $model->timestamp ?></small>
    <p>
        <?= $model->description ?>
    </p>
    <a class="btn btn-primary" href="<?= $model->url ?>">Go to app</a>
</div>
