<?php

class Request
{
    public function __get($name)
    {
        if (method_exists($this, $method = ('get' . ucfirst($name))))
            return $this->$method();
        else
            throw new Exception('Can\'t get property ' . $name);
    }

    public function getForm()
    {
        return json_decode(json_encode($_POST), FALSE);
    }

    public function getParams()
    {
        return json_decode(json_encode($_GET), FALSE);
    }
}