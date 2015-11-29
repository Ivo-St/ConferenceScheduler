<?php

interface IUserModel
{
    public function isLogged();

    public function register($username, $password, $fullName);

    public function login($username, $password);
}