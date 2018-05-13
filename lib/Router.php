<?php

namespace lib;

class Router
{
    protected $route;
    protected $lang;
    protected $controller;
    protected $action;
    protected $area;
    protected $params = [];

//    private $defaults; ?????????

    /**
     * Set up default values
     */
    private function setUpDefaults()
    {
//        $this->defaults = Config::get('defaults'); ????????????????
        $defaults = Config::get('defaults');

//        foreach ($this->defaults as $key => $default) { ????????????
        foreach ($defaults as $key => $default) {
            $this->$key = $default;
        }
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getActionWithArea()
    {
        return $this->area . $this->action;
    }

    /**
     * @param $uri
     */
    public function parseUrl($uri)
    {
        $this->setUpDefaults();

        $routes = Config::get('routes');

        $uri = urldecode(trim($uri, '/'));

        $path_parts = explode('/', $uri);

        if (!empty($path_parts)) {
            $part = strtolower(array_shift($path_parts));
            // Get route
            if (in_array($part, array_keys($routes))) {
                $this->route = $part;

                $part = strtolower(array_shift($path_parts));
            }
            // Get language
            if ($part && in_array($part, Config::get('lang'))) {
                $this->lang = $part;

                $part = strtolower(array_shift($path_parts));
            }
            // Get controller
            if ($part) {
                $this->controller = $part;

                $part = strtolower(array_shift($path_parts));
            }
            // Get action
            if ($part) {
                $this->action = $part;
            }
            // Get params
            $this->params = $path_parts;

            $this->area = isset($routes[$this->route]) ? $routes[$this->route] : '';
        }
    }

    /**
     * @param $url
     * @param int $code
     */
    public static function redirect($url, $code = 302)
    {
        header("Location: " . $url, true, $code);
        exit();
    }
}