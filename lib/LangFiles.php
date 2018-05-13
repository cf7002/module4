<?php
namespace lib;

class LangFiles
{
    private $lang = [];
    /** @var LangFiles */
    private static $instance;

    /**
     * LangFiles constructor.
     * Is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from LangFiles::getInstance() instead
     */
    private function __construct() {}

    /**
     * Prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone() {}

    /**
     * Prevent from being unserialized (which would create a second instance of it)
     */
    private function __wakeup() {}

    /**
     * Gets the instance via lazy initialization (created on first usage)
     *
     * @return LangFiles
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @param $lang
     *
     * @throws \Exception
     */
    public function load($lang)
    {
        $lang_file = ROOT . 'lang' . DIRECTORY_SEPARATOR . strtolower($lang) . '.php';

        if (!file_exists($lang_file)) {
            throw new \Exception("Language file {$lang_file} not found.");
        }

        $this->lang = include $lang_file;
    }

    /**
     * @param $phrase
     * @param string $default
     *
     * @return mixed|string
     */
    public function translate($phrase, $default)
    {
        $result = $this->lang[$phrase];
        if (!$result) {
            return $default;
        }

        return $result;
    }
}

