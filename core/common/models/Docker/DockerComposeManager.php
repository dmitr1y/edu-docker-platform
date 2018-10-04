<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 04.10.18
 * Time: 15:51
 */

namespace common\models\Docker;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DockerComposeManager
{
    private $dockerCompose;
    private $storagePath;

    public function __construct()
    {
        $this->storagePath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../storage');
//        $this->storagePath = realpath(\Yii::$app->basePath.'/../../storage');
    }

    public function setDockerCompose($compose)
    {

    }

    public function up($services = null)
    {
        $cmd = "docker-compose up";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    public function build($services = null)
    {
        $cmd = "docker-compose build";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    public function stop($services = null)
    {
        $cmd = "docker-compose stop";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    public function down($services = null)
    {
        $cmd = "docker-compose down";
        if ($services !== null)
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    private function exec($cmd)
    {
        $process = new Process($cmd);
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
