<?php

// If I encapsulate the nodes inside curly braces as strings, PHPStorm will ignore these.

class UserController extends Controller
{

	public function __construct() 
	{
		parent::__construct();

	}

    /**
     * Show users data in a list
     *
     */
    public function index()
	{
        Auth::checkAuthentication();
        $this->view->render('user/index', array(
            'users' => $this->{'model'}->showUsersData()
        ));
    }


    /**
     * @param $user_id
     */
    public function ShowEditUserRole($user_id)
    {
        $user_data = $this->{'model'}->userSingleList($user_id);
        if ($user_data) {
            $this->view->render('user/edit', array('user' => $user_data));
        } else {
            $this->view->render('user/index');
        }
    }


    /**
     * @todo
     */
    public function changeUserRole($user_id)
    {
        if ($this->{'model'}->changeUserRole(
                                        $user_id,
                                        Sanitize::post('user_name', true),
                                        $user_account_role = $_POST['user_account_role'])) {
            Session::add('feedback_positive', Message::get(''));
            Redirect::to('user/index');
        } else {
            Session::add('feedback_negative', Message::get(''));
        }
    }



    /**
     *  Edit username from profile
     *
     *
     */
    public function editUsernameAction()
    {
        if($this->{'model'}->editNewUsername(Session::get('user_id'), Sanitize::post('user_name'))) {
            Redirect::to('profile/index');
        } else {
            Redirect::to('profile/index');
        }
    }


    /**
     * Edit user password from profile
     *
     */
    public function editUserPasswordAction()
    {
        if ($this->{'model'}->editUserPassword(
                                    Session::get('user_id'),
                                    Sanitize::post('user_password', true),
                                    Sanitize::post('user_password_again', true))) {

            Redirect::to('profile/index');
        } else {
            Redirect::to('profile/index');
        }
    }


    /**
     * Update new user
     *
     */
    public function updateUserEmail()
    {
        if ($this->{'model'}->editUserEmail(
                                            Session::get('user_id'),
                                            Sanitize::post('user_email', true),
                                            Sanitize::post('user_email_again', true))) {
            Redirect::to('profile/index');
        } else {
            Redirect::to('profile/index');
        }
    }


    /**
     * Delete user from database by id
     *
     * @param $user_id
     */
    public function deleteUser($user_id)
	{
		$this->{'model'}->delete($user_id);
        Redirect::to('user');
    }
	
	

}