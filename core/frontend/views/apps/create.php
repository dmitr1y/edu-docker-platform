<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:06
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['/apps/list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'description') ?>
    <?= $form->field($model, 'type')->dropDownList([
        '0' => 'Static app',
        '1' => 'Dynamic app',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
