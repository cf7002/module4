<?php

namespace models;

use lib\Form;
use lib\Session;

class CategoryForm extends Form
{
    public $title;
    public $priority;
    public $is_moderated;
    public $sub_category;
    public $status;
    public $version;

    /**
     * CategoryForm constructor.
     *
     * @param string|null $title
     * @param string $priority
     * @param string $is_moderated
     * @param string $sub_category
     * @param string $status
     * @param string $version
     */
    public function __construct(
        $title = null,
        $priority = '0',
        $is_moderated = '0',
        $sub_category = '0',
        $status = '0',
        $version = '0'
    ) {
        $this->title = $title;
        $this->priority = $priority;
        $this->is_moderated = $is_moderated;
        $this->sub_category = $sub_category;
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

        $this->priority = $this->asInt($this->priority, array('min_range' => 0, 'max_range' => 255));
        if ($this->priority === false) {
            $is_good = false;
            $session->setFlash("Недопустимое значение приоритета.", 'warning');
        }

        $this->is_moderated = $this->asRadio($this->is_moderated);

        $this->sub_category = $this->asRadio($this->sub_category);

        $this->status = $this->asRadio($this->status);

        $this->version = $this->asInt($this->version, array('min_range' => 1, 'max_range' => 65535));
        if (!$this->version) {
            $is_good = false;
            $session->setFlash("Недопустимое значение версии.", 'warning');
        }

        return $is_good;
    }
}