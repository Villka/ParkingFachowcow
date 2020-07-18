<?php

namespace app\controllers;

use app\core as core;
use app\models as models;
use app\libs\validator as valid;

class FirstAuthController extends core\Controller
{
    private $validator;
    private $auth;

    function __construct($class)
    {
        $viewNameSpc = 'app\api\firstAuth\\' . $class;
        $this->view = new $viewNameSpc;
        $this->validator = new valid\Validator;
        $this->model = new models\AuthModel;
    }

    public function getAction($input)
    {
        $result = $this->model->checkFirstAuth();
        $this->view->getFirstAuth($result);
    }

    public function postAction($input)
    {
        $request = $this->getPostData();
        
        $email = trim(strip_tags($request['email']));
        $login = trim(strip_tags($request['login']));
        $password = trim(strip_tags($request['password']));
        $rePassword = trim(strip_tags($request['rePassword']));

        if($password != $rePassword)
        {
            return ['status' => 'err_password'];
        }
        if(true === $this->validator->checkRule($email, 'isEmail') and
           true === $this->validator->checkRule($login, 'isStringText') and
           true === $this->validator->checkRule($password, 'isStringText'))
        {
            $result = $this->model->addUser($email, $login, $password);
        } else {
            $result = ['status' => 'err_valid'];
        }

        $this->view->postFirstAuth($result);
    }

    public function putAction()
    {

    }

    public function deleteAction($input)
    {
        
    }

}