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

class CreateStaticNginxConf extends BaseObject implements \yii\queue\JobInterface
{
    public $serviceName;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return null
     */
    public function execute($queue)
    {
        if (!isset($this->serviceName) || empty($this->serviceName))
            return null;

        $nginxConf = new NginxConf();
        $nginxConf->proxyServer = $nginxConf->serviceName = DockerService::prepareServiceName($this->serviceName);
        $nginxConf->createStatic();
    }
}
