<?php

interface IRouter
{
    public function getController();

    public function getAction();

    public function getParams();
}