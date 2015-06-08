<?php
	
	
	
class RegisterController extends Controller
{
	public function __construct()
    {
		parent::__construct();
	}


    /**
     * Index - show index register page, default action
     */
    public function index()
	{
        if ($this->{'model'}->IsUserLoggedIn()) {
            Redirect::baseUrl();
        }
        else {
            $this->view->render('register/index');
        }
	}

    /**
     * Register new user - when a new user submit a register form
     */
    public function registerNewUserAction()
    {
        if ($this->{'model'}->registerNewUserAction(
                Sanitize::post('user_name', true),
                Sanitize::post('user_email', true),
                Sanitize::post('user_password', true),
                Sanitize::post('user_password_again', true))) {

            // redirect if register success to login
            Redirect::to('login/index');
        } else {
            Redirect::to('register/index');
        }
    }
}
