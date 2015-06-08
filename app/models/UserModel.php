<?php
	

class UserModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}


    /**
     *  check if username already exist in database
     *
     *
     *  @param $user_name
     *  @return bool
     */
    public function checkIfUsernameAlreadyExists($user_name)
    {
        $query = $this->db->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
        $query->execute(array(':user_name' => $user_name));
        if($query->rowCount() == 1) {
            return true;
        }
        return false;
    }


    public function editNewUsername($user_id, $user_name)
    {
        if (!$this->validateNewUsername($user_name)) {
            return false;
        }
        if (!$this->saveUsernameInDatabase($user_id, $user_name)) {
            Session::add('feedback_negative', Message::get('MESSAGE_UNKNOWN_ERROR_DATABASE'));
            return false;
        }
        // if validate and save in database correctly...Set new username in Session
        Session::add('feedback_positive', Message::get('MESSAGE_SUCCESSFULLY_UPDATED'));
        Session::set('user_name', $user_name);
        return true;
    }



    /**
     *  validate username
     *
     *
     *  @param $user_name
     *  @return bool
     */
    public function validateNewUsername($user_name)
    {
        if (empty($user_name) AND strlen($user_name) < 20) {
            Session::add('feedback_negative', Message::get('VALIDATION_USERNAME_EMPTY_OR_EXCEED_THAN_20_CHARACTERS'));
            return false;
        }
        if ($user_name == Session::get('user_name')) {
            Session::add('feedback_negative', Message::get('VALIDATION_USERNAME_SAME_AS_OLD_ONE'));
            return false;
        }
        if ($this->checkIfUsernameAlreadyExists($user_name)) {
            Session::add('feedback_negative', Message::get('VALIDATION_USERNAME_ALREADY_REGISTERED'));
            return false;
        }
        return true;
    }

    /**
     *  Validate user email
     *
     *  @param $user_email
     *  @param null $user_email_again
     *  @return bool
     */
    public function validateNewUserEmail($user_email, $user_email_again)
    {
        if (empty($user_email) || empty($user_email_again)) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_EMPTY_OR_EMAIL_AGAIN_EMPTY'));
            return false;
        }

        // if new email is identical than old one return false
        if ($user_email == Session::get('user_email')) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_SAME_AS_OLD_ONE'));
            return false;
        }

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_INVALID'));
            return false;
        }

        if (!$this->checkIfEmailAlreadyExists($user_email)) {
            Session::add('feedback_negative', Message::get('VALIDATION_EMAIL_ALREADY_REGISTERED'));
            return false;
        }
        return true;
    }


    /**
     *  Save username in Database
     *
     *  @param $user_id
     *  @param $user_name
     *  @return bool
     */
    public function saveUsernameInDatabase($user_id, $user_name)
    {
        $sql = "UPDATE users SET user_name = :user_name WHERE user_id = :user_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id, ':user_name' => $user_name));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }


    /**
     * Edit new user password
     *
     * @param $user_id
     * @param $user_password
     * @param $user_password_again
     * @return bool
     */
    public function editUserPassword($user_id, $user_password, $user_password_again)
    {
        if (!$this->validateNewUserPassword($user_password, $user_password_again)) {
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

        if (!$this->saveNewUserPasswordInDatabase($user_id, $user_password_hash)) {
            Session::add('feedback_negative', Message::get('EDIT_USER_NEW_PASSWORD_ERROR_DATABASE'));
            return false;
        }
        // all cool!
        Session::add('feedback_positive', Message::get('EDIT_USER_NEW_PASSWORD_SUCCESSFUL'));
        return true;

    }

    /**
     *  Validate new user password
     *
     *  @param $user_password
     *  @param $user_password_again
     *  @return bool
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
     * Save new user password hash in database - return true on success
     *
     * @param $user_id
     * @param $user_password_hash
     * @return bool
     */
    public function saveNewUserPasswordInDatabase($user_id, $user_password_hash)
    {
        $sql = "UPDATE users SET user_password_hash = :user_password_hash
                WHERE user_id = :user_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_password_hash' => $user_password_hash, ':user_id' => $user_id));
        // if result exist return true, otherwise return false
        return ($query->rowCount() == 1 ? true : false);
    }


    /**
     *  Edit new user email
     *
     *  @param $user_id
     *  @param $user_email
     *  @param $user_email_again
     *  @return bool
     */
    public function editUserEmail($user_id, $user_email, $user_email_again)
    {
        if (!$this->validateNewUserEmail($user_email, $user_email_again)) {
            return false;
        }
        if (!$this->saveNewEmailInDatabase($user_id, $user_email)) {
            Session::add('feedback_negative', Message::get('EDIT_USER_NEW_EMAIL_ERROR_DATABASE'));
            return false;
        }
        // everything is correct...Set new email in Session and return true on success
        Session::add('feedback_positive', Message::get('EDIT_USER_NEW_EMAIL_SAVED_SUCCESSFUL'));
        Session::set('user_email', $user_email);
        return true;
    }


    /**
     *  Save user new email in a database - return true on success
     *
     *  @param $user_id
     *  @param $user_email
     *  @return bool
     */
    public function saveNewEmailInDatabase($user_id, $user_email)
    {
        $sql = "UPDATE users SET user_email = :user_email WHERE user_id = :user_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_email' => $user_email, ':user_id' => $user_id));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }


    /**
     * Check if an email already exist in a database - return true on success
     *
     * @param $user_email
     * @return bool
     */
    public function checkIfEmailAlreadyExists($user_email)
    {
        $sql = "SELECT user_email FROM users WHERE user_email = :user_email LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_email' => $user_email));
        if($query->rowCount() == 1) {
            return false;
        }
        // if it's ok..
        return true;
    }


    /**
     * Get username by id - return user id
     *
     * @param $user_name
     * @return mixed
     */
    public function getUserIdByUsername($user_name)
    {
        $sql = "SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_name' => $user_name));
        // return one row only (user_id)
        return $query->fetch();
    }


    /**
     * Retrieve information from all users to display in user/index
     *
     * @return array
     */
    public function showUsersData()
    {
        $query = $this->db->query("SELECT user_id, user_name, user_account_role,user_has_picture, user_filename
                                   FROM users");
        $result = $query->fetchAll();
        return $result;
    }


    /**
     *
     * fetch(); only one column is being returned or are only interested in the first column returned
     * @param $user_id
     * @return mixed
     */
    public function userSingleList($user_id)
    {
        $sql = "SELECT user_id, user_name, user_account_role, user_filename, user_has_picture
                FROM users WHERE user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

        if ($query->rowCount() ==1) {
            return $result = $query->fetch();
        } else {
            return false;
        }


    }


    /**
     *
     *
     * @param $user_id
     * @param $user_name
     * @param $user_account_role
     * @return bool
     */
    public function changeUserRole($user_id, $user_name, $user_account_role)
    {
        $sql = "UPDATE users
                SET user_name = :user_name, user_account_role = :user_account_role
                WHERE user_id = :user_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':user_id' => $user_id,
            ':user_name' => $user_name,
            ':user_account_role' => $user_account_role));
        if ($query->rowCount() == 1) {
            //set new user account role in session
            Session::set('user_account_role', $user_account_role);
            return true;
        } else {
            return false;
        }


    }

    /**
     * @param $user_id
     * @return bool
     */
    public function delete($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $user_id));
        if ($query->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

}