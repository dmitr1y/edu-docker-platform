<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 18:37
 */

namespace common\models\Nginx;


use phpDocumentor\Reflection\Types\This;
use RomanPitak\Nginx\Config\Directive;
use RomanPitak\Nginx\Config\Exception;
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
        $this->pathToFile = realpath(\Yii::$app->basePath . '/../../storage/nginx');
    }


    public function setConf($conf)
    {
        foreach ($conf as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function createMainConf()
    {
        $conf = Scope::create()
            ->addDirective(Directive::create('server')
                ->setChildScope(Scope::create()
                    ->addDirective(Directive::create('listen', $this->listen))
                    ->addDirective(Directive::create('ssl_certificate', $this->ssl_certificate))
                    ->addDirective(Directive::create('ssl_certificate_key', $this->ssl_certificate_key))
                    ->addDirective(Directive::create('server_name', $this->serviceName . '.' . $this->subdomain . '.' . $this->host))
                    ->addDirective(Directive::create('location', '/', Scope::create()
                        ->addDirective(Directive::create('proxy_pass', 'http://' . $this->proxyServer . ':' . $this->proxyPort . '/' . $this->serviceName))
                    ))
                )
            )->prettyPrint(0);
        file_put_contents($this->pathToFile . '/main/' . $this->serviceName . ".conf", $conf);
        return $conf;
    }

    public function createSubConf()
    {
        $conf = Scope::create()
            ->addDirective(Directive::create('server')
                ->setChildScope(Scope::create()
                    ->addDirective(Directive::create('listen', $this->listen))
                    ->addDirective(Directive::create('location', '/' . $this->serviceName, Scope::create()
                        ->addDirective(Directive::create('proxy_pass', 'http://' . $this->proxyServer . ':' . $this->proxyPort . '/'))
                    ))
                )
            )->prettyPrint(0);
        file_put_contents($this->pathToFile . '/dind/' . $this->serviceName . ".conf", $conf);
        return $conf;
    }
}
