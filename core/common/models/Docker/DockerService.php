<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 17:54
 */

class DockerService
{
    public $name;
    public $image;
    public $ports;
    public $networks;
    public $dependsOn;
    public $volumes;
    public $limits;

    public function __construct()
    {

    }
}
