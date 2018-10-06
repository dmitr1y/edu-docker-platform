<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 18:37
 */

namespace common\models\Nginx;


use RomanPitak\Nginx\Config\Directive;
use RomanPitak\Nginx\Config\Scope;

class NginxConf
{
    public $serviceName;
    public $proxyServer;
    public $proxyPort;
    public $pathToFile;

    public function __construct()
    {
        $this->pathToFile = realpath(\Yii::$app->basePath . '/../../storage/nginx/dind/user_confs');
    }


    public function setConf($conf)
    {
        foreach ($conf as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Building and saving Nginx conf file for apps
     * @return string - Nginx conf file
     */
    public function create()
    {
        $conf = Scope::create()
            ->addDirective(Directive::create('location', '/' . $this->serviceName . '/', Scope::create()
                ->addDirective(Directive::create('proxy_set_header', 'X-Forwarded-Host $host'))
                ->addDirective(Directive::create('proxy_set_header', 'X-Forwarded-Server $host'))
                ->addDirective(Directive::create('proxy_set_header', 'X-Forwarded-For $proxy_add_x_forwarded_for'))
                ->addDirective(Directive::create('proxy_pass', 'http://' . $this->proxyServer . ':' . $this->proxyPort . '/'))
            ))
            ->prettyPrint(0);
        file_put_contents($this->pathToFile . '/' . $this->serviceName . ".conf", $conf);
        return $conf;
    }

    /**
     * Deleting Nginx conf file
     * @return bool true - file deleted, false - isn't
     */
    public function remove()
    {
        if (!empty($this->serviceName))
            return unlink($this->pathToFile . '/' . $this->serviceName);
        return false;
    }
}
