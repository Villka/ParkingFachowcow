<?php

namespace app\libs\validator;

class Validator
{
    /**
     *   
     *  Class-validator
     *  Checking inputs by some rules
     * 
    **/

    private $result;
    private $error;

    public function checkRule($data, $rule, $length=128)
    {
        if(method_exists($this, $rule))
        {
            $this->result = $this->$rule($data, $length);
        }

        if (!$this->result)
        {
            return $this->error;
        }
        return $this->result;
    }

    private function isText($string, $length=128)
    {
        if (strlen($string) > $length)
        {
            $this->error = ERR_STRING_LIMIT;
            return false;
        }

        if (!trim($string) || !is_string($string))
        {
            $this->error = ERR_STRING_TYPE;
            return false;
        }

        if (!preg_match("/^[a-zA-Z]+$/", $string))
        {
            $this->error = ERR_STRING_CHARS;
            return false;
        }

        return true;
    }

    private function isStringText($string, $length=128)
    {
        if (strlen($string) > $length)
        {
            $this->error = ERR_STRING_LIMIT;
            return false;
        }

        if (!$string or !is_string($string))
        {
            $this->error = ERR_STRING_TYPE;
            return false;
        }
        return true;
    }

    private function isEmail($email, $length=128)
    {
        if (strlen($email) > $length)
        {
            $this->error = ERR_STRING_LIMIT;
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->error = ERR_EMAIL_FILTER;
            return false;
        }
        return true;
    }

    private function isInteger($int)
    {
        if (is_null($int))
        {
            $this->error = ERR_INT_NULL;
            return false;
        }

        if (!is_numeric($int))
        {
            $this->error = ERR_INT_NOT_NUMERIC;
            return false;
        }
        return true;
    }

    private function checkPass($string)
    {
        if($string != trim($string))
        {
            $this->error = ERR_PASS_SPACES;
            return false;
        }
        return true;
    }
}