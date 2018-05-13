<?php

use lib\Config;

Config::set('site_name', 'Bla-Bla News');

Config::set('lang', [
    'en',
    'uk',
    'ru'
]);

Config::set('routes', [
    'default' => '',
    'admin' => 'admin_',
]);

Config::set('defaults', [
    'route' => 'default',
    'lang' => 'ru',
    'controller' => 'site',
    'action' => 'index',
]);

Config::set('db', [
    'host' => '127.0.0.1',
    'user' => 'root',
    'pass' => '',
    'db' => 'blabla_news',
]);

Config::set('main_menu', [
    'page' => 'Pages',
    'site/contact' => 'Contact us',
]);

Config::set('admin_menu', [
    'page' => 'Pages',
    'site/contact' => 'Messages',
    'user' => 'Users',
]);

Config::set('uploads', [
//    'upload_path' => ROOT . 'web' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR,
    'max_file_size' => '153600', // макс. размер загружаемого файла в байтах
]);

Config::set('items_per_page', 5);

