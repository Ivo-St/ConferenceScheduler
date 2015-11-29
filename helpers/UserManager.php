<?php

include_once 'http context/Session.php';

class UserManager
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function __get($name)
    {
        if (method_exists($this, $method = ('get' . ucfirst($name))))
            return $this->$method();
        else
            throw new Exception('Can\'t get property ' . $name);
    }

    private function getUserId()
    {
        return $this->session->userId;
    }

    private function getUserName()
    {
        return $this->session->username;
    }

    private function getFullName()
    {
        return $this->session->fullName;
    }

    public function isLoggedIn()
    {
        return $this->session->isset('username');
    }
}