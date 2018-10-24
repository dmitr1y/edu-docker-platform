<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 12.10.18
 * Time: 23:52
 */

namespace common\models\docker;

use common\models\app\Apps;
use common\models\app\AppsLog;
use common\models\app\DockerApps;
use yii\base\BaseObject;
use yii\db\StaleObjectException;
use yii\queue\Queue;

class RemoveDockerService extends BaseObject implements \yii\queue\JobInterface
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
        $app = Apps::findOne(['id' => $this->appModel->app_id]);
        $log = AppsLog::findOne(['appId' => $this->appModel->app_id]);
        if (!empty($log)) {
            try {
                $log->delete();
            } catch (StaleObjectException $e) {
            } catch (\Throwable $e) {
            }
        }
        $manager = new DockerComposeManager();
        $manager->down($this->serviceName);
        $this->appModel->remove();
        $app->delete();
    }
}
