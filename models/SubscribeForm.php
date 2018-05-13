<?php

namespace models;

use lib\Form;
use lib\Session;

class SubscribeForm extends Form
{
    public $email;
    public $first_name;
    public $last_name;

    /**
     * RegisterForm constructor.
     *
     * @param string|null $email
     * @param string|null $first_name
     * @param string|null $last_name
     */
    public function __construct(
        $email = null,
        $first_name = null,
        $last_name = null
    ) {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    /**
     * Validating the attributes of the registration form
     *
     * @return bool
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

        $this->first_name = $this->asTitle($this->first_name, array('regexp' => "/^[\w- ]{2,25}$/u"));
        if (!$this->first_name) {
            $is_good = false;
            $session->setFlash("Имя должно быть больше 2 и меньше 25 символов.", 'warning');
        }

        $this->last_name = $this->asTitle($this->last_name, array('regexp' => "/^[\w- ]{2,25}$/u"));
        if (!$this->last_name) {
            $is_good = false;
            $session->setFlash("Фамилия должна быть больше 2 и меньше 25 символов.", 'warning');
        }

        return $is_good;
    }
}