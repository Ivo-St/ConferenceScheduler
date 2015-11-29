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
            $username = $this->request->form->username;
            $fullName = $this->request->form->fullName;
            $password = $this->request->form->password;

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

    /**
     * @test
     */
    public function login()
    {
        if ($this->isPost()) {
            $username = $this->request->form->username;
            if ($this->user->isLoggedIn() && $username == $this->user->userName) {
                echo 'Username already logged in';
                return;
            }

            $password = $this->request->form->password;

            try {
                $result = $this->userModel->login($username, $password);
                if ($result) {
                    $this->viewBag['username'] = $result;
                    $this->renderView('loginSuccess');
                    die;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}