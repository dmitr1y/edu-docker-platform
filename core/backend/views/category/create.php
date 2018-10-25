<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\app\AppsCategory */

$this->title = Yii::t('app', 'Create Apps Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Apps Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apps-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
