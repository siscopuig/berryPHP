<?php

class ProfileController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}


    /**
     * Show index profile page by default
     */
    public function index()
	{
        Auth::checkAuthentication();
        $this->view->render('profile/index', array(
            'user' => $this->{'model'}->displayUserProfile(Session::get('user_id'))));
	}


    /**
     * Upload a profile picture and perform the action
     *
     */
    public function uploadProfilePictureAction()
    {
        // quick check if there is an error
        if (Sanitize::checkErrorUploadFile()) {
            if ($this->{'model'}->uploadAndSaveProfilePicture()) {
                Redirect::to('profile/index');
            } else {
                $this->view->render('profile/index');
            }
        } else {
            // apparently work well when redirect...
            Redirect::to('profile/index');
        }
    }
} // End of class Profile.
