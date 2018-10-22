<?php

/* @var $this yii\web\View */

$this->title = 'Docker manager';
?>
<div class="docker">

    <div class="row-fluid">
        <div class="col-sm-3">
            <span class="fa fa-check-circle"></span> <?= $stats['online'] ?> containers online
        </div>
        <div class="col-sm-3">
            <span class="fa fa-stop-circle"></span> <?= $stats['offline'] ?> containers offline
        </div>
        <div class="col-sm-3">
            <span class="fa fa-exclamation-circle"></span> <?= $stats['error'] ?> containers with errors
        </div>
        <div class="col-sm-3">
            <span class="fa fa-database"></span> <?= $stats['db_count'] ?> apps databases
        </div>
    </div>
</div>
