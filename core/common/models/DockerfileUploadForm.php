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

class DockerfileUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $dockerfile;
    private $path;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->path = realpath(\Yii::$app->basePath . '/../../storage/user_dockerfiles');
    }

    public function rules()
    {
        return [
            [['dockerfile'], 'file', 'skipOnEmpty' => false, 'extensions' => ''],
        ];
    }

    public function upload($userid = null)
    {
        if ($this->validate() && !empty($userid)) {
            $this->path = $this->path . '/' . $userid;

//            todo fix permissions
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }

            $this->path .= '/Dockerfile';
            $this->dockerfile->saveAs($this->path);
            return $this->path;
        } else {
            return false;
        }
    }
}
