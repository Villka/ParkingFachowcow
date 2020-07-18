<?php

namespace app\controllers;

use app\core as core;
use app\models as models;
use app\libs\validator as valid;

class RequestController extends core\Controller
{

    private $auth;

    function __construct($class)
    {
        $viewNameSpc = 'app\api\request\\' . $class;
        $this->view = new $viewNameSpc;
        $this->validator = new valid\Validator;
        $this->model = new models\RequestModel;
        $this->auth = new models\AuthModel;
    }

    public function getAction($input)
    {
        $request = $this->parseGetData($input);

        $login = $request[0];
        $token = $request[1];
        $id = $request[2];

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
        
        if ($id)
        {
            $idCheck = $this->validator->checkRule($id, "isInteger");
            if ($idCheck)
            {
                $result = $this->model->getRequestById($id);
            }
        } else {
            $result = $this->model->getAllRequests();
        }
        $this->view->getRequest($result);
        
    }

    public function postAction()
    {
        $request = $this->getPostData();
        $utmQueryString = @parse_url($_SERVER["HTTP_REFERER"])['query'];

        $utmQueryData = $this->getUtmData($utmQueryString);
        $valids = array();
        foreach($request as $key => $field)
        {
            if ($field)
            {
                $pureField = strip_tags(stripslashes($field));

                if ($pureField !== $request[$key])
                {
                    return $this->view->postRequest(REQ_ERR_VALID_TEXT, '.txt');
                }

                $valids[$key] = trim($pureField);
            } else {
                $valids[$key] = "Not set";
            }
        }

        $requestInfo = array();
        $requestInfo['date'] = date("Y-m-d G:i:s");

        $requestInfo['ip'] = $_SERVER[ "REMOTE_ADDR" ];

        if($utmQueryData)
        {
            $requestInfo['utmData'] = $utmQueryData;
        }

        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$_REQUEST['REMOTE_ADDR']));
        
        if($query['status'] == 'success')
        {
            $requestInfo['country'] = $query['country'];
        } else {
            $requestInfo['country'] = "Unknown";
        }

        $result = $this->model->addRequest($requestInfo, $valids);
        $this->model->sendEmailRequest($requestInfo, $valids);

        $this->view->postRequest($result, '.txt');
        
        
    }

    public function putAction()
    {
        $request = $this->getPutData();
        
        $login = $request['login'];
        $token = $request['token'];
        $id = $request['id'];

        $loginCheck = $this->validator->checkRule($login, "isStringText");
        $tokenCheck = $this->validator->checkRule($token, "isStringText");
        $idCheck = $this->validator->checkRule($id, "isInteger");

        if (true == $loginCheck and true == $tokenCheck)
        {
            $userCheck = $this->auth->userCheck($login, $token);   
        }
        
        if (!$userCheck or $userCheck['status'] != 'success')
        {
            return $this->view->getRequest(['status' => 'err_login']);
        }

        if ($idCheck)
        {
            $result = $this->model->checkedApp($id);
        }

        $this->view->putRequest($result);


    }

    public function deleteAction($input)
    {

    }

    /**
     *
     * @param string
     * @return array or false
     * 
     * get query string from last page
     * and try to create utm data array
     * if no utm params return false
     * 
     */
    private function getUtmData($queryString)
    {
        if (is_string($queryString) and strlen($queryString) > 0)
        {
            $utmQueryArr = explode("&", $queryString);
    
            $utmQueryData = [];
    
            foreach ($utmQueryArr as $part)
            {
                array_push($utmQueryData, explode("=", $part));
            }
    
            if ($utmQueryData[0][0] == 'utm_source') {
                return $utmQueryData;
            }
        }
        return false;
    }

}