<?php

namespace Jguillaumesio\PhpMercureHub;

class Config {

    private static $config;

    public function __construct(){
        if(!isset(self::$config) || self::$config === null){
            $configFilePath = getenv('MERCURE_CONFIG_PATH');
            if($configFilePath === false){
                throw new \Error('MISSING_ENV_MERCURE_CONFIG_PATH');
            }
            if (file_exists($configFilePath) && is_readable($configFilePath)) {
                self::$config = require $configFilePath;
            }
        }
        return self::$config;
    }

}