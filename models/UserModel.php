<?php

class UserModel extends BaseModel
{
    private function isValidUsername($username)
    {
        return isset($username) && trim($username);
    }

    private function isValidPassword($password)
    {
        return isset($password) && trim($password);
    }

    public function exists($username)
    {
        $statement = self::$db->query("SELECT id FROM user WHERE username = '$username'");
        $rowsCount = $statement->num_rows;

        return $rowsCount > 0;
    }

    public function register($username, $password, $fullName)
    {
        if (!$this->isValidUsername($username) || !$this->isValidPassword($password)) {
            throw new \Exception('Username or password is invalid');
        }

        if ($this->exists($username)) {
            throw new \Exception('User already registered');
        }

        $statement = self::$db->prepare("INSERT INTO user VALUES(NULL ,?, ?, ?)");
        if (!$statement) {
            throw new \Exception('Could not create user');
        }

        $statement->bind_param('sss', $username, password_hash($password, PASSWORD_DEFAULT), $fullName);
        $statement->execute();
        $statement->store_result();

        if ($statement->affected_rows != 1) {
            throw new Exception('Could not create user');
        }

        return $username;
    }

    public function login($username, $password)
    {
        if (!$this->isValidUsername($username) || !$this->isValidPassword($password)) {
            return false;
        }

        $statement = self::$db->prepare("SELECT id,username,password FROM user WHERE username = ?");
        $statement->bind_param('s', $username);
        $statement->execute();
        $statement->store_result();
        $statement->bind_result($id, $dbUsername, $dbPassword);
        $statement->fetch();
        if ($statement->num_rows() != 1) {
            throw new Exception('Username or password does not match');
        }

        if (!password_verify($password, $dbPassword)) {
            throw new Exception('Username or password does not match');
        }

        return $dbUsername;
    }
}