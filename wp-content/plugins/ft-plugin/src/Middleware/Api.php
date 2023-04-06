<?php
namespace PluginFrameticket\Middleware;

use PluginFrameticket\Control\Config;
use PluginFrameticket\Core\Apis;

class Api extends Apis
{
    public function __construct()
    {
        $objConfig = new Config();
        $this->_apiUrl = $objConfig->_apiUrl;
        $this->setHeader(['contentType' => 'json']);
        $this->setBasicAuth('marketplace',$objConfig->_apiKey);
    }

    public function setSession($name, $arr)
    {
        $_SESSION[$name] = $arr;
    }

    public function getSession($name)
    {
        return $_SESSION[$name];
    }
}
