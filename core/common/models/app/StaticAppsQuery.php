<?php

namespace common\models\app;

/**
 * This is the ActiveQuery class for [[StaticApps]].
 *
 * @see StaticApps
 */
class StaticAppsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return StaticApps[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return StaticApps|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
