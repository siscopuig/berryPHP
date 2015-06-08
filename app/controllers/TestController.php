<?php


class TestController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function Index()
    {
        $this->view->render('test/index');
    }




}