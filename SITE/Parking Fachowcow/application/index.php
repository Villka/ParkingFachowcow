<?php

/**
 *  Point of entry. All requests come here.
 */

require_once "config.php";
require_once "Autoloader.php";
if (PHP_OLD_VERSION)
{
    require_once "app/libs/password/password.php";
}

spl_autoload_register(array("Autoloader", 'loadPackages'));

require_once "app/Start.php";