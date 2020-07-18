<?php

namespace app\libs\auth;
use app\models as models;
use app\libs\validator as validator;

class Auth 
{ 

    private $validator;
    private $model;

    function __construct()
    {
        $this->model            = new models\AuthModel();
        $this->validator        = new validator\Validator;
    }

    public function login($login, $password)
    {
        $loginCheck             = $this->validator->checkRule($login, "isStringText");
        $passwordCheck          = $this->validator->checkRule($password, "checkPass");

        if (true === $loginCheck and true === $passwordCheck)
        {
            $result             = $this->model->login($login, $password);
        } else {
            $result = [];
            $result['status']   = "err_valid";
            $result['errors']   = array($loginCheck, $passwordCheck);
        }
        return $result;
    }

    public function logout($id)
    {
        $idCheck                = $this->validator->checkRule($id, "isInteger");

        if (true === $idCheck)
        {
            $result             = $this->model->logout($id);
        } else {
            return false;
        }
    }

    public function userCheck($login, $token)
    {
        $loginCheck             = $this->validator->checkRule($login, "isStringText");
        $tokenCheck             = $this->validator->checkRule($token, "isStringText");

        if (true === $loginCheck and true === $tokenCheck)
        {
            $result             = $this->model->userCheck($login, $token);
        } else {
            $result = [];
            $result['status']   = "err_valid";
            $result['errors']   = array($loginCheck, $passwordCheck);
        }
        return $result;
    }
}