<?php

namespace models;

use lib\Form;
use lib\Session;

class RegisterForm extends Form
{
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $nick_name;
    public $version;

    /**
     * RegisterForm constructor.
     *
     * @param string|null $email
     * @param string|null $password
     * @param string|null $first_name
     * @param string|null $last_name
     * @param string|null $nick_name
     * @param string $version
     */
    public function __construct(
        $email = null,
        $password = null,
        $first_name = null,
        $last_name = null,
        $nick_name = null,
        $version = '0'
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->nick_name = $nick_name;
        $this->version = $version;
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

        $this->nick_name = $this->asTitle($this->nick_name, array('regexp' => "/^[\w- ]{2,25}$/u"));
        if (!$this->nick_name) {
            $is_good = false;
            $session->setFlash("Псевдоним должен быть больше 2 и меньше 25 символов.", 'warning');
        }

        $this->password = $this->asLength($this->password, array('regexp' => "/^[\w- ]{5,15}$/u"));
        if (!$this->password) {
            $is_good = false;
            $session->setFlash("Пароль должен быть больше 5 и меньше 15 символов.", 'warning');
        }

        $this->version = $this->asInt($this->version, array('min_range' => 1));

        return $is_good;
    }
}