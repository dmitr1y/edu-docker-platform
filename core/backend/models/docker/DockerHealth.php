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

    public static function getContainersList()
    {
        $manager = new DockerComposeManager();
        return DockerHealth::parseContainersList($manager->ps());
    }

    public static function getImagesList()
    {
        $manager = new DockerComposeManager();
        return DockerHealth::parseImagesList($manager->images());
    }

    public static function parseContainersList($input)
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

    public static function parseImagesList($input)
    {
        $words = explode("\n", $input);
//        removing headers
        unset($words[0], $words[1]);

        $arr = null;
        foreach ($words as $value) {
            if (!empty($value)) {
                $out = preg_split("/   +/", $value, -1, PREG_SPLIT_NO_EMPTY);
                $arr[] = [
                    'container' => $out[0],
                    'repository' => $out[1],
                    'tag' => $out[2],
                    'image_id' => $out[3],
                    'size' => $out[4]
                ];
            }
        }

        return $arr;
    }
}
