<?php


class Redirect
{
    /**
     *  base URL  eg: http://localhost:8888/mvc/
     */
    public static function baseUrl()
    {
        header("location: " . Config::get('BASE_URL'));
    }

    public static function to($path)
    {
        header("Location: " . Config::get('BASE_URL') . $path);
    }


}