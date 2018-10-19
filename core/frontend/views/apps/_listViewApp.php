<?php

/**
 * @var \common\models\app\Apps $model
 */
?>
<a href="<?= $model->url ?>" class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1"><?= ucfirst($model->name) ?></h5>
        <small><?= $model->timestamp ?></small>
    </div>
    <p class="mb-1">
        <?php
        $strLimit = 150;
        $description = strip_tags($model->description);
        if (strlen($description) > $strLimit)
            echo trim(substr($description, 0, $strLimit)) . '...';
        else
            echo $description;
        ?>
    </p>
    <small>Author</small>
</a>