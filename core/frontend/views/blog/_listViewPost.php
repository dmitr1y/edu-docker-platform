<?php

/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 10.02.19
 * Time: 20:39
 */
?>
<a href="<?= '/blog/view?id=' . $model->id ?>"
   class="list-group-item list-group-item-action flex-column align-items-start">
    <h4 class="list-group-item-heading"><?= $appIcon ?> <b><?= ucfirst($model->title) ?></b></h4>
    <p class="list-group-item-text">
        <?php
        $strLimit = 150;
        $description = strip_tags($model->annotation);
        if (strlen($description) > $strLimit) {
            echo trim(substr($description, 0, $strLimit)) . '...';
        } else {
            echo $description;
        }
        ?>
    </p>
    <!--    <div>-->
    <!--        <small>Категория: --><? //= $model->category ?><!--</small>-->
    <!--    </div>-->
    <div>
        <small><?= \dektrium\user\models\User::findOne(['id' => $model->creator])->username ?></small>
        <small><?= $model->created ?></small>
    </div>
</a>
