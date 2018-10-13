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
use common\models\nginx\NginxConf;
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
     * @var Apps $appModel
     */
    public $appModel;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $this->appModel->status = '3';
        $this->appModel->save();

        $log = AppsLog::findOne(['appId' => $this->appModel->id]);
        if (!empty($log)) {
            try {
                $log->delete();
            } catch (StaleObjectException $e) {
            } catch (\Throwable $e) {
            }
        }
        $manager = new DockerComposeManager();
        $manager->down($this->serviceName);
        try {
            $this->appModel->delete();
        } catch (StaleObjectException $e) {
        } catch (\Throwable $e) {
        }
    }
}
