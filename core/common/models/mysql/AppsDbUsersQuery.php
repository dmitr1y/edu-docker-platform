<?php

namespace common\models\mysql;

/**
 * This is the ActiveQuery class for [[AppsDbUsers]].
 *
 * @see AppsDbUsers
 */
class AppsDbUsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AppsDbUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AppsDbUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
