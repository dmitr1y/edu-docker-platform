<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:06
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

//$this->title = 'App';
$this->params['breadcrumbs'][] = 'App';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'description') ?>
    <?= $form->field($model, 'port') ?>
    <?= $form->field($model, 'image') ?>
    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <code><?= __FILE__ ?></code>
</div>
