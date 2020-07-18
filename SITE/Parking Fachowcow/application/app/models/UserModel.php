<?php

namespace app\models;

use app\libs\PDOHandler as pdo;
use app\core as core;

class UserModel extends core\Model
{

    private $sql;

    function __construct()
    {
        $this->sql = new pdo\PDOHandler;
    }

    public function updateEmail($login, $newEmail)
    {
        $result = $this->sql->newQuery()->update("users", ['email'], ["'$newEmail'"], "login='$login'")->doQuery();
        // echo $this->sql->getQuery();

        if ($result)
        {
            return ['status' => 'success'];
        }
        return ['status' => 'error'];
    }

    public function updatePassword($login, $oldPass, $newPass)
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

        if(false === password_verify($oldPass, $user['password']))
        {
            return ["status" => "err_password"];
        }

        $hashPassword = password_hash($newPass, PASSWORD_DEFAULT);

        $result = $this->sql->newQuery()->update("users", ['password'], ["'$hashPassword'"], "login='$login'")->doQuery();

        if ($result)
        {
            return ['status' => 'success'];
        }
        return ['status' => 'error'];
    }

}
