<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 12.10.18
 * Time: 23:56
 */

namespace common\models\nginx;

use common\models\docker\DockerService;
use yii\base\BaseObject;
use yii\queue\Queue;

class RemoveNginxConf extends BaseObject implements \yii\queue\JobInterface
{
    public $serviceName;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $nginxConf = new NginxConf();
        $nginxConf->serviceName = DockerService::prepareServiceName($this->serviceName);
        $nginxConf->remove();
    }
}
