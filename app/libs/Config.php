<?php


/**
 * Class Config -
 */
class Config
{
    // here you can set a path to config file
    public static $config;

    /**
     * Get message based in a key given - go to config file and get message based on given key
     *
     * @param $key
     * @return bool
     */
    public static function get($key)
    {
        if (!self::$config) {

            $config_file_path = realpath(dirname(__FILE__) . '/../../').'/app/config/config.php';

            if (!file_exists($config_file_path)) {
                return false;
            }

            self::$config = require $config_file_path;
        }
        return self::$config[$key];
    }
}