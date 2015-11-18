<?php

require_once 'views/ViewManager.php';

class BaseController
{
    protected $controller;
    protected $action;
    protected $layout = DEFAULT_LAYOUT;
    protected $viewBag = [];

    public function __construct($controller, $action)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->onInitialize();
    }

    protected function onInitialize()
    {
    }

    public function __get($name)
    {
        if (isset($this->viewBag[$name])) {
            return $this->viewBag[$name];
        }

        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->viewBag[$name] = $value;
    }

    public function index()
    {
        $this->renderView();
    }

    public function renderView($viewName = null, $isPartial = false)
    {
        if ($viewName === null) {
            $viewName = $this->action;
        }

        if (!$isPartial) {
            $headerFile = 'views/layouts/' . $this->layout . '/header.php';
            $headerView = ViewManager::render($headerFile, $this->viewBag);
            echo $headerView;
        }

        $viewFile = 'views/' . $this->controller . '/' . $viewName . '.php';
        $view = ViewManager::render($viewFile, $this->viewBag);
        echo $view;

        if (!$isPartial) {
            $footerFile = 'views/layouts/' . $this->layout . '/footer.php';
            $footerView = ViewManager::render($footerFile, $this->viewBag);
            echo $footerView;
        }
    }

    protected function redirectToURL($url)
    {
        header("Location: $url");
        die;
    }

    public function redirect($controller = DEFAULT_CONTROLLER, $action = DEFAULT_ACTION, $params = [])
    {
        $url = "$controller/$action";
        $urlEncodedParams = array_map('urlencode', $params);
        $urlParams = implode('/', $urlEncodedParams);
        $url .= '/' . $urlParams;
        $this->redirectToURL($url);
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['username']);
    }
}