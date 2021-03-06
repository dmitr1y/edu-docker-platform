<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:06
 */
/* @var $this yii\web\View */

/* @var $model \common\models\app\Apps */

/* @var $log \common\models\app\AppsLog */

use yii\helpers\Html;

$this->params['breadcrumbs'][] = 'Приложения';
$this->params['breadcrumbs'][] = $this->title;;
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table table-hover table-bordered">
        <tr class="success">
            <td>Key</td>
            <td>Value</td>
        </tr>
        <?php
        if (isset($model))
            foreach ($model as $key => $value) {
                if (isset($model->{$key}) || $key === 'status') {
                    echo "<tr>";
                    if ($key === 'url')
                        echo "<td>" . $key . "</td><td><a href='" . $value . "' target='_blank'>" . $value . "</a></td>";
                    else {
                        if ($key === 'status') {
                            echo "<td>" . $key . "</td><td>";
                            switch ($value) {
                                case 0:
                                    echo 'Выключено';
                                    break;
                                case 1:
                                    echo 'Запускается';
                                    break;
                                case 2:
                                    echo 'Запущено';
                                    break;
                                default:
                                    echo 'Ошибка';
                                    break;
                            }
                            echo "</td>";
                        } else {
                            echo "<td>" . $key . "</td><td>" . $value . "</td>";
                        }
                    }
                    echo "</tr>";

                }
            }
        else
            throw new \yii\web\NotFoundHttpException("Приложение не найдено");
        ?>
    </table>

    <?php
    if (isset($model->url) && !empty($model->url)) {
    ?>
    <form class="form-group" action="/apps/manager" method="post">
        <input class="btn btn-info" type="submit" name="action" value="Запутсить"/>
        <input class="btn btn-info" type="submit" name="action" value="Остановить"/>
        <input class="btn btn-danger" type="submit" name="action" value="Удалить"/>
        <input type="hidden" name="app" value="<?= strtolower(preg_replace('/\s+/', '-', $model->name)) ?>">
        <input type="hidden" name="id" value="<?= $model->id ?>">
        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->csrfToken ?>"/>
    </form>

    <?php
    if (!empty($log)) {
    ?>
    <div class="row">
        <?php if (isset($log->log) && !empty($log->log)) { ?>
            <div class="panel panel-info autocollapse">
                <div class="panel-heading clickable">
                    <h3 class="panel-title">
                        Log
                    </h3>
                </div>
                <div class="panel-body">
                    <?= $log->log ?>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        }
        }
        ?>
    </div>
