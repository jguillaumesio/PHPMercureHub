<?php

namespace Jguillaumesio\PhpMercureHub;

class Config {

    private static $instance;
    private $config;

    public function __construct(){
        $configFilePath = getenv('MERCURE_CONFIG_PATH');
        if($configFilePath === false){
            throw new \Error('MISSING_ENV_MERCURE_CONFIG_PATH');
        }
        if (file_exists($configFilePath) && is_readable($configFilePath)) {
            $this->config = require $configFilePath;
        }
    }

    public static function getConfig(){
        if(!isset(self::$instance) || self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance->config;
    }

}