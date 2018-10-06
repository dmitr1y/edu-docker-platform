<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 06.10.18
 * Time: 23:25
 */

namespace common\models\Docker;


class Dockerfile
{
    public $pathToFile;
    public $image;
    public $file;

    public function __construct()
    {
        $this->file = null;
    }

    public function set($dockerfile)
    {
        foreach ($dockerfile as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function get()
    {
        return $this->file;
    }
}
