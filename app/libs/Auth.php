<?php


class Auth
{
    public static function checkAuthentication()
    {
        // initialise the session
        Session::init();

        // if user is not logged in...
        if(!Session::userIsLoggedIn())
        {
            // destroy current session, redirect to login page
            Session::destroy();
            header('Location: ' . Config::get('BASE_URL') . 'login');

            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we
            // leave the application. this is not optimal and will be fixed in future releases
            exit;

        }
    }
}