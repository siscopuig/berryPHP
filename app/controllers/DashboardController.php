<?php

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{

	function __construct()
    {
		parent::__construct();
        {
            Auth::checkAuthentication();
        }
		// This is the path to the dashboard/default javascript file
		$this->view->js = array('dashboard/js/default.js');
	}


    /**
     * Show dashboard/index by default
     */
    public function index()
	{	
		$this->view->render('dashboard/index');
	}


    /**
     * Save 'text' in database, data returned from model is render in JSON
     */
    public function xhrInsert()
	{
        if (!empty($_POST['text'])) {
            $data = $this->{'model'}->xhrInsert(Sanitize::post('text', true));
            if ($data) {
                $this->view->renderJSON($data);
            }
        }
	}


    /**
     * display a list of records rendered in JSON
     */
    public function xhrGetListings()
	{
        $data = $this->{'model'}->xhrGetListings();
        if ($data) {
            $this->view->renderJSON($data);
        } else {
            $this->view->render('dashboard/index');
        }
	}


    /**
     * Delete last entry list by id given
     */
    public function xhrDeleteListing()
	{
		$this->{'model'}->xhrDeleteListing(Sanitize::post('id', true));
	}


    /**
     *  destroy session when logout
     */
    public function logout()
    {
        Redirect::baseUrl();
        Session::destroy();
    }
}