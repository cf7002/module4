<?php
namespace lib;

class Session
{
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
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Sets a session variable when none exists
     *
     * @param $key
     * @param $value
     */
    public function store($key, $value)
    {
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * @param $key
     * @param bool $remove
     *
     * @return null
     */
    public function get($key, $remove = false)
    {
        $value = null;
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            if ($remove) {
                unset($_SESSION[$key]);
            }
        }
        return $value;
    }

    /**
     * @return bool
     */
    protected function hasFlash()
    {
        return empty($_SESSION['flash']) ? false : true;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getTypeMessage($type)
    {
        switch ($type) {
            case 'success':
                $class = 'alert-success';
                break;
            case 'warning':
                $class = 'alert-warning';
                break;
            case 'danger':
                $class = 'alert-danger';
                break;
            default:
                $class = 'alert-primary';
        }

        return $class;
    }

    /**
     * @param string $message
     * @param string $type
     */
    public function setFlash($message, $type)
    {
        $_SESSION['flash'][] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    /**
     * Display flash message and erase from session
     */
    public function getFlash()
    {
        $message = [];

        if (self::hasFlash()) {
            foreach ($_SESSION['flash'] as $flash) {
                $message[] = [
                    'type' => $this->getTypeMessage($flash['type']),
                    'message' => $flash['message'],
                ];
            }
            $_SESSION['flash'] = [];
        }

        return $message;
    }
}
