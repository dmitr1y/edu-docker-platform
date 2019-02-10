<?php

/**
 * @var \common\models\app\Apps $model
 */

use common\models\app\Apps;


?>
<a href="<?= '/apps/manage?id=' . $model->id ?>"
   class="list-group-item list-group-item-action flex-column align-items-start">

    <h4 class="list-group-item-heading">
        <?php
        switch ($model->type) {
            case Apps::STATIC_TYPE:
                echo '<i class="fab fa-html5"></i>';
                break;
            case Apps::DYNAMIC_TYPE:
                echo '<i class="fab fa-docker"></i>';
                break;
            default:
                echo '<i class="fab fa-question"></i>';
                break;
        }
        ?>
        <b><?= ucfirst($model->name) ?></b>
    </h4>

    <p class="list-group-item-text">
        <?php
        $strLimit = 150;
        $description = strip_tags($model->description);
        if (strlen($description) > $strLimit) {
            echo trim(substr($description, 0, $strLimit)) . '...';
        } else {
            echo $description;
        }
        ?>
    </p>
    <div>
        <small><?= \dektrium\user\models\User::findOne(['id' => $model->owner_id])->username ?></small>
        <small><?= $model->timestamp ?></small>
    </div>
</a>
