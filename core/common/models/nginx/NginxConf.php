<?php
/**
 * Created by PhpStorm.
 * User: dmitriy
 * Date: 01.10.18
 * Time: 18:37
 */

namespace common\models\nginx;


use phpDocumentor\Reflection\Types\This;
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
    public function createProxy()
    {
        $conf = Scope::create()
            ->addDirective(Directive::create('location', '/' . $this->serviceName . '/', Scope::create()
                ->addDirective(Directive::create('proxy_set_header', 'X-Forwarded-Host $host'))
                ->addDirective(Directive::create('proxy_set_header', 'X-Forwarded-Server $host'))
                ->addDirective(Directive::create('proxy_set_header', 'X-Forwarded-For $proxy_add_x_forwarded_for'))
                ->addDirective(Directive::create('proxy_pass', 'http://' . $this->proxyServer . ':' . $this->proxyPort . '/'))
            ))
            ->prettyPrint(0);
        $this->save($this->pathToFile, $this->serviceName . ".conf", $conf);
        return $conf;
    }

    public function createStatic($userId = null)
    {
        if (empty($userId))
            return null;
        $conf = Scope::create()
            ->addDirective(Directive::create('location', '/' . $this->serviceName . '/', Scope::create()
                ->addDirective(Directive::create('root', '/usr/share/nginx/html/' . $userId))//todo change root dir
                ->addDirective(Directive::create('index', 'index.html index.htm'))
            ))
            ->prettyPrint(0);

        //        todo for debug only
//        if (!file_exists(\Yii::$app->basePath . '/../../storage/user_apps/' . $this->serviceName)) {
//            mkdir(\Yii::$app->basePath . '/../../storage/user_apps/' . $this->serviceName, 0777, true);
//        }

        $this->save($this->pathToFile, $this->serviceName . ".conf", $conf);
        return $conf;
    }

    private function save($path, $fileName, $file)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path . '/' . $fileName, $file);
    }

    /**
     * Deleting Nginx conf file
     * @return bool true - file deleted, false - isn't
     */
    public function remove()
    {
        if (!empty($this->serviceName) && file_exists($this->pathToFile . '/' . $this->serviceName . '.conf'))
            return unlink($this->pathToFile . '/' . $this->serviceName . '.conf');
        return false;
    }
}
