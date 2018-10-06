<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 17:54
 */

namespace common\models\Docker;
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

}
