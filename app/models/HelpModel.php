<?php


class HelpModel extends Model
{
    function __construct()
    {

    }

    function index()
    {
        // This is for debugging
        echo 'this come from class Help_Model extends Model'. '<br>';


        // We call view object inside the class Controller
        $this->view->render('index/index');


        // If we don't want to include the header and footer we could pass another argument
        // in our function. We setup this in libs/View class.
        // $this->view->render('index/index', 1);

    }

}