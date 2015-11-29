<?php

class Session
{
    public function __get($name)
    {
        return $_SESSION[$name];
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function delete($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    public function isset($name)
    {
        return isset($_SESSION[$name]);
    }
}