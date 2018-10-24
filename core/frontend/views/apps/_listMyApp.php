<?php

/**
 * @var \common\models\app\Apps $model
 */


?>
<a href="<?= '/apps/manage?id=' . $model->id ?>"
   class="list-group-item list-group-item-action flex-column align-items-start">

    <h4 class="list-group-item-heading">
        <?php
        switch ($model->status) {
            case 0:
                echo '<i class="fa fa-stop-circle"></i>';
                break;
            case 1:
                echo '<i class="fa fa-spinner"></i>';
                break;
            case 2:
                echo '<i class="fa fa-check-circle"></i>';
                break;
            default:
                echo '<i class="fa fa-exclamation-circle"></i>';
                break;
        }
        ?>
        <b><?= ucfirst($model->name) ?></b>
    </h4>

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
    <div>
        <small><?= \dektrium\user\models\User::findOne(['id' => $model->owner_id])->username ?></small>
        <small><?= $model->timestamp ?></small>
    </div>
</a>