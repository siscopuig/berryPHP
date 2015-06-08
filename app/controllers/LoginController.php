<?php
	
	
	
	
class LoginController extends Controller
{
	function __construct()
    {
		parent::__construct();
	}


    /**
     * Show index login page and check if user is logged in and redirect to profile/index,
     * if not show form again.
     */
    function index()
	{
        if($this->{'model'}->IsUserLoggedIn()) {
            Redirect::to('profile/index');
        }
        else {
            $this->view->render('login/index');
        }
	}

    /**
     *  Login action - submit the form, if correct redirect to profile/index, if not show login form
     */
    public function loginAction()
    {
        if ($this->{'model'}->loginAction(Sanitize::post('user_name', true), Sanitize::post('user_password', true))) {
            Redirect::to('profile/index');
        }
        else {
            $this->view->render('login/index');
        }
    }


    /**
     * Verify when user click a link previously sent to activate an account - go to model and update data in
     * database
     *
     * @param $user_id
     * @param $user_activation_verification_code
     */
    public function verifyUserActivationVerificationCode($user_id, $user_activation_verification_code)
    {
        if (isset($user_id) AND isset($user_activation_verification_code)) {
            $this->{'model'}->verifyUserActivationVerificationCode($user_id, $user_activation_verification_code);
            Redirect::to('login/index');
        } else {
            Redirect::to('login/index');
        }
    }


    /**
     * requestNewUsernameAction
     *
     * display page form to request username
     */
    public function showFormRequestUsername()
    {
        $this->view->render('login/formRequestUsername');
    }


    /**
     * Receive an input data (user_email) formRequestUsername and call model to process this data
     * redirect to login/index on success or show same page if failed
     *
     */
    public function requestUsernameByEmailAction()
    {
        if ($this->{'model'}->requestUsernameByEmail(Sanitize::post('user_email', true))) {
            Redirect::to('login/index');
        } else {
            Redirect::to('login/showFormRequestUsername');
        }
    }


    /**
     * Show form to input an user email
     *
     */
    public function showFormRequestPassword()
    {
        $this->view->render('login/formRequestPassword');
    }


    /**
     *  Reset user password
     */
    public function resetUserPassword()
    {
        if ($this->{'model'}->resetUserPassword(Sanitize::post('user_email', true))) {
            Redirect::to('login/index');
        } else {
            Redirect::to('login/showFormRequestPassword');
        }
    }


    /**
     * Verify token sent to user by clicking a link
     *
     * @param $user_name
     * @param $user_password_reset_code
     */
    public function verifyResetPassword($user_name, $user_password_reset_code)
    {
        if ($this->{'model'}->verifyResetPassword($user_name, $user_password_reset_code)) {
            $this->view->render('login/formSetNewPassword', array(
                'user_name' => $user_name,
                'user_password_reset_hash' => $user_password_reset_code));
        } else {
            Redirect::to('login/index');
        }
    }


    /**
     * setNewUserPassword
     *
     *
     */
    public function setNewUserPassword()
    {
        $user_name = $_POST['user_name'];
        $user_password_reset_hash = $_POST['user_password_reset_hash'];
        if ($this->{'model'}->setNewUserPassword(
                            Sanitize::post('user_name', true),
                            Sanitize::post('user_password_reset_hash', true),
                            Sanitize::post('user_password', true),
                            Sanitize::post('user_password_again', true))) {
            // redirect to..
            Redirect::to('login/index');
        } else {
            $this->view->render('login/formSetNewPassword', array(
                'user_name' => $user_name,
                'user_password_reset_hash' => $user_password_reset_hash));
        }
    }


    /**
     *  logout, redirect user to URL root
     */
    public function logout()
    {
        LoginModel::logout();
        Redirect::baseUrl();
    }

}
