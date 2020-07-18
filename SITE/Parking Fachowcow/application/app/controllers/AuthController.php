<?php

namespace app\controllers;

use app\core as core;
use app\libs\auth as auth;

class AuthController extends core\Controller
{
    private $auth;

    function __construct($class)
    {
        
        $viewNameSpc = 'app\api\auth\\' . $class;
        $this->view = new $viewNameSpc;
        $this->auth = new auth\Auth;
        
    }

    public function getAction($input)
    {
        $request = $this->parseGetData($input);

        $login = trim(strip_tags($request[0]));
        $token = trim(strip_tags($request[1]));

        $result = $this->auth->userCheck($login, $token);

        $this->view->getAuth($result);
    }

    public function postAction()
    {
        
    }

    public function putAction()
    {
        $request    = $this->getPutData();
        // var_dump($request);
        $result     = $this->auth->login($request['username'], $request['password']);

        $this->view->putAuth($result);
    }

    public function deleteAction($input)
    {
        $request = $this->getDeleteParams($input);
        $id = $request[0];

        $this->auth->logout($id);
    }

}