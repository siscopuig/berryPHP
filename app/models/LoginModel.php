<?php
	
	
class LoginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set data login in session
     *
     * @param $user_id
     * @param $user_name
     * @param $user_email
     * @param $user_account_role
     * @param $user_has_picture
     * @param $user_filename
     */
    public static function setDataLoginIntoSession(
        $user_id, $user_name, $user_email, $user_account_role, $user_has_picture, $user_filename)
    {
        Session::init();
        Session::set('user_logged_in', TRUE);
        Session::set('user_id', $user_id);
        Session::set('user_name', $user_name);
        Session::set('user_email', $user_email);
        Session::set('user_account_role', $user_account_role);
        Session::set('user_has_picture', $user_has_picture);
        Session::set('user_filename', $user_filename);

    }

    /**
     *  Returns the current state of the user's login
     *
     *  @return bool user's login status
     */
    public static function isUserLoggedIn()
    {
        return Session::userIsLoggedIn();
    }


    /**
     * Performs login action
     *
     * @param $user_name
     * @param $user_password
     * @return bool
     */
    public function loginAction($user_name, $user_password)
    {
        if (empty($user_name) OR empty($user_password)) {
            Session::add('feedback_negative', Message::get('MESSAGE_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        // we store user values in an array $result
        $user = $this->getUserData($user_name);

        // if user try to enter wrong details, database will return false and login will failed
        if(!$user) {
            Session::add('feedback_negative', Message::get('MESSAGE_LOGIN_FAILED'));
            return false;
        }

        // is user account is not activated will display a message
        if($user->user_active != 1) {
            Session::add('feedback_negative', Message::get('MESSAGE_USER_ACCOUNT_NOT_ACTIVE'));
            return false;
        }

        $this->setDataLoginIntoSession($user->user_id, $user->user_name,
            $user->user_email, $user->user_account_role, $user->user_has_picture, $user->user_filename);

        return true;
    }


    /**
     * Get user data from database
     *
     * @param $user_name
     * @return mixed
     */
    public function getUserData($user_name)
    {
        $sql = "SELECT user_id, user_name, user_email, user_password_hash , user_account_role, user_active,
                       user_filename, user_has_picture, user_creation_timestamp
                FROM users
                WHERE (user_name = :user_name OR user_email = :user_name)";

        $query = $this->db->prepare($sql);
        $query->execute(array(':user_name' => $user_name));
        return $query->fetchObject();
    }


    /**
     * Verify user activate account sent by mail, Activate user account in database
     *
     * @param $user_id
     * @param $user_activation_verification_code
     * @return bool
     */
    public function verifyUserActivationVerificationCode($user_id, $user_activation_verification_code)
    {
        $sql = "UPDATE users SET user_active = 1, user_activation_hash = NULL
                WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id, ':user_activation_hash' => $user_activation_verification_code));

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', Message::get('ACCOUNT_ACTIVATION_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', Message::get('ACCOUNT_ACTIVATION_FAILED'));
        return false;
    }


    /**
     * Request username by email in database
     *
     * @param $user_email
     * @return bool
     */
    public function requestUsernameByEmail($user_email)
    {
        if (!$this->validateUserEmail($user_email)) {
            return false;
        }
        $user_name = $this->getUsernameByEmail($user_email);
        if (!$user_name) {
            Session::add('feedback_negative', Message::get('REQUEST_EMAIL_NOT_FOUND_IN_DATABASE'));
            return false;
        }
        if (!$this->sendRequestUserEmail($user_name, $user_email)) {
            return false;
        }
        return true;
    }


    /**
     * Perform some validations for an email input
     *
     * @param $user_email
     * @return bool
     */
    public function validateUserEmail($user_email)
    {
        if (empty($user_email) AND strlen($user_email < 64)) {
            Session::add('feedback_negative',Message::get('VALIDATION_EMAIL_EMPTY'));
            return false;
        }

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_INVALID'));
            return false;
        }
        return true;
    }


    /**
     * Send username by mail -
     *
     * @param $user_name
     * @param $user_email
     * @return bool
     */
    public function sendRequestUserEmail($user_name, $user_email)
    {
        $body = 'This is your username: '. urlencode($user_name);

        $mail = new Mail;
        $mail_sent = $mail->sendMailPhpMailer($user_email, Config::get('EMAIL_FROM_EMAIL'),
            Config::get('EMAIL_FROM_NAME'), Config::get('EMAIL_REQUEST_FROM_SUBJECT'), $body);

        if ($mail_sent) {
            Session::add('feedback_positive', Message::get('MAIL_REQUEST_SENT_SUCCESSFUL'));
            return true;
        } else {
            Session::add('feedback_negative', Message::get('MAIL_REQUEST_FAILED').$mail->error);
            return false;
        }
    }


    /**
     * Get username by email given
     *
     * PDOStatement::fetchColumn() => returns a single column from the next row of a result set, but
     * most important is that return a string from an array.
     *
     * @param $user_email
     * @return string
     */
    public function getUsernameByEmail($user_email)
    {
        $sql = "SELECT user_name FROM users WHERE user_email = :user_email";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_email' => $user_email));
        return $query->fetchColumn();
    }


    /**
     * Perform the process to reset old password, send token by user to confirm user
     *
     * @param $user_email
     * @return bool
     */
    public function resetUserPassword($user_email)
    {
        if (!$this->validateUserEmail($user_email)) {
            return false;
        }

        $user_data = $this->getUserIdUsernameEmailByEmail($user_email);
        if (!$user_data) {
            Session::add('feedback_negative', Message::get('PASSWORD_RESET_UNKNOWN_DATABASE_ERROR'));
            return false;
        }

        // set temporary timestamp
        $temp_timestamp = time();

        // create a random number
        $user_password_reset_hash = sha1(uniqid(mt_rand(), true));

        $token = $this->setPasswordResetTokenInDatabase($user_data->user_name, $user_password_reset_hash, $temp_timestamp);
        if (!$token) {
            Session::add('feedback_negative', Message::get('PASSWORD_RESET_TOKEN_FAILED'));
            return false;
        }

        $mail_sent = $this->sendPasswordResetTokenByEmail($user_data->user_name, $user_data->user_email, $user_password_reset_hash);
        if (!$mail_sent) {
            Session::add('feedback_negative', Message::get('PASSWORD_RESET_EMAIL_SENT_FAILED'));
            return false;
        }

        return true;
    }


    /**
     * Get some user data by email given
     *
     * @param $user_email
     * @return mixed
     */
    public function getUserIdUsernameEmailByEmail($user_email)
    {
        $sql = "SELECT user_id, user_name, user_email FROM users WHERE user_email = :user_email LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':user_email' => $user_email));
        return $query->fetchObject();
    }

    /**
     * Set password reset token in database - returns true on success
     *
     * @param $user_name
     * @param $user_password_reset_hash
     * @param $temp_timestamp
     * @return bool
     */
    public function setPasswordResetTokenInDatabase($user_name, $user_password_reset_hash, $temp_timestamp)
    {
        $sql = "UPDATE users
                SET user_password_reset_hash = :user_password_reset_hash,
                    user_password_reset_timestamp = :user_password_reset_timestamp
                WHERE user_name = :user_name LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':user_password_reset_hash' => $user_password_reset_hash,
            ':user_password_reset_timestamp' => $temp_timestamp,
            ':user_name' => $user_name));
        if ($query->rowCount() == 1) {
            return true;
        }

        return false;
    }




    /**
     * Send password reset token
     *
     * @param $user_name
     * @param $user_email
     * @param $user_password_reset_hash
     * @return bool
     */
    public function sendPasswordResetTokenByEmail($user_name, $user_email, $user_password_reset_hash)
    {
        $body = Config::get('EMAIL_REQUEST_PASSWORD_RESET_BODY') . Config::get('BASE_URL') .
                Config::get('EMAIL_REQUEST_PASSWORD_RESET_PATH_URL') . '/' . urldecode($user_name) . '/' . urldecode($user_password_reset_hash);
        $mail = new Mail();
        $mail_sent = $mail->sendMailPhpMailer(
            $user_email, Config::get('EMAIL_FROM_EMAIL'),
            Config::get('EMAIL_FROM_NAME'),
            Config::get('EMAIL_REQUEST_PASSWORD_RESET_SUBJECT'), $body);
        if ($mail_sent) {
            Session::add('feedback_positive', Message::get('PASSWORD_RESET_EMAIL_SENT_SUCCESSFUL'));
            return true;
        } else {
            return false;
        }
    }


    /**
     * Verify reset password code sent by mail
     *
     * @param $user_name
     * @param $user_password_reset_code
     * @return bool|mixed
     */
    public function verifyResetPassword($user_name, $user_password_reset_code)
    {
        $sql = "SELECT user_id, user_password_reset_timestamp
                FROM users
                WHERE user_name = :user_name
                AND user_password_reset_hash = :user_password_reset_hash LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':user_name' => $user_name,
            ':user_password_reset_hash' => $user_password_reset_code));
        if ($query->rowCount() == 0) {
            return false;
        }
        // fetchObject — Fetches the next row and returns it as an object.
        $data = $query->fetchObject();

        // create a timestamp one hour ago
        $timestamp_hour_ago = time() - 3600;

        if ($data->user_password_reset_timestamp > $timestamp_hour_ago) {
            Session::add('feedback_positive', Message::get('PASSWORD_RESET_LINK_SENT_IS_VALID'));
            return true;
        } else {
            Session::add('feedback_negative', Message::get('PASSWORD_RESET_LINK_SENT_IS_NOT_VALID'));
            return false;
        }
    }


    /**
     * Set new user password
     *
     * @param $user_name
     * @param $user_password_reset_code
     * @param $user_password
     * @param $user_password_again
     * @return bool
     */
    public function setNewUserPassword($user_name, $user_password_reset_code, $user_password, $user_password_again)
    {
        if (!$this->validateNewUserPassword($user_password, $user_password_again)) {
            return false;
        }

        $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

        if (!$this->saveNewPasswordInDatabase($user_name, $user_password_hash, $user_password_reset_code)) {
            Session::add('feedback_negative', Message::get('NEW_PASSWORD_CHANGE_UNSUCCESSFUL'));
            return false;
        }

        Session::add('feedback_positive', Message::get('NEW_PASSWORD_CHANGED_SUCCESSFULLY'));
        return true;
    }


    /**
     * Validate new user password
     *
     * @param $user_password
     * @param $user_password_again
     * @return bool
     */
    public function validateNewUserPassword($user_password, $user_password_again)
    {
        if (empty($user_password) OR empty($user_password_again)) {
            Session::add('feedback_negative', Message::get('VALIDATION_PASSWORD_EMPTY_OR_PASSWORD_AGAIN_EMPTY'));
            return false;
        }
        if ($user_password !== $user_password_again) {
            Session::add('feedback_negative', Message::get('VALIDATION_PASSWORD_DOES_NOT_MATCH_WITH_PASSWORD_AGAIN'));
            return false;
        }
        if (strlen($user_password) < 6) {
            Session::add('feedback_negative', Message::get('VALIDATION_PASSWORD_LESS_THAN_6_CHARACTERS'));
            return false;
        }
        return true;
    }


    /**
     * Save new password in a database
     *
     * @param $user_name
     * @param $user_password_hash
     * @param $user_password_reset_code
     * @return bool
     */
    public function saveNewPasswordInDatabase($user_name, $user_password_hash, $user_password_reset_code)
    {
        $sql = "UPDATE users SET user_password_hash = :user_password_hash, user_password_reset_hash = NULL
                WHERE user_name = :user_name AND user_password_reset_hash = :user_password_reset_hash";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':user_password_hash' => $user_password_hash,
            ':user_password_reset_hash' => $user_password_reset_code,
            ':user_name' => $user_name));

        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }


    /**
     *  Logout, delete session
     */
    public static function logout()
    {
        Session::destroy();
    }
}
