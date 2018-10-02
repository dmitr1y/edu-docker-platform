<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 14:23
 */

namespace common\models\Docker;

class DockerCompose
{
    public $file;
    public $version;
    public $services;
    public $networks;
    public $volumes;

    /**
     * DockerCompose constructor.
     * @param $pathToFile - path to docker-compose.yml
     */
    public function __construct($pathToFile = __DIR__ . "/test.yml")
    {
        $this->file = yaml_parse(file_get_contents($pathToFile, FILE_USE_INCLUDE_PATH));
        $this->version = $this->file['version'];
        $this->services = $this->file['services'];
        $this->networks = $this->file['networks'];
        $this->volumes = $this->file['volumes'];
    }

    public function addService($service)
    {
        $this->services[$service['name']] = $service;
    }

    /**
     * @param $network - DockerNetwork model
     */
    public function addNetwork($network)
    {
        $this->networks[] = $network;
    }

    /**
     * @param $service - DockerService model
     */
    public function removeService($service)
    {
        unset($this->services[$service['name']]);
    }

    /**
     * @param $network - DockerNetwork model
     */
    public function removeNetwork($network)
    {
        unset($this->networks[$network['name']]);
    }

    /**
     * @param $volume - volume name
     */
    public function addVolume($volume)
    {
        $this->volumes[] = $volume;
    }

    /**
     * @param $volume - volume name
     */
    public function removeVolume($volume)
    {
        unset($this->volumes[array_search($volume, $this->volumes)]);
    }
}
