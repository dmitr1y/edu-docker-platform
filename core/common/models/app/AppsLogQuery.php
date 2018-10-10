<?php

namespace common\models\app;

/**
 * This is the ActiveQuery class for [[AppsLog]].
 *
 * @see AppsLog
 */
class AppsLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AppsLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AppsLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
