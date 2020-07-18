<?php
 /**
 *   
 *  Core file of View
 *  Take data and type of view info and show it. View types: xml, txt, json, html (as simple list)
 * 
**/
namespace app\core;

class View
{
   
    /**
     *
     * @param array
     * @param string
     * @return string
     * 
     * Formats response in fromat from query string
     * or default value from config
     * and call appropriate method to show response
     * 
     */
    public function showResponse($data, $viewType=VIEW_JSON)
    {
        switch (strtolower($viewType))
        {
            case '.txt':
                header("Content-Type: text/plain");
                $this->toText($data);
                break;
            case '.html':
                header("Content-Type: text/html");
                $this->toHtml($data);
                break;
            case '.xml':
                header("Content-Type:text/xml");
                $this->toXML($data);
                break;
            default:
                header("Content-Type:application/json");
                $this->toJSON($data);
        }
    }

    private function toJSON($data)
    {
        print_r(json_encode($data));
        
    }

    private function toText($data)
    {
        $text = "";
        if (is_array($data))
        {
            foreach ($data as $data_key => $data_value)
            {
                if (is_array($data_value))
                {
                    foreach ($data_value as $key => $param)
                    {
                        $text .= "$key: $param\n";
                    }
                }

                if (is_string($data_value))
                {
                    $text .= "$data_key: $data_value\n";
                }
            }
        }

        if (is_string($data))
        {
            $text = $data;
        }
        print_r($text);
    }

    private function toHtml($data)
    {
        if(is_array($data))
        {
            $result = "";
            foreach($data as $key => $value)
            {
                $result .= "<div>";
                if (is_array($value))
                {
                    $result .="<ul>";
                    foreach($value as $param_key => $param_val)
                    {
                        $result .="<li>" . $param_key . ": " . $param_val . "</li>";
                    }
                    $result .="</ul>";
                }
                if (is_string($value) || is_numeric($value))
                {
                    $result .= "<li>$key: $value</li>";
                }
                $result .= "</div>";
            }
            
        }
        echo $result;
    }

    private function toXML($data)
    {
        $xml = new \SimpleXMLElement('<elements/>');

        if (is_array($data))
        {
            foreach ($data as $data_key => $item)
            {
                if (is_array($item))
                {
                    $element = $xml->addChild('element');
                    foreach ($item as $key => $val)
                    {
                        $element->addChild($key, $val);
                    }
                }

                if(is_string($item))
                {
                    $xml->addChild($data_key, $item);
                }
            }
            $result = $xml->asXML();
        }
        print_r($result);
    }

    /**
     *
     * @param string
     * @return header or boolean
     * 
     * get status code 
     * and returning appropriate header
     * 
     */
    public function restResponse($status)
    {
        switch ($status)
        {
            case "200" || 200:
                $this -> successResponse();
                break;
            case "204" || 204:
                $this -> noContentResponse();
                break;
            case "404" || 404:
                $this -> unknownResponse();
                break;
            case "400" || 400:
                $this -> badRequestResponse();
                break;
            default:
                return false;
        }
    }

    protected function successResponse()
    {
        header("Status: 200 Ok");
    }

    protected function noContentResponse()
    {
        header("HTTP/1.1 204 No Content");
    }

    protected function unknownResponse()
    {
        header("HTTP/1.1 404 Not Found");
    }

    protected function badRequestResponse()
    {
        header('HTTP/1.1 400 Bad Request');
    }
}