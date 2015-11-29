<?php

class Cookie
{
    public function __get($name)
    {
        return $_COOKIE[$name];
    }

    public function __set($name, $value)
    {
        $_COOKIE[$name] = $value;
    }

    public function delete($name)
    {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }
    }

}