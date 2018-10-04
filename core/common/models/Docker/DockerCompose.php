<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 14:23
 */

namespace common\models\Docker;

use phpDocumentor\Reflection\Types\This;
use function Couchbase\defaultDecoder;

class DockerCompose
{
    public $version;
    public $file;
    public $services;
    public $networks;
    public $volumes;
    private $pathToFile;

    /**
     * DockerCompose constructor.
     * @param $pathToComposeFile - path to docker-compose.yml
     */
    public function __construct($pathToComposeFile = null)
    {
        if ($pathToComposeFile == null)
            $this->pathToFile = $_SERVER['DOCUMENT_ROOT'] . '/../storage/docker-compose.yml';
        else
            $this->pathToFile = $pathToComposeFile;
        $this->load($this->pathToFile);
    }

    public function addService($service)
    {
        $this->services[$service['name']] = $service;
        unset($this->services[$service['name']]['name']);
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

    public function save()
    {
        foreach ($this as $key => $value) {
            $this->file[$key] = $value;
        }
        unset($this->file['pathToFile'], $this->file['file']);
        yaml_emit_file($this->pathToFile, $this->file);
    }

    public function load($pathToFile)
    {
        $this->file = yaml_parse(file_get_contents($pathToFile, FILE_USE_INCLUDE_PATH));
        foreach ($this->file as $key => $value) {
            $this->{$key} = $value;
        }
//        $this->version = $this->file['version'];
//        $this->services = $this->file['services'];
//        $this->networks = $this->file['networks'];
//        $this->volumes = $this->file['volumes'];
    }

    private function prepareToExec()
    {

    }
}
