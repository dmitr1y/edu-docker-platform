<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 12.10.18
 * Time: 23:52
 */

namespace common\models\docker;

use common\models\app\AppsLog;
use common\models\app\DockerApps;
use yii\base\BaseObject;
use yii\queue\Queue;

class StopDockerService extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var string $serviceName
     */
    public $serviceName;
    /**
     * @var DockerApps $appModel
     */
    public $appModel;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $this->appModel->status = '3';
        $this->appModel->save();
        $log = AppsLog::findOne(['appId' => $this->appModel->app_id]);
        if (empty($log)) {
            $log = new AppsLog();
            $log->appId = $this->appModel->app_id;
        }
        $manager = new DockerComposeManager();
        $log->log = $manager->stop($this->serviceName);
        $log->save();
        $this->appModel->status = '0';
        $this->appModel->save();
    }
}
