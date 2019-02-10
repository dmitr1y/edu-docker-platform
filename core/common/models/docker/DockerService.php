<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 17:54
 */

namespace common\models\docker;

class DockerService
{
    public $name;
    public $image;
    public $build;
    public $containerName;
    public $environment;
    public $command;
    public $volumes;
    public $ports;
    public $networks;
    public $dependsOn;
    public $limits;

    public function getService()
    {
        return $this->prepareArray(
            [
                'name' => $this->name,
                'image' => $this->image,
                'build' => $this->build,
                'containerName' => $this->containerName,
                'command' => $this->command,
                'environment' => $this->environment,
                'ports' => $this->ports,
                'networks' => $this->networks,
                'volumes' => $this->volumes
            ]
        );
    }

    public function setService($service)
    {
        foreach ($service as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @param $array - DockerService fields in array
     * @return mixed - clean array for DockerCompose
     */
    private function prepareArray($array)
    {
        foreach ($array as $key => $value) {
            if (!isset($array[$key]))
                unset($array[$key]);
        }
        return $array;
    }

    /**
     * Преобразует название сервиса в нужный формат
     *
     * @param $name
     * @return mixed|null|string|string[]
     */
    public static function prepareServiceName($name)
    {
        return DockerService::translit(strtolower(preg_replace('/\s+/', '-', $name)));
    }

    /**
     * Транслит русского текста в английский
     *
     * @param $str
     * @return mixed|null|string|string[]
     */
    private static function translit($str)
    {
        $tr = array("а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "j", "з" => "z", "и" => "i", "й" => "y",
            "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shh", "ъ" => "", "ы" => "y", "ь" => "", "э" => "e", "ю" => "u", "я" => "ya", "—" => "-",
            "«" => "", "»" => "", "…" => "", " " => "-", "№" => "#");
        $str = mb_strtolower($str, 'utf-8');
        // $str = preg_replace("/\s+/",' ',$str);
        $str = strtr(trim($str), $tr);
        $str = trim(preg_replace("/\-+/", '-', $str), '- ');
        $str = preg_replace('~[^a-z0-9/-]~', '', $str);
        return $str;
    }
}
