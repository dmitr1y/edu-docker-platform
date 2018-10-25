<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\app\Apps */

$this->title = Yii::t('app', 'Create Apps');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Apps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apps-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
