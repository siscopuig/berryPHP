<?php

class Controller
{
    public $model_filename;

	function __construct()
	{
        // initialize a session that we can use across the application
        Session::init();

        // create an object of class View
		$this->view = new View();
	}

    /**
     * @param $name
     * @param string $model_path
     */
    public function loadModel($name)
    {
        $this->setModelFileName($name);

        // IndexController
        // @todo create a specific path in config
        $path = Config::get('MODEL_PATH') . $this->model_filename .'Model.php';

        if (file_exists($path)) {
            // load model file
            require $path;

            // model name class
            $model_name = $this->model_filename . 'Model';

            $this->model = new $model_name();
        }
    }

    /**
     *  split controller name to get model name
     *  eg: IndexController => Index
     *
     *  @param $name
     *  @return mixed
     */
    public function setModelFileName($name)
    {
        $split_model_name = explode('Controller', $name);
        return $this->model_filename = $split_model_name[0];
    }





}