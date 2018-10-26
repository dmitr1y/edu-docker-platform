<?php

/* @var $this yii\web\View */

$this->title = 'Docker containers';
?>
<div class="docker">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">name</th>
            <th scope="col">command</th>
            <th scope="col">state</th>
            <th scope="col">ports</th>
            <th scope="col">manage</th>
        </tr>
        </thead>
        <?php
        if (!empty($ps))
            foreach ($ps as $key => $value) {
                $state = '';
                $name = preg_replace("/storage_/", '', $ps[$key]['name']);
                $name = substr($name, 0, strlen($name) - 2);
                $tr_class = '';
                $manager = '<form method="post" class="form-inline">
<span class="input-group-btn">
    <button type="submit" name="manager" value="start" class="btn btn-primary"><i class="fas fa-play"></i></button>
    <button type="submit" name="manager" value="stop" class="btn btn-danger"><i class="fas fa-stop"></i></button>
    <button type="submit" name="manager" value="log" class="btn btn-primary" disabled><i class="fas fa-history"></i></button>
    </span>
    <input type="hidden" name="service" value="' . $name . '">
    <input id="form-token" type="hidden" name="' . Yii::$app->request->csrfParam . '" value="' . Yii::$app->request->csrfToken . '"/>
</form>';
                switch ($ps[$key]['state']) {
                    case 'Up':
                        $state = '<i class="fas fa-check-square"></i>';
                        break;
                    default:
                        $tr_class = 'class="danger"';
                        $state = '<i class="fas fa-stop"></i> ' . $ps[$key]['state'];
                        break;
                }

                $line = '<tr ' . $tr_class . '>
<td><a href="https://localhost/app/' . $name . '" target="_blank">' . $name . '</a> </td>
<td>' . $ps[$key]['command'] . '</td>
<td>' . $state . '</td>
<td>' . $ps[$key]['ports'] . '</td>
<td>' . $manager . '</td>
</tr>';
                echo $line;
            }
        ?>
    </table>
</div>
