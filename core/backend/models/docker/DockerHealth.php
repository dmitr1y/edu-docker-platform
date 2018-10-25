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
        return [$manager->ps(), DockerHealth::parseComposeOut($manager->ps())];
    }

    public static function parseComposeOut($input)
    {
        $words = explode("\n", $input);
//        removing headers
        unset($words[0], $words[1]);
        $arr = null;

        foreach ($words as $value) {
            $out = preg_split("/[\t,]/", $value);
//            $out = explode("\t ", $value);
//            unset($out[1]);
            $arr[] = $out;
        }

        return $arr;
    }
}
