<?php

namespace common\models\app;

use Yii;

/**
 * This is the model class for table "apps_log".
 *
 * @property int $id
 * @property string $appId
 * @property string $log
 * @property string $timestamp
 */
class AppsLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apps_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appId'], 'required'],
            [['log'], 'string'],
            [['timestamp'], 'safe'],
            [['appId'], 'string', 'max' => 255],
            [['appId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'appId' => Yii::t('app', 'App ID'),
            'log' => Yii::t('app', 'Log'),
            'timestamp' => Yii::t('app', 'Created at'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AppsLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppsLogQuery(get_called_class());
    }
}
