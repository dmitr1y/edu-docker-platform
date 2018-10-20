<?php

namespace common\models\app;

use common\models\docker\DockerService;
use Yii;

/**
 * This is the model class for table "apps".
 *
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $description
 * @property string $file
 * @property string $image
 * @property string $url
 * @property integer $port
 * @property integer $status
 * @property string $timestamp
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
            [['timestamp'], 'safe'],
            [['port', 'status', 'owner_id'], 'integer'],
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
            'file' => Yii::t('app', 'Dockerfile'),
            'image' => Yii::t('app', 'Image from Docker Hu'),
            'url' => Yii::t('app', 'Link to app'),
            'port' => Yii::t('app', 'App port'),
            'status' => Yii::t('app', 'App status'),
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

    public function removeFile($userId = null)
    {
        if (file_exists($this->file)) {
            if (is_dir($this->file) && !empty($userId))
                return $this->removeStaticApp($userId);
            else {
                unlink($this->file);
                rmdir(str_replace('/Dockerfile', '', $this->file));
                return true;
            }
        }
        return false;
    }

    private function removeStaticApp($userId)
    {
        if (empty($userId))
            return false;
        if (is_dir(\Yii::$app->basePath . '/../../storage/user_apps/' . DockerService::prepareServiceName($this->name)))
            return $this->rrmdir(\Yii::$app->basePath . '/../../storage/user_apps/' . $userId . '/' . DockerService::prepareServiceName($this->name));
        return false;
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object))
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            return rmdir($dir);
        }
        return false;
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
