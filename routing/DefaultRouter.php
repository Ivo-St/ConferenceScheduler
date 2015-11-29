<?php

include_once('config.php');
include('IRouter.php');

class DefaultRouter implements IRouter
{
    const REQUEST_URI_CONTROLLER_INDEX = 0;
    const REQUEST_URI_ACTION_INDEX = 1;

    function getController()
    {
        $uri = explode('/', strtok($_SERVER["REQUEST_URI"],'?'));

        $controller = $uri[DEFAULT_PATH_PREFIX + self::REQUEST_URI_CONTROLLER_INDEX];

        if (empty($controller)) {
            return DEFAULT_CONTROLLER;
        }

        return $controller;
    }

    function getAction()
    {
        $uri = explode('/', strtok($_SERVER["REQUEST_URI"],'?'));

        $action = $uri[DEFAULT_PATH_PREFIX + self::REQUEST_URI_ACTION_INDEX];

        if (empty($action)) {
            return DEFAULT_ACTION;
        }

        return $action;
    }

    function getParams()
    {
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        $controllerParams = array_slice($uri, DEFAULT_PATH_PREFIX + self::REQUEST_URI_ACTION_INDEX);

        return $controllerParams;
    }
}