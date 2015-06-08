<?php


class HelpController extends Controller
{
    function __construct()
    {
        parent:: __construct();
    }

    function index()
    {
        $this->view->render('help/index');
    }


    public function other($arg = false)
    {
        //echo 'Function other() inside Class Help:' . '<br>';
        //echo 'OPTIONAL:  ' . $arg;

        require 'models/HelpModel.php';
        $model = new HelpModel();

        // this is for call function blah() in help_model class
        $this->view->blah = $model->blah();
    }
}