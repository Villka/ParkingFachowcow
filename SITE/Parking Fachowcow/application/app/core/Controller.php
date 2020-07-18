<?php
/**
 *   
 *  Core file of Controller
 *  Here described methods for working with the different data (get, post, put, delete)
 *  All requests from frontend comes here, controller validate it. If validation success, 
 *  controller sending data to model, handles the response and push it into view class
 * 
**/
namespace app\core;

class Controller
{
    protected $model;
    protected $view;

    /**
     *
     * @param string
     * @return array
     * 
     * get query string and converting it into the data array
     * 
     */
    public function parseGetData($input)
    {
        // print_r($input);
        $pattern = ['/\.txt/','/\.html/','/\.xml/','/\.json/'];
        $input = preg_replace($pattern, '' ,$input);
        $data = explode('/',$input);
        return $data;
    }

    /**
     *
     * @return array
     * 
     * returning array with data from post query
     * 
     */

    public function getPostData()
    {
        return $_POST;
    }


    /**
     *
     * @return array
     * 
     * returning array with data from put query
     * 
     */
    public function getPutData()
    {
        $result = array(); 
        $putdata = file_get_contents('php://input'); 
        // print_r($putdata);
        $result = json_decode($putdata, true);
        return $result;
    }

    /**
     *
     * @param string
     * @return array
     * 
     * get query string from delete request
     * and converting it into the data array
     * 
     */
    public function getDeleteParams($input)
    {
        $data = explode("/", $input);
        return $data;
    }
}