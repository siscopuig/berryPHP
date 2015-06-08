<?php


/**
 * Class View -
 *
 * expect changes!
 */
class View
{

	function __construct()
	{

	}


    /**
     *  Display header and footer by default - if we want to render something else we pass another
     *  parameter $noInclude inside the function with a false value.
     *
     *  @param $name
     *  @param null $parameters
     */
    public function render($name, $parameters = null)
	{
		if ($parameters) {
            foreach ($parameters as $key => $value) {
                $this->{$key} = $value;
            }
		}

        // Include automatically the header and footer in our views pages
        require Config::get('VIEW_PATH') . 'header.php';
        require Config::get('VIEW_PATH') . $name . '.php';
        require Config::get('VIEW_PATH') . 'footer.php';
	}


    /**
     *  Show messages added, unset messages as soon they are displayed
     */
    public function renderMessages()
    {
        require Config::get('VIEW_PATH') . 'templates/templates.php';
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }


    /**
     *  Render JSON
     *
     * @param $data
     */
    public function renderJSON($data)
    {
        echo json_encode($data);
    }

}