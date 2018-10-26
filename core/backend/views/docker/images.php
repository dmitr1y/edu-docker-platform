<?php

/* @var $this yii\web\View */

$this->title = 'Docker images';
?>
<div class="docker">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">container</th>
            <th scope="col">repository</th>
            <th scope="col">tag</th>
            <th scope="col">image id</th>
            <th scope="col">size</th>
        </tr>
        </thead>
        <?php
        if (!empty($ps))
            foreach ($ps as $key => $value) {
                $state = '';
                $name = preg_replace("/storage_/", '', $ps[$key]['container']);
                $name = substr($name, 0, strlen($name) - 2);
                $tr_class = '';
                $manager = '<form method="post" class="form-inline">
<span class="input-group-btn">
    <button type="submit" name="manager" value="start" class="btn btn-primary"><i class="fas fa-play"></i></button>
    <button type="submit" name="manager" value="stop" class="btn btn-danger"><i class="fas fa-stop"></i></button>
    <button type="submit" name="manager" value="log" class="btn btn-primary"><i class="fas fa-history"></i></button>
    </span>
    <input type="hidden" name="service" value="' . $name . '">
    <input id="form-token" type="hidden" name="' . Yii::$app->request->csrfParam . '" value="' . Yii::$app->request->csrfToken . '"/>
</form>';

                $line = '<tr >
<td>' . $name . '</td>
<td>' . $ps[$key]['repository'] . '</td>
<td>' . $ps[$key]['tag'] . '</td>
<td>' . $ps[$key]['image_id'] . '</td>
<td>' . $ps[$key]['size'] . '</td>
</tr>';
                echo $line;
            }
        ?>
    </table>
</div>
