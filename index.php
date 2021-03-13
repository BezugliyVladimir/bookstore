<?php

// Front Controller

ini_set('display_errors',1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'tmp/errorslog.txt');

session_start();

define('ROOT', dirname(__FILE__));
require_once(ROOT.'/submodules/Autoload.php');


$router = new Routing();
$router->run();
