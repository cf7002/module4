<?php

namespace lib;

use Exception;
use models\User;

class App
{
    /**
     * @param $params
     *
     * @return string
     *
     * @throws \Exception
     */
    public function run($params)
    {
        $request = new Request($_GET, $_POST, $_FILES, $_SERVER);

//        var_dump($request);
        $router = new Router();
        $router->parseUrl($params);

        LangFiles::getInstance()->load($router->getLang());

        /** @var Controller $controller_name */
        $controller_name = (new ControllerFactory())->createController($router);

        if ($router->getRoute() === 'admin' && !User::isAdmin()) {
            $controller_name->redirect('/user/login');
        }

        $controller_method = $router->getActionWithArea() . 'Action';
        if (!method_exists($controller_name, $controller_method)) {
            throw new Exception('Method ' . $controller_method . ' of class: ' . $controller_name . 'does not exist.');
        }

//        $controller_name->setParams($router->getParams()); ???????????
        $controller_name->setRouter($router);

        return $controller_name->$controller_method($request);
    }
}
