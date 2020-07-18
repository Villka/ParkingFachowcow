<?php
/**
 *  Class for autoloading classes
 */
class Autoloader 
{
    /**
     * 
     *  converts namespace to full path 
     *  @param string namespace name
     * 
     */
    public static function loadPackages($className)
    {
        $className = '' . str_replace('\\', '/', $className) . '.php';
        include_once($className);
    }
}