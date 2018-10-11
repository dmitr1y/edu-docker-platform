<?php

namespace common\models\app;

use Yii;

/**
 * This is the model class for table "apps".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $file
 * @property string $image
 * @property string $url
 * @property integer $port
 */
class Apps extends \yii\db\ActiveRecord
{
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
            [['description', 'file', 'url'], 'string'],
            [['name'], 'string', 'max' => 32],
            [['image'], 'string', 'max' => 255],
            [['port'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'App ID'),
            'name' => Yii::t('app', 'App name'),
            'description' => Yii::t('app', 'Description'),
            'file' => Yii::t('app', 'Dockerfile'),
            'image' => Yii::t('app', 'Image from Docker Hu'),
            'url' => Yii::t('app', 'Link to app'),
            'port' => Yii::t('app', 'App port'),
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

    public function create()
    {

    }
}
