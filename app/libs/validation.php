<?php


class validation
{


    public static function post($key, $empty = null)
    {
        if(isset($_POST[$key]))
        {
            if (!empty($_POST[$key])) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}