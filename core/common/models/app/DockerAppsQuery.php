<?php

namespace common\models\app;

/**
 * This is the ActiveQuery class for [[DockerApps]].
 *
 * @see DockerApps
 */
class DockerAppsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DockerApps[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DockerApps|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
