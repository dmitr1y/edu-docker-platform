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
$this->params['breadcrumbs'][] = 'Приложения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'port')->label('Порт приложения') ?>

    <div class="form-group">
        <label for="apps-type">Способ создания сервиса:</label>
        <select title="Выберите способ создания приложения" id="apps-type" class="form-control">
            <option value="image">Docker образ</option>
            <option value="dockerfile">Dockerfile</option>
        </select>
    </div>

    <div class="form-group" id="image">
        <?= $form->field($model, 'image')->label('Docker образ') ?>
    </div>

    <div class="form-group" id="dockerfile">
        <?= $form->field($model, 'dockerfile')->fileInput() ?>
    </div>
    <div class="form-group" id="image">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
    </div>

    <script language="JavaScript" type="text/javascript">
        let appType = document.getElementById('apps-type');
        let dockerfile = document.getElementById('dockerfile');
        let image = document.getElementById('image');
        dockerfile.style.display = 'none';

        appType.onchange = function () {
            switch (appType.options[appType.selectedIndex].value) {
                case "image":
                    dockerfile.style.display = 'none';
                    image.style.display = 'block';
                    break;
                case "dockerfile":
                    dockerfile.style.display = 'block';
                    image.style.display = 'none';
                    break;
                default:
                    dockerfile.style.display = 'none';
                    image.style.display = 'none';
                    break;
            }
        };
    </script>

    <?php ActiveForm::end(); ?>

    <code><?= __FILE__ ?></code>
</div>
