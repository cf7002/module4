<?php

error_reporting(E_ALL);

use lib\App;

define('ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('VIEW_DIR', ROOT . 'views' . DIRECTORY_SEPARATOR);
define('UPLOAD_DIR', ROOT . 'web' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);

//include '../.temp/generator.php';
//die;

require_once (ROOT . 'lib' . DIRECTORY_SEPARATOR . 'functions.php');

session_start();

try {
    $app = new App();
    $result = $app->run($_SERVER['REQUEST_URI']);
} catch (Exception $e){
    $result = $e->getMessage();
} finally {
    echo $result;
}
