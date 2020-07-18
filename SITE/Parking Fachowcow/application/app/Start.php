<?php
/**
 *
 * File, which starts router and other core classes
 * 
 */
namespace app;
use app\core as core;
$server = new core\Route;
$server->start();