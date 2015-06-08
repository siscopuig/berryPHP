<?php

/**
 * Class Session
 *
 * handles the session stuff. creates session when no one exists, sets and gets values,
 * and closes the session properly (=logout). Not to forget the check if the user is logged in or not.
 */
class Session
{

    /**
     *  Starts the session
     */
	public static function init()
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
	}


    /**
     *  Set a specific value to a specific key of the session
     *
     *  @param $key
     *  @param $value
     */
    public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}


    /**
     *  Add a value as a new array element to the key
     *  useful for collecting error messages etc..
     *
     *  @param $key
     *  @return mixed
     */
    public static function get($key)
	{
		if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }

        return false;
	}


    /**
     *  Add a value as a new array element to the key, useful for collecting error messages
     *
     *  @param $key
     *  @param $value
     */
    public static function add($key, $value)
    {
        $_SESSION[$key] = $value;
    }


    /**
     *  Under test for possible usages
     */
    public static function showSessionData()
	{
		echo '<pre>';
		print_r($_SESSION);
		echo '<pre>';
	}


    /**
     *  Deletes the session
     */
    public static function destroy()
	{
		session_destroy();
	}


    /**
     *  Checks if the user is logged in or not
     *
     *  @return bool user login status
     */
    public static function userIsLoggedIn()
    {
        return (Session::get('user_logged_in') ? true : false );
    }
	
}