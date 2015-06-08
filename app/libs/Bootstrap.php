<?php


class Bootstrap {

    private $url = null;
    private $controller = null;
    private $controller_error_file = 'ErrorController.php';
    public $controller_name;
    public $controller_method;
    public $controller_path = 'app/controllers/';
    public $controller_file_default = 'IndexController';

    /**
     * Starts the Bootstrap
     *
     * @return boolean
     */
    public function init()
    {
        $this->getUrl();

        // if no controller in the URL means is equal to empty
        if (empty($this->url[0]))
        {
            // load default controller
            $this->loadDefaultController();
            return false;
        }


        $this->formatUrlControllerNameToOriginal($this->url[0]);

        $this->loadExistingController();
        $this->callControllerMethod();

        return false;
    }

    /**
     *  fetches the $_GET from 'url'
     */
    private function getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $this->url = explode('/', $url);
    }


    /**
     *  load default controller file set ($controller_file_default)
     */
    private function loadDefaultController()
    {
        // grab full path for the default controller, eg: 'controllers/IndexController.php'
        require Config::get('CONTROLLER_PATH') . $this->controller_file_default . '.php';

        // instantiate class of default file
        $this->controller = new IndexController();

        // call method index inside default controller class
        $this->controller->index();
    }


    /**
     *  format first letter of controller name, eg: 'index' -> 'Index' and add 'Controller'
     *  string at the end. eg: 'IndexController'
     *
     *  @param $controller
     *  @return string
     */
    private function formatUrlControllerNameToOriginal($controller)
    {
        return $this->controller_name = ucwords($controller) . 'Controller';
    }



    /**
     *  (Optional) Set a custom path to controllers
     *  @param string $path
     */
    public function setControllerPath($path)
    {
        $this->_controllerPath = trim($path, '/') . '/';
    }

    /**
     * (Optional) Set a custom path to models
     * @param string $path
     */
    public function setModelPath($path)
    {
        $this->model_path = trim($path, '/') . '/';
    }

    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: error.php
     */
    public function setErrorFile($path)
    {
        $this->_errorFile = trim($path, '/');
    }

    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: index.php
     */
    public function setDefaultFile($path)
    {
        $this->_defaultFile = trim($path, '/');
    }



    /**
     * Load an existing controller if there IS a GET parameter passed
     *
     * @return boolean|string
     */
    private function loadExistingController()
    {
        $file = Config::get('CONTROLLER_PATH') . $this->controller_name . '.php';

        if (file_exists($file)) {
            require $file;

            $this->controller = new $this->controller_name;

            $this->controller->{'loadModel'}($this->controller_name);

        } else {
            $this->error();
            return false;
        }
    }

    /**
     * If a method is passed in the GET url parameter
     *
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
     */
    private function callControllerMethod()
    {
        $length = count($this->url);

        // Make sure the method we are calling exists
        if ($length > 1) {
            if (!method_exists($this->controller, $this->url[1])) {
                $this->{'error'}();
            }
        }

        // Determine what to load
        switch ($length) {
            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->controller->{$this->url[1]}($this->url[2], $this->url[3], $this->url[4]);
                break;

            case 4:
                //Controller->Method(Param1, Param2)
                $this->controller->{$this->url[1]}($this->url[2], $this->url[3]);
                break;

            case 3:
                //Controller->Method(Param1, Param2)
                $this->controller->{$this->url[1]}($this->url[2]);
                break;

            case 2:
                //Controller->Method(Param1, Param2)
                $this->controller->{$this->url[1]}();
                break;

            default:
                $this->controller->{'index'}();
                break;
        }
    }

    /**
     * Display an error page if nothing exists
     *
     * @return boolean
     */
    private function error() {
        require Config::get('CONTROLLER_PATH') . $this->controller_error_file;
        $this->controller = new ErrorController();
        $this->controller->index();
        exit;
    }

}