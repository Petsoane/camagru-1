<?php

// require_once('./config/setup.php');


define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

// Load config
require_once(ROOT . DS. 'config' . DS . 'config.php');
require_once(ROOT . DS. 'app' . DS . 'lib' . DS . 'helpers' . DS . 'functions.php');
dump("Loaded config files");

// Autoload classes
function autoload($className) {
    if (file_exists(ROOT . DS . 'core' . DS . $className . '.php')) {
        require_once(ROOT . DS . 'core' . DS . $className . '.php');
        dump("required: " . ROOT . DS . 'core' . DS . $className . '.php');
    } else if (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . $className . '.php')) {
        require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . $className . '.php'); 
        dump("required: " . ROOT . DS . 'app' . DS . 'controllers' . DS . $className . '.php');
    } else if (file_exists(ROOT . DS . 'app' . DS . 'models' . DS . $className . '.php')) {
        require_once(ROOT . DS . 'app' . DS . 'models' . DS . $className . '.php'); 
        dump("required: " . ROOT . DS . 'app' . DS . 'models' . DS . $className . '.php');
    }
}


session_start();

// dnd($_SESSION);

dump("Session start");
spl_autoload_register('autoload');

$url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];    

if (!Session::exists(SESSION_NAME) && Cookie::exists(REMEMBER_ME)) {
    User::loginCookie();
    echo "logging in from cookie";
}

// Routing
dump("Routing: ", $url);
Router::route($url);