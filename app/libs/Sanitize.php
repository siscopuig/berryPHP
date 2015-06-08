<?php


class Sanitize {


    /**
     * Sanitize input data to escape unsafe characters
     *
     * this function is built in mind to add new cases and give more options to
     * sanitize input data. If $clean = false means that no apply any sanitation,
     * passing true as a parameter is when it works and return a sanitised input
     *
     * @param $key
     * @param bool $clean
     * @return bool|string
     */
    public static function post($key, $clean = false)
    {
        switch($key) {
            case $key:
                return  ($clean) ? trim(strip_tags($_POST[$key])) : $_POST[$key];
            break;
            default:
                return false;
            break;
        }
    }

    /**
     * Check for error upload file if error exist inside a file
     *
     * @return bool
     */
    public static function checkErrorUploadFile()
    {
        switch ($_FILES['file']['error']) {
            case 1:
                Session::add('feedback_negative', Message::get('ERROR_IS_BIGGER_THAN_UPLOAD_MAX_FILE'));
                break;
            case 2:
                Session::add('feedback_negative', Message::get('ERROR_IS_BIGGER_THAN_MAX_FILE_SIZE'));
                break;
            case 3:
                Session::add('feedback_negative', Message::get('ERROR_ONLY_PART_OF_THE_FILE_WAS_UPLOADED'));
                break;
            case 4:
                Session::add('feedback_negative', Message::get('ERROR_NO_FILE_UPLOADED'));
                break;
            case 5:
                Session::add('feedback_negative', Message::get('ERROR_NO_TEMPORARY_DIRECTORY_TO_STORE_THE_FILE'));
                break;
            case 6:
                Session::add('feedback_negative', Message::get('ERROR_PHP_COULD_NOT_WRITE_THE_FILE_TO_DISK'));
                break;
            case 7:
                Session::add('feedback_negative', Message::get('ERROR_UPLOAD_STOPPED_BY_A_PHP_FILE_EXTENSION'));
                break;
            default:
                return true;
        }
        // we have to return something anyway
        return false;
    }


    /**
     * Escape output characters
     *
     * htmlentities() — Convert all applicable characters to HTML entities
     * Escape output with the PHP htmlentities() function. Be sure you use ENT_QUOTES as the second argument
     * so that it escapes both single and double quotes. xss attack
     *
     *
     * @param $string
     * @return string
     */
    public static function escape($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }


    /**
     * Return values to the form - Useful to return input values back to the form
     *
     * @param $item
     * @return string
     */
    public static function get($item)
    {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } else if(isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }
}
