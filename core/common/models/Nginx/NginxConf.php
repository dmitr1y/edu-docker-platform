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
    public $host;
    public $subdomain;
    public $listen;
    public $serviceName;
    public $proxyServer;
    public $ssl_certificate;
    public $ssl_certificate_key;
    public $proxyPort;
    public $pathToFile;

    public function __construct()
    {
        $this->host = "localhost";
        $this->subdomain = "apps";
        $this->pathToFile = realpath(\Yii::$app->basePath . '/../../storage/nginx/dind/user_confs');
    }


    public function setConf($conf)
    {
        foreach ($conf as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function create()
    {
        $conf = Scope::create()
            ->addDirective(Directive::create('location', '/' . $this->serviceName . '/', Scope::create()
                ->addDirective(Directive::create('proxy_pass', 'http://' . $this->proxyServer . ':' . $this->proxyPort . '/'))
            ))
            ->prettyPrint(0);
        file_put_contents($this->pathToFile . '/' . $this->serviceName . ".conf", $conf);
        return $conf;
    }
}
