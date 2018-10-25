<?php

/* @var $this yii\web\View */

$this->title = 'Docker containers';
?>
<div class="docker">
    <table class="table table-hover table-striped">
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
                $state = '';
                switch ($ps[$key]['state']) {
                    case 'Up':
                        $state = '<i class="fas fa-check-square"></i>';
                        break;
                    default:
                        $state = '<i class="fas fa-stop"></i> ' . $ps[$key]['state'];
                        break;
                }

                $line = '<tr>
<td>' . $ps[$key]['name'] . '</td>
<td>' . $ps[$key]['command'] . '</td>
<td>' . $state . '</td>
<td>' . $ps[$key]['ports'] . '</td>
</tr>';
                echo $line;
            }
        ?>
    </table>
</div>
