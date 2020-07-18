<?php

namespace app\controllers;

use app\libs\validator as valid;
use app\core as core;
use app\models as models;
use app\libs\auth as auth;

class UserController extends core\Controller
{
    private $auth;

    function __construct($class)
    {
        
        $viewNameSpc = 'app\api\user\\' . $class;
        $this->view = new $viewNameSpc;
        $this->validator = new valid\Validator;
        $this->model = new models\UserModel;
        $this->auth = new models\AuthModel;
        
    }

    public function getAction($input)
    {

    }

    public function postAction()
    {

    }

    public function putAction()
    {
        $request = $this->getPutData();
        
        $login = trim(strip_tags($request['login']));
        $token = trim(strip_tags($request['token']));
        $email = trim(strip_tags($request['email']));
        $oldPassword = trim(strip_tags($request['oldPass']));
        $newPassword = trim(strip_tags($request['newPass']));
        $rePassword = trim(strip_tags($request['rePass']));

        $loginCheck = $this->validator->checkRule($login, "isStringText");
        $tokenCheck = $this->validator->checkRule($token, "isStringText");

        if (true == $loginCheck and true == $tokenCheck)
        {
            $userCheck = $this->auth->userCheck($login, $token);   
        }
        
        if (!$userCheck or $userCheck['status'] != 'success')
        {
            return $this->view->getRequest(['status' => 'err_login']);
        }

        if ($email and !$newPassword)
        {

            $emailCheck = $this->validator->checkRule($email, 'isEmail');

            if (true !== $emailCheck)
            {
                return $this->view->putUser(['status' => 'err_email']);
            }

            $result = $this->model->updateEmail($login, $email);
            

        } elseif ($newPassword and !$email) {

            if($newPassword != $rePassword)
            {
                return $this->view->putUser(['status' => 'err_rePassword']);
            }

            $result = $this->model->updatePassword($login, $oldPassword, $newPassword);
        } else {
            $result = ['status' => 'err_unknown'];

        }

        $this->view->putUser($result);

    }

    public function deleteAction($input)
    {

    }

}