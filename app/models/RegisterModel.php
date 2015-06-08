<?php

// @TODO email verification, send an email to the user
// @TODO password validation function

// @todo implement password hash verification
//echo $user_activation_hash = sha1(uniqid(mt_rand(), true));

// @todo add user_creation_timestamp, user_password_hash in future



class RegisterModel extends Model
{
	public function __construct()
	{
		parent::__construct();
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
     *  register new user
     *
     *  @param $user_name
     *  @param $user_email
     *  @param $user_password
     *  @param $user_password_again
     *  @return bool
     */
    public function registerNewUserAction($user_name, $user_email, $user_password, $user_password_again)
    {
        if (!$this->validateUsername($user_name)) {
            return false;
        }
        if (!$this->validateUserEmail($user_email)) {
            return false;
        }
        if (!$this->validateUserPassword($user_password, $user_password_again)) {
            return false;
        }

        // password_hash() creates a new password hash using a strong one-way hashing algorithm
        // PASSWORD_DEFAULT - Use the bcrypt algorithm (default as of PHP 5.5.0)
        // it is recommended to store the result in a database column that can expand beyond
        // 60 characters (255 characters would be a good choice).
        // http://php.net/manual/en/function.password-hash.php

        // Storing hashed passwords prevents users’ accounts from becoming compromised if an
        // unauthorized person gets a peek at your username and password database
        $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

        // generate a random hash for user activation
        $user_activation_hash = sha1(uniqid(mt_rand(), true));

        if (!$this->insertNewUserInDatabase($user_name, $user_email, $user_password_hash, $user_activation_hash, time())) {
            return false;
        }

        $user_id = $this->getUserIdByUsername($user_name);

        // send and email to activate account, returns true on success and exit
        if ($this->sendEmailVerification($user_id, $user_email, $user_activation_hash)) {
            Session::add('feedback_positive', Message::get('REGISTER_ACTIVATION_MAIL_SENT_SUCCESSFUL'));
            return true;
        }

        // if reach this point means that some problem happen and no mail has been sent - delete registration
        $this->rollbackRegisterByUserId($user_id);
        Session::add('feedback_negative', Message::get('REGISTER_ACTIVATION_MAIL_SENDING_FAILED'));
        return false;
    }


    /**
     * Save new user register in a database - returns true on success
     *
     * @param $user_name
     * @param $user_email
     * @param $user_password_hash
     * @param $user_activation_hash
     * @param $user_creation_timestamp
     * @return bool
     */
    public function insertNewUserInDatabase($user_name, $user_email, $user_password_hash, $user_activation_hash, $user_creation_timestamp)
    {
        $query = $this->db->prepare("
            INSERT INTO users
            (user_name, user_email, user_password_hash, user_activation_hash, user_creation_timestamp)
            VALUES (:user_name, :user_email, :user_password_hash, :user_activation_hash, :user_creation_timestamp)");

        $query->execute(array(
            ':user_name' => $user_name,
            ':user_email' => $user_email,
            ':user_password_hash' => $user_password_hash,
            ':user_activation_hash' => $user_activation_hash,
            ':user_creation_timestamp' => $user_creation_timestamp
        ));
        // rowCount() always return an integer.
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }


    /**
     * Delete registration by user id -
     *
     * @param $user_id
     */
    public function rollbackRegisterByUserId($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

    }


    /**
     * Validate username - returns true on success and false if wrong adding an error message
     *
     * @param $user_name
     * @return bool
     */
    public function validateUsername($user_name)
    {
        if (empty($user_name) AND mb_strlen($user_name) < 20) {
            Session::add('feedback_negative', Message::get('VALIDATION_USERNAME_EMPTY_OR_EXCEED_THAN_20_CHARACTERS'));
            return false;
        }
        if ($this->checkIfUsernameAlreadyExist($user_name)) {
            Session::add('feedback_negative', Message::get('VALIDATION_USERNAME_ALREADY_REGISTERED'));
            return false;
        }
        return true;
    }


    /**
     * Validates user email - return true on success
     *
     * @param $user_email
     * @return bool
     */
    public function validateUserEmail($user_email)
    {
        if (empty($user_email)) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_EMPTY'));
            return false;
        }
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_INVALID'));
            return false;
        }
        if (!$this->checkIfUserEmailAlreadyExists($user_email)) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_ALREADY_REGISTERED'));
            return false;
        }
        return true;
    }


    /**
     * Send mail to user to activate account
     *
     * urlencode() -  This function is convenient when encoding a string to be used in
     * a query part of a URL, as a convenient way to pass variables to the next page.
     */
    public static function sendEmailVerification($user_id, $user_email, $user_activation_hash) {

        $body = Config::get('EMAIL_FROM_CONTENT') . Config::get('BASE_URL') .
            Config::get('EMAIL_VERIFICATION_PATH_URL') . '/' . urldecode($user_id) . '/' . urldecode($user_activation_hash);

        $mail = new Mail;
        $mail_sent = $mail->sendMailPhpMailer($user_email, Config::get('EMAIL_FROM_EMAIL'),
            Config::get('EMAIL_FROM_NAME'), Config::get('EMAIL_FROM_SUBJECT'), $body);

        if ($mail_sent) {
           Session::add('feedback_positive',Message::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            return true;
        } else {
            Session::add('feedback_negative',Message::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->error);
            return false;
        }
    }


    /**
     * Validates user password - return true on success
     *
     * @param $user_password
     * @param $user_password_again
     * @return bool
     */
    public function validateUserPassword($user_password, $user_password_again)
    {
        if (empty($user_password) OR empty($user_password_again)) {
            Session::add('feedback_negative', Message::get('VALIDATION_PASSWORD_EMPTY_OR_PASSWORD_AGAIN_EMPTY'));
            return false;
        }
        if ($user_password !== $user_password_again) {
            Session::add('feedback_negative', Message::get('VALIDATION_PASSWORD_DOES_NOT_MATCH_WITH_PASSWORD_AGAIN'));
            return false;
        }
        // $user_password = filter_input(INPUT_POST, 'password');
        if (mb_strlen($user_password) < 6) {
            Session::add('feedback_negative', Message::get('VALIDATION_PASSWORD_LESS_THAN_6_CHARACTERS'));
            return false;
        }
        return true;
    }


    /**
     *  Check if username given is already in database - return false on success
     *
     *  @param $user_name
     *  @return bool
     */
    public function checkIfUsernameAlreadyExist($user_name)
    {
        $query = $this->db->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
        $query->execute(array(':user_name' => $user_name));
        if($query->rowCount() == 1) {
            return true;
        }
        // no duplicate has been found
        return false;
    }


    /**
     * Check for duplicate entry in a database
     *
     * @param $user_email
     * @return bool
     */
    public function checkIfUserEmailAlreadyExists($user_email)
    {
        $query = $this->db->prepare("SELECT user_email FROM users WHERE user_email = :user_email LIMIT 1");
        $query->execute(array(':user_email' => $user_email));
        // check for result, if match result return false
        if($query->rowCount() == 1) {
            return false;
        }
        // if it is ok..
        return true;
    }


    /**
     * Get user id by username in database
     *
     * @param $user_name
     * @return mixed
     */
    public function getUserIdByUsername($user_name)
    {
        $query = "SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1";
        $sth = $this->db->prepare($query);
        $sth->execute(array(':user_name' => $user_name));
        // return one row only (user_id)
        $result = $sth->fetch();
        $user_id = $result['user_id'];
        return $user_id;

    }

} // End of class


