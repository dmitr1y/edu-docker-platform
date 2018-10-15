<?php

/**
 * @var \common\models\app\Apps $model
 */
?>
<div class="post">
    <?php
    if (isset($model))
        foreach ($model as $key => $value) {
            if (isset($model->{$key})) {
                echo "<tr>";
//                    echo "<td>" . $key . "</td><td>" . $value . "</td>";

                if ($key === 'url')
                    echo "<td>" . $key . "</td><td><a href='https://" . $value . "'>" . $value . "</a></td>";
                else {
                    if ($key === 'status') {
                        echo "<td>" . $key . "</td><td>";
                        switch ($value) {
                            case 0:
                                echo 'off';
                                break;
                            case 1:
                                echo 'running';
                                break;
                            case 2:
                                echo 'on';
                                break;
                            default:
                                echo 'error';
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
        echo "NOT FOUNDED<br>";
    ?>
    ?>
</div>
