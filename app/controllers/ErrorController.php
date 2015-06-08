<?php
	
/*
The extended or derived class has all variables and functions of the base
class this is called 'inheritance'. 	
*/
	
class ErrorController extends Controller
{
	function __construct()
    {
		parent::__construct();
	}
	
	
	
	function index()
	{	
		// We call view then msg and assign a value to this message:
		$this->view->msg = 'This page does not exist';
		$this->view->render('error/index');
	}
}