<?php
ini_set('display_errors', 1);
session_start();

include_once('config.php');

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestParts = explode('/', $requestPath);

$controllerName = DEFAULT_CONTROLLER;
$controllerAction = DEFAULT_ACTION;
$controllerParams = [];
if (count($requestParts) >= DEFAULT_PATH_PREFIX + 1 && $requestParts[DEFAULT_PATH_PREFIX] != '') {
    $controllerName = $requestParts[DEFAULT_PATH_PREFIX];
    if (count($requestParts) >= DEFAULT_PATH_PREFIX + 2 && $requestParts[DEFAULT_PATH_PREFIX + 1] != '') {
        $controllerAction = $requestParts[DEFAULT_PATH_PREFIX + 1];
        if (count($requestParts) >= DEFAULT_PATH_PREFIX + 3) {
            $controllerParams = array_slice($requestParts, DEFAULT_PATH_PREFIX + 2);
        }
    }
}

$controllerClassName = ucfirst($controllerName) . DEFAULT_CONTROLLER_SUFFIX;
if (class_exists($controllerClassName)) {
    $controller = new $controllerClassName($controllerName, $controllerAction);
    if (method_exists($controller, $controllerAction)) {
        call_user_func_array(array($controller, $controllerAction), $controllerParams);
        $controller->renderView();
    } else {
        die("Cannot find action \"$controllerAction\" in controller \"$controllerName\"");
    }
} else {
    die("Cannot find controller \"$controllerClassName.php\"");
}

function __autoload($class_name)
{
    if (file_exists("controllers/$class_name.php")) {
        include "controllers/$class_name.php";
    }
    if (file_exists("models/$class_name.php")) {
        include "models/$class_name.php";
    }
}