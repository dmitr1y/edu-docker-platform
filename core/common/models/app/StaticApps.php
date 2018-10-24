<?php

namespace common\models\app;

use yii\db\StaleObjectException;

/**
 * This is the model class for table "static_apps".
 *
 * @property int $id
 * @property int $app_id
 * @property string $path_to_index
 * @property string $index_name
 */
class StaticApps extends \yii\db\ActiveRecord
{
    private $userDirPath;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->userDirPath = \Yii::$app->basePath . '/../../storage/user_apps/';
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'static_apps';
    }

    /**
     * {@inheritdoc}
     * @return StaticAppsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StaticAppsQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_id'], 'integer'],
            [['path_to_index', 'index_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'path_to_index' => 'Path To Index',
            'index_name' => 'Index Name',
        ];
    }

    public function remove()
    {
        if ($this->rrmdir($this->path_to_index))
            try {
                return $this->delete();
            } catch (StaleObjectException $e) {
            } catch (\Throwable $e) {
            }
        return false;
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object))
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            return rmdir($dir);
        }
        return false;
    }
}
