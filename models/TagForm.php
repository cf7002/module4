<?php

namespace models;

use lib\Form;
use lib\Session;

class TagForm extends Form
{
    public $title;
    public $status;
    public $version;

    /**
     * TagForm constructor.
     *
     * @param string|null $title
     * @param string $status
     * @param string $version
     */
    public function __construct($title = null, $status = '0', $version = '0')
    {
        $this->title = $title;
        $this->status = $status;
        $this->version = $version;
    }

    /**
     * Validating the attributes of the form
     *
     * @return bool
     */
    public function isValid()
    {
        $is_good = true;

        /** @var Session $session */
        $session = Session::getInstance();

        $this->title = $this->asTitle($this->title, array('regexp' => "/^[\w- ]{2,25}$/u"));
        if (!$this->title) {
            $is_good = false;
            $session->setFlash("Название должно быть больше 2 и меньше 25 символов.", 'warning');
        }

        $this->status = $this->asRadio($this->status);

        $this->version = $this->asInt($this->version, array('min_range' => 1, 'max_range' => 65535));
        if (!$this->version) {
            $is_good = false;
            $session->setFlash("Недопустимое значение версии.", 'warning');
        }

        return $is_good;
    }
}