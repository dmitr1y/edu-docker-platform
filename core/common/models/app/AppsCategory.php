<?php

namespace common\models\app;

use Yii;

/**
 * This is the model class for table "apps_category".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 */
class AppsCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apps_category';
    }

    /**
     * {@inheritdoc}
     * @return AppsCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppsCategoryQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['timestamp'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}
