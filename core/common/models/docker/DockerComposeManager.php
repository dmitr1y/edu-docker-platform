<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 04.10.18
 * Time: 15:51
 */

namespace common\models\docker;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DockerComposeManager
{
    private $storagePath;

    public function __construct()
    {
        $this->storagePath = realpath(\Yii::$app->basePath . '/../../storage');
    }

    /**
     *
     * @param $compose
     */
    public function setDockerCompose($compose)
    {
        foreach ($compose as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Сборка и запуск образов
     *
     * @param null $services
     * @return string|null
     */
    public function up($services = null)
    {
        $cmd = "up -d";
        if (!empty($services))
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    /**
     * Сборка образов
     *
     * @param null $services
     * @return string|null
     */
    public function build($services = null)
    {
        $cmd = "build";
        if (!empty($services))
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    /**
     * Остановка сервисов
     *
     * @param null $services
     * @return string|null
     */
    public function stop($services = null)
    {
        $cmd = "stop";
        if (!empty($services))
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    /**
     * Отображение информации о статусе сервисов
     *
     * @param null $services
     * @return string|null
     */
    public function ps($services = null)
    {
        $cmd = "ps";
        if (!empty($services))
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    /**
     * Отображение списка образов
     *
     * @param null $services
     * @return string|null
     */
    public function images($services = null)
    {
        $cmd = "images";
        if (!empty($services))
            $cmd .= ' ' . $services;
        return $this->exec($cmd);
    }

    /**
     * Остановка и удаление сервисов
     *
     * @param null $service
     * @return bool|string
     */
    public function down($service = null)
    {
//        todo окончательное удаление - вместе с томом данных или удаление только контейнера
        if (empty($service))
            return false;
        $log = "Stoping service: " . $this->stop($service);
        $manager = new DockerCompose();
        $log .= "\n Removing service: " . $manager->removeService(['name' => $service]);
        $manager->save();
        return $log;
    }

    /**
     * Выполнение команды
     *
     * @param $cmd
     * @return string|null
     */
    private function exec($cmd)
    {
        if (empty($cmd)) {
            return null;
        }

        $process = new Process('docker-compose -f ' . $this->storagePath . '/docker-compose.yml ' . $cmd);

        $process->run();

        // executes after the command finishes
        $log = $process->getOutput();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $log;
    }
}
