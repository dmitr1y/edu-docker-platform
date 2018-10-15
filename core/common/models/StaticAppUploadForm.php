<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 14.10.18
 * Time: 23:17
 */

namespace common\models;


use yii\base\Model;
use yii\web\UploadedFile;
use ZipArchive;

class StaticAppUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $app;
    private $path;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->path = realpath(\Yii::$app->basePath . '/../../storage/user_apps');
    }

    public function rules()
    {
        return [
            [['app'], 'file', 'skipOnEmpty' => false, 'extensions' => 'zip, html, htm, js'],
        ];
    }

    public function upload($userId = null, $appName = null)
    {
        if ($this->validate() && !empty($userId) && !empty($appName)) {
            $this->path = $this->path . '/' . $userId . '/' . $appName;
//            todo fix permissions
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
            if ($this->app->extension === 'zip') {
                $zip = new ZipArchive;
                $res = $zip->open($this->app->tempName);
                if ($res === TRUE) {
                    $zip->extractTo($this->path);
                    $zip->close();
                } else
                    return false;
            } else
                $this->app->saveAs($this->path . '/' . $this->app->baseName . '.' . $this->app->extension);
            return $this->path;
        } else {
            return false;
        }
    }
}