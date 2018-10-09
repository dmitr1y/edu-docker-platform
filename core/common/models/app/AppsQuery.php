<?php

namespace common\models\app;

/**
 * This is the ActiveQuery class for [[Apps]].
 *
 * @see Apps
 */
class AppsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Apps[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Apps|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
