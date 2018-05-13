<?php

namespace models;

use lib\Form;
use lib\Session;

class LoginForm extends Form
{
    public $email;
    public $password;

    /**
     * LoginForm constructor.
     *
     * @param string|null $email
     * @param string|null $password
     */
    public function __construct($email = null, $password = null)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Validating the attributes of the login form
     */
    public function isValid()
    {
        $is_good = true;

        /** @var Session $session */
        $session = Session::getInstance();

        $this->email = $this->asEmail($this->email);
        if (!$this->email) {
            $is_good = false;
            $session->setFlash('Некорректный электронный адрес', 'warning');
        }

        return $is_good;
    }
}