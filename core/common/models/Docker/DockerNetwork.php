<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 17:59
 */

namespace common\models\Docker;

use http\Exception\UnexpectedValueException;

class DockerNetwork
{
    public $name;
//    public $mode;
    public $driver;

//    public $aliases;

    public function getNetwork()
    {
        return $this->prepareArray(
            [
                'name' => $this->name,
                'driver' => $this->driver
            ]
        );
    }

    public function setNetwork($network)
    {
        foreach ($network as $key => $value) {
            $this->{$key} = $value;
        }
    }

    private function prepareArray($array)
    {
        foreach ($array as $key => $value) {
            if (!isset($array[$key]))
                unset($array[$key]);
            if ($key === "driver") {
                if ($value !== "bridge" || $value !== "host" || $value !== "overlay" || $value !== "macvlan" || $value !== "none")
                    $array[$key] = "bridge";
            }
        }
        return $array;
    }
}
