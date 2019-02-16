<?php

/**
 * @var \common\models\app\Apps $model
 */

$appIcon = '<i class="';

switch ($model->type) {
    case 0:
        $appIcon .= 'fab fa-html5';
        break;
    case 1:
        $appIcon .= 'fab fa-docker';
        break;
    default:
        $appIcon .= 'fas fa-desktop';
        break;
}
$appIcon .= '"></i>';
?>
<a href="<?= '/apps/detail?id=' . $model->id ?>"
   class="list-group-item list-group-item-action flex-column align-items-start">
    <h4 class="list-group-item-heading"><?= $appIcon ?> <b><?= ucfirst($model->name) ?></b></h4>
    <p class="list-group-item-text">
        <?php
        $strLimit = 150;
        $description = strip_tags($model->description);
        if (strlen($description) > $strLimit)
            echo trim(substr($description, 0, $strLimit)) . '...';
        else
            echo $description;
        ?>
    </p>
    <!--    <div>-->
    <!--        <small>Категория: --><? //= $model->category ?><!--</small>-->
    <!--    </div>-->
    <div>
        <small><?= \dektrium\user\models\User::findOne(['id' => $model->owner_id])->username ?></small>
        <small><?= $model->timestamp ?></small>
    </div>
</a>
