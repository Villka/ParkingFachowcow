<?php
/**
 *   
 *  Routing class
 *  Parse url, choose class and method, start the server
 * 
**/
namespace app\core;

class Route
{
    
    private $service;
    private $url;

    public function __construct()
    {
        if (SERV_CROSS_DOMAIN)
        {
            header("Access-Control-Allow-Origin: *");
            header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE, PATCH');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,token, Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
            header("Access-Control-Expose-Headers: Location");
        }
        $this->url = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function start()
    {
        switch (PATH_DIR_QUANTITY)
        {
            case 1:
                list($server, $dir, $taskDir, $serverDir, $apiDir, $className, $input) = explode('/', $this->url, 7);
                break;
            case 2:
                list($server, $user, $dir, $taskDir, $serverDir, $apiDir, $className, $input) = explode('/', $this->url, 8);
                break;
            case 3: 
                list($server, $a, $user, $dir, $taskDir, $serverDir, $apiDir, $className, $input) = explode('/', $this->url, 9);
                break;
            default:
                list($server, $dir, $appDir, $apiDir, $className, $input) = explode('/', $this->url, 6);
        }


        // echo $server . "\n";
        // echo $dir . "\n";
        // echo $serverDir . "\n";
        // echo $apiDir . "\n";
        // echo $className . "\n";
        // echo $input . "\n";

        $controllerName = 'app\controllers\\' . ucfirst($className) . 'Controller';
        $controller = new $controllerName(ucfirst($className));

        if (!is_object($controller))
        {
            $this->error404();
        }

        switch($this->method)
        {
            case 'GET':
                if (method_exists($controller, 'getAction'))
                {
                    $controller->getAction($input);
                } else {
                    $this->error404();
                }
                break;
            case 'DELETE':
                if (method_exists($controller, 'deleteAction'))
                {
                    $controller->deleteAction($input);
                } else {
                    $this->error404();
                }
                break;
            case 'POST':
                if (method_exists($controller, 'postAction'))
                {
                    $controller->postAction($input);
                } else {
                    $this->error404();
                }
                break;
            case 'PUT':
                if (method_exists($controller, 'putAction'))
                {
                    $controller->putAction($input);
                } else {
                    $this->error404();
                }
                break;
            case 'OPTIONS':
                break;
            default:
                $this->error404();
        }
    }

    public function error404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '404');
    }
}