<?php

namespace common\models\app;

use Yii;

/**
 * This is the model class for table "apps".
 *
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property integer $type
 * @property integer $category
 * @property string $timestamp
 */
class Apps extends \yii\db\ActiveRecord
{
    /**
     * Статическое приложение (простой html+js, выполняется только на стороне клиента)
     */
    const STATIC_TYPE = 0;

    /**
     * Динамическое приложение (выполняется на сервере и работает в Docker)
     */
    const DYNAMIC_TYPE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'url'], 'string'],
            [['name'], 'string', 'max' => 32],
            [['timestamp'], 'safe'],
            [['type', 'category', 'owner_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'App ID'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'name' => Yii::t('app', 'App name'),
            'description' => Yii::t('app', 'Description'),
            'url' => Yii::t('app', 'Link to app'),
            'type' => Yii::t('app', 'App type'),
            'category' => Yii::t('app', 'App category'),
            'timestamp' => Yii::t('app', 'App creating date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AppsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppsQuery(get_called_class());
    }

    public function set($app)
    {
        foreach ($app as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function isUnique($appName = null)
    {

        $result = null;
        if (!empty($appName))
            $result = $this::findOne(['name' => $appName]);
        else
            $result = $this::findOne(['name' => $this->name]);
        return empty($result);
    }

}
