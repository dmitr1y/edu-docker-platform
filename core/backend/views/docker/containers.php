<?php

/* @var $this yii\web\View */

$this->title = 'Docker containers';
?>
<div class="docker">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">name</th>
            <th scope="col">command</th>
            <th scope="col">state</th>
            <th scope="col">ports</th>
        </tr>
        </thead>
        <?php
        if (!empty($ps))
            foreach ($ps as $key => $value) {
                $line = '<tr>
<td>' . $ps[$key]['name'] . '</td>
<td>' . $ps[$key]['command'] . '</td>
<td>' . $ps[$key]['state'] . '</td>
<td>' . $ps[$key]['ports'] . '</td>
</tr>';
                echo $line;
            }
        ?>
    </table>
</div>
