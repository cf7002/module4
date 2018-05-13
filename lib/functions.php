<?php

use lib\LangFiles;
use lib\Session;

spl_autoload_register(function($class_name){
    $file_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);

    $file_name = ROOT . $file_name . '.php';

    if (!file_exists($file_name)) {
        throw new Exception("File '$file_name' doesn't exist.");
    }

    include $file_name;
});

require_once (ROOT . 'config' . DIRECTORY_SEPARATOR . 'config.php');


/**
 * @param $key
 * @param $default
 *
 * @return string
 */
function _t($key, $default = '*****') {
    $lang = LangFiles::getInstance();

    return $lang->translate($key, $default);
}

/**
 * @return mixed
 */
function getFlash() {
    /** @var Session $session */
    $session = Session::getInstance();

    return $session->getFlash();
}
