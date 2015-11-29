<?php
ini_set('display_errors', 1);
session_start();

include_once('config.php');
include_once 'AnnotationsParser.php';
include 'routing/DefaultRouter.php';

spl_autoload_register('autoloadController');

$router = new DefaultRouter();
$annotationsParser = new AnnotationsParser();

$controllerName = $router->getController();
$controllerAction = $router->getAction();
$controllerParams = $router->getParams();

$controllerClassName = ucfirst($controllerName) . DEFAULT_CONTROLLER_SUFFIX;

if (class_exists($controllerClassName)) {
    $annotationsParser->parseClassAnnotations($controllerClassName);
    $controller = new $controllerClassName(lcfirst($controllerName), lcfirst($controllerAction));
    if (method_exists($controller, $controllerAction)) {
        $annotationsParser->parseActionAnnotations($controllerClassName, $controllerAction);
        call_user_func_array(array($controller, $controllerAction), $controllerParams);
        $controller->renderView();
    } else {
        die("Cannot find action \"$controllerAction\" in controller \"$controllerName\"");
    }
} else {
    die("Cannot find controller \"$controllerClassName.php\"");
}

function autoloadController($class_name)
{
    if (file_exists("controllers/$class_name.php")) {
        include "controllers/$class_name.php";
    }
    if (file_exists("models/$class_name.php")) {
        include "models/$class_name.php";
    }
}