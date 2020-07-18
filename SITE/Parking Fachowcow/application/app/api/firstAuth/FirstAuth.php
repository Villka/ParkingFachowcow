<?php

namespace app\api\firstAuth;
use app\core as core;

class FirstAuth extends core\View
{
    
    public function getFirstAuth($data="", $viewType="")
    {
        $this->restResponse("200");
        $this->showResponse($data, $viewType);
    }

    public function postFirstAuth($data="", $viewType="")
    {
        $this->restResponse("200");
        $this->showResponse($data, $viewType);
    }

    public function putFirstAuth($data="", $viewType="")
    {
        $this->restResponse("200");
        $this->showResponse($data, $viewType);
    }
    
    public function deleteFirstAuth($data="", $viewType="")
    {
        $this->restResponse("200");
        $this->showResponse($data, $viewType);
    }
}