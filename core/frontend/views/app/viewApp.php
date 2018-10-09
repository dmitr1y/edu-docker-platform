<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 09.10.18
 * Time: 22:06
 */
/* @var $this yii\web\View */

/* @var $model \common\models\app\Apps */

use yii\helpers\Html;

$this->params['breadcrumbs'][] = 'Apps';
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
        foreach ($model as $key => $value) {
            if (isset($model->{$key}) && !empty($model->{$key})) {
                echo "<tr>";
                if ($key === 'url')
                    echo "<td>" . $key . "</td><td><a href='https://" . $value . "'>" . $value . "</a></td>";
                else
                    echo "<td>" . $key . "</td><td>" . $value . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

    <?php
    if (isset($model->url) && !empty($model->url)) {
        ?>
        <form action="/app/start/<?= preg_replace('/\s+/', '', $model->name) ?>">
            <input type="submit" value="Start app"/>
        </form>
        <?php
        if (isset($log)) {
            echo "<pre>";
            echo print_r($log);
            echo "</pre>";
        }
    }
    ?>

    <code><?= __FILE__ ?></code>
</div>
