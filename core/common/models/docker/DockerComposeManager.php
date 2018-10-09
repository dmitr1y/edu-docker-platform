<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 04.10.18
 * Time: 15:51
 */

namespace common\models\docker;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DockerComposeManager
{
    private $dockerCompose;
    private $storagePath;

    public function __construct()
    {
        $this->storagePath = realpath(\Yii::$app->basePath . '/../../storage');
    }

    public function setDockerCompose($compose)
    {
        foreach ($compose as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function up($services = null)
    {
        $cmd = "up -d";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    public function build($services = null)
    {
        $cmd = "build";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    public function stop($services = null)
    {
        $cmd = "stop";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    public function down($services = null)
    {
        $cmd = "down";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    private function exec($cmd)
    {
        if (!isset($cmd) || empty($cmd))
            return null;

        $process = new Process('docker-compose -f ' . $this->storagePath . '/docker-compose.yml ' . $cmd);
        $process->run();

        // executes after the command finishes
        $log = $process->getOutput();
        if ($process->isSuccessful()) {
            $log = "[SUCCESS] " . $log;
        } else {
            //            throw new ProcessFailedException($process);
            $log = "[ERROR " . $process->getExitCodeText() . "] " . $process->getErrorOutput();
        }

        return $log;
    }
}
