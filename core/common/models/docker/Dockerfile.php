<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 06.10.18
 * Time: 23:25
 */

namespace common\models\docker;


class Dockerfile
{
    public $pathToFile;
    public $appName;
    public $file;

    public function __construct()
    {
//        $this->file = null;
        $this->pathToFile = realpath(\Yii::$app->basePath . '/../../storage/user_apps');
    }

    public function set($dockerfile)
    {
        foreach ($dockerfile as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function get()
    {
        if (isset($this->pathToFile, $this->appName))
            return file_get_contents($this->pathToFile . '/' . $this->appName . '/Dockerfile', $this->file);
        else return null;
    }

    public function save()
    {
        if (mkdir($this->pathToFile . '/' . $this->appName, 0777, true) &&
            file_put_contents($this->pathToFile . '/' . $this->appName . '/Dockerfile', $this->file))
            return true;
        return false;
    }
}
