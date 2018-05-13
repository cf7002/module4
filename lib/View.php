<?php
namespace lib;

use Exception;

class View
{
    /* @var $router Router */
    private $router;

    /**
     * View constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getViewTemplate($name = null)
    {
        $tpl_name = VIEW_DIR . $this->router->getController() . DIRECTORY_SEPARATOR;
        $tpl_name .= empty($name) ? $this->router->getActionWithArea() : $name;

        return "$tpl_name.php";
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getView($name)
    {
        $tpl_name = VIEW_DIR . $this->router->getController() . DIRECTORY_SEPARATOR;
        $tpl_name .= empty($name) ? $this->router->getActionWithArea() : $name;

        return "$tpl_name.php";
    }


    /**
     * @param string $name
     *
     * @return string
     */
    public function getLayout($name = null)
    {
        $layout_name = VIEW_DIR . 'layouts' . DIRECTORY_SEPARATOR;
        $layout_name .= empty($name) ? $this->router->getRoute() : $name;

        return "$layout_name.php";
    }

    /**
     * @param string $file
     * @param array $data
     *
     * @return string
     *
     * @throws Exception
     */
    public function render($file, $data = [])
    {
        if (!file_exists($file))
            throw new Exception("View file {$file} is not found.");

        ob_start();
        include $file;

        return ob_get_clean();
    }
}
