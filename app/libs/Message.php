<?php


/**
 * Class Message - performs the action to grab a message given based on a key
 */
class Message
{
    private static $message;

    /**
     * Get message from key given
     *
     * @param $key
     * @return null
     */
    public static function get($key)
    {
        if(!$key)
        {
            return null;
        }

        // load config file, this is only done once per application lifecycle
        if(!self::$message)
        {
            self::$message = require(Config::get('MESSAGE_PATH'));
        }

        // check if array key exists
        if(!array_key_exists($key, self::$message))
        {
            return null;
        }

        return self::$message[$key];
    }
}