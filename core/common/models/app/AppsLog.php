<?php

namespace common\models\app;

use Yii;

/**
 * This is the model class for table "apps_log".
 *
 * @property int $id
 * @property string $appId
 * @property string $build
 * @property string $run
 * @property string $error
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
            [['build', 'run', 'error'], 'string'],
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
            'build' => Yii::t('app', 'Build'),
            'run' => Yii::t('app', 'Run'),
            'error' => Yii::t('app', 'Error'),
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
