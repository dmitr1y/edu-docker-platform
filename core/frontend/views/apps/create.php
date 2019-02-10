<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:06
 */

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['/apps/list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
    <?= $form->field($model, 'type')->dropDownList([
        '0' => 'Static app',
        '1' => 'Dynamic app',
    ]) ?>
    <div class="form-group" id="dbReqiure">
        <?= Html::checkbox("database", false, ['label' => 'Database required']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
    </div>
    <script language="JavaScript" type="text/javascript">
        let appType = document.getElementById('apps-type');
        document.getElementById('dbReqiure').style.display = 'none';
        appType.onchange = function () {
            let dbReqiure = document.getElementById('dbReqiure');
            switch (appType.options[appType.selectedIndex].value) {
                case "1":
                    dbReqiure.style.display = 'block';
                    break;
                default:
                    dbReqiure.style.display = 'none';
                    break;
            }
        };
    </script>
    <?php ActiveForm::end(); ?>
</div>
