<?php

namespace common\models\app;

/**
 * This is the ActiveQuery class for [[AppsCategory]].
 *
 * @see AppsCategory
 */
class AppsCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AppsCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AppsCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
