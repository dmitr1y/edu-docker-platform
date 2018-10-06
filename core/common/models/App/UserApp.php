<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 06.10.18
 * Time: 22:04
 */

class UserApp
{
    public $id;
    public $name;
    public $description;
    public $url;


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
