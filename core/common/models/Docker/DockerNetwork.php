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
    public $driver;
    public $external;

    public function getNetwork()
    {
        return $this->prepareArray(
            [
                'name' => $this->name,
                'driver' => $this->driver,
                'external' => [
                    'name' => $this->external
                ]
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
        if (empty($array['name'])) {
            return null;
        }

        if (!empty($array['driver'])) {
            $drv = $array['driver'];
            if ($drv !== "bridge" || $drv !== "host" || $drv !== "overlay" || $drv !== "macvlan" || $drv !== "none")
                $array['driver'] = "bridge";
        }

        if (!empty($array['external'])) {
            if (empty($array['external']['name'])) {
                return null;
            }
        }
        return $array;
    }
}
