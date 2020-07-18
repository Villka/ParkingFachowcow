<?php

namespace app\models;

use app\libs\PDOHandler as pdo;
use app\core as core;

class AuthModel extends core\Model
{

    private $sql;

    function __construct()
    {
        $this->sql = new pdo\PDOHandler;
    }

    public function checkFirstAuth()
    {
        $users = $this->sql->newQuery()
                           ->select(['login'])
                           ->from('users')
                           ->doQuery();
        $userCheck = $users[0];

        if($userCheck)
        {
            return ["status" => "false"];
        }

        return ["status" => "true"];
    }

    public function addUser($email, $login, $password)
    {
        $firstAuth = $this->checkFirstAuth();

        if ($firstAuth['status'] == "true")
        {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            $result = $this->sql->newQuery()->insert('users', ["email", "login", "password"], "'$email', '$login', '$hashPassword'")->doQuery();
            if ($result)
            {
                return ["status" => "success"];
            }
            return ["status" => "error"];
        }

        return ["status" => "err_exists"];
    }

    public function login($login, $password)
    {
        if ($login and $password)
        {
            $userLogin = $this->sql->newQuery()
                                   ->select(['id', 'login', 'password'])
                                   ->from('users')
                                   ->where("login='$login'")
                                   ->doQuery();
            $user = $userLogin[0];

            if(!$user)
            {
                return ["status" => "no_user"];
            }

            // $hash = password_hash($password, PASSWORD_DEFAULT);

            if(false === password_verify($password, $user['password']))
            {
                return ["status" => "err_password"];
            }


            $token = $this->generateToken($user['login']);
            $tokenInput = $this->sql->newQuery()->update('users', ['token'], ["'$token'"], 'id=' . $user['id'])->doQuery();

            if($tokenInput)
            {
                $result = [
                    'id'       => $user['id'],
                    'login'    => $user['login'],
                    'token'    => $token,
                    'status'   => 'success'
                ];
                return $result;
            }

            return ["status" => "error"];
        }
        return ["status" => "empty_form"];
    }

    public function logout($id)
    {
        $this->sql->newQuery()->update('users', ['token'], ['NULL'], 'id=' . $id)->doQuery();
    }

    public function userCheck($login, $token)
    {
        $user = $this->sql->newQuery()->select("login, token")
                                      ->from("users")
                                      ->where("login='$login'")
                                      ->doQuery();
        $user = $user[0];
        if($token != $user['token'])
        {
            return ['status' => 'err_login'];
        }
        return ['status' => 'success'];
    }

    private function generateToken($user="")
    {
        $token = md5($user . time(microtime()));
        return $token;
    }
}