<?php

class UserController extends BaseController
{
    private $userModel;

    protected function onInitialize()
    {
        $this->title = 'User';
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $this->viewBag['controllerName'] = $this->controller;
        $this->renderView();
    }

    public function register()
    {
        if ($this->isPost()) {
            $username = $_POST['username'];
            $fullName = $_POST['fullName'];
            $password = $_POST['password'];

            try {
                $result = $this->userModel->register($username, $password, $fullName);
                if ($result) {
                    $this->viewBag['username'] = $result;
                    $this->renderView('registerSuccess');
                    die;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }


    public function login()
    {
        if ($this->isPost()) {
            $username = $_POST['username'];

            if ($this->isLoggedIn() && $username == $_SESSION['username']) {
                echo 'Username already logged in';
                return;
            }

            $password = $_POST['password'];

            try {
                $result = $this->userModel->login($username, $password);
                if ($result) {
                    $this->viewBag['username'] = $result;
                    $this->renderView('loginSuccess');
                    $_SESSION['username'] = $result;
                    die;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}