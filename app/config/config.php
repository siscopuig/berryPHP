<?php
/*
 * realpath() expands all symbolic links and resolves references to '/./', '/../' and extra '/'
 * characters in the input path and returns the canonicalized absolute pathname.
 *
 * dirname() Given a string containing the path of a file or directory,
 * this function will return the parent directory's path.
 *
 * */

return array(
    // URL path. note the slash in the end!
    'BASE_URL' => 'http://' . $_SERVER['HTTP_HOST'] .
        str_replace('public', '', dirname($_SERVER['SCRIPT_NAME']) .'/'),

    // absolute path root computer
    'BASE_DIR' => realpath(dirname(__FILE__) . '/../../').'/',
    'CONTROLLER_PATH' => realpath(dirname(__FILE__) . '/../../') . '/app/controllers/',
    'MODEL_PATH'=> realpath(dirname(__FILE__) . '/../../') . '/app/models/',
    'VIEW_PATH' => realpath(dirname(__FILE__).'/../../') . '/app/views/',
    'MESSAGE_PATH' => realpath(dirname(__FILE__).'/../../') . '/app/config/messages.php',
    'TEST' => 'TEST CONFIG FILE',
    'PATH_UPLOAD_FILE' => realpath(dirname(__FILE__).'/../../') . '/app/public/images/uploads/',
    'PATH_THUMB_IMAGE' => realpath(dirname(__FILE__).'/../../') . '/app/public/images/thumbs/',
    'DEFAULT_PROFILE_PICTURE' => realpath(dirname(__FILE__).'/../../') . '/app/public/images/default_profile.jpg',

    // Database configuration
    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'mvc',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_PORT' => '3306',
    'DB_CHARSET' => 'utf8',

    // email server configuration
    'EMAIL_SMTP_AUTH' => true,
    'EMAIL_SMTP_HOST' => '',
    'EMAIL_SMTP_USERNAME' => '',
    'EMAIL_SMTP_PASSWORD' => '',
    'EMAIL_SMTP_SECURE' => 'ssl',
    'EMAIL_SMTP_PORT' => 465,


    // email content configuration for change password
    'EMAIL_REQUEST_FROM_SUBJECT' => 'Username request',
    'EMAIL_REQUEST_PASSWORD_RESET_PATH_URL' => 'login/verifyResetPassword',
    'EMAIL_REQUEST_PASSWORD_RESET_FROM_EMAIL' => 'yourEmailHere',
    'EMAIL_REQUEST_PASSWORD_RESET_FROM_NAME' => 'minimalMVC',
    'EMAIL_REQUEST_PASSWORD_RESET_SUBJECT' => 'Password reset for minimalMVC',
    'EMAIL_REQUEST_PASSWORD_RESET_BODY' => 'Please click on this link to reset your password: ',

    // email content configuration for account verification
    'EMAIL_VERIFICATION_PATH_URL' => 'login/verifyUserActivationVerificationCode',
    'EMAIL_FROM_EMAIL' => 'yourEmail@demo.com',
    'EMAIL_FROM_NAME' => 'minimalMVC',
    'EMAIL_FROM_SUBJECT' => 'Account activation for minimalMVC',
    'EMAIL_FROM_CONTENT' => 'Please click on this link to activate your account: ',



);