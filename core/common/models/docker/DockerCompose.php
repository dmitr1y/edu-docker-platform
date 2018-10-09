<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 14:23
 */

namespace common\models\docker;

use Symfony\Component\Yaml\Yaml;

class DockerCompose
{
    public $version;
    public $services;
    public $networks;
    public $volumes;
    private $file;
    private $pathToFile;

    /**
     * DockerCompose constructor.
     * @param $pathToComposeFile - path to docker-compose.yml
     */
    public function __construct($pathToComposeFile = null)
    {
        if ($pathToComposeFile == null)
            $this->pathToFile = realpath(\Yii::$app->basePath . '/../../storage/docker-compose.yml');
        else
            $this->pathToFile = $pathToComposeFile;
        $this->load();
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
        $name = $network['name'];
        unset($network['name']);
        foreach ($network as $key => $value) {
            if (isset($network[$key]) && !empty($network[$key]))
                $this->networks[$name][$key] = $value;
        }
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
            if (isset($this->{$key}) || !empty($this->{$key})) {
                $this->file[$key] = $value;
            }
        }
//        exit;
        unset($this->file['pathToFile'], $this->file['file']);
        $yaml = Yaml::dump($this->file);
        file_put_contents($this->pathToFile, $yaml);
    }

    public function load()
    {
        $this->file = Yaml::parseFile($this->pathToFile);
        foreach ($this->file as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
