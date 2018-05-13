<?php

namespace lib;

class ControllerFactory
{
    /**
     * @param Router $router
     *
     * @return mixed
     */
    public function createController(Router $router)
    {
        $controller_name = ucfirst($router->getController()) . 'Controller';
        $controller_name = "controllers\\$controller_name";

        return new $controller_name();
    }
}