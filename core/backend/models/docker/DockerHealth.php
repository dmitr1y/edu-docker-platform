<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 25.10.18
 * Time: 20:07
 */

namespace backend\models\docker;


use common\models\docker\DockerComposeManager;
use yii\base\Model;

class DockerHealth extends Model
{
//    private function docker
    public static function getStatus()
    {
        $manager = new DockerComposeManager();
//        return  $manager->ps();
        return DockerHealth::parseComposeOut($manager->ps());
    }

    public static function parseComposeOut($input)
    {
        $words = explode("\n", $input);
//        removing headers
        unset($words[0], $words[1]);

        $arr = null;
        foreach ($words as $value) {
            if (!empty($value)) {
                $out = preg_split("/   +/", $value, -1, PREG_SPLIT_NO_EMPTY);
                $arr[] = [
                    'name' => $out[0],
                    'command' => $out[1],
                    'state' => $out[2],
                    'ports' => $out[3]
                ];
            }
        }

        return $arr;
    }
}
