<?php
namespace lib;

class Config {

    private static $values = [];

    public static function get($name)
    {
        return @self::$values[$name];
    }

    public static function set($name, $obj)
    {
        self::$values[$name] = $obj;
    }

}