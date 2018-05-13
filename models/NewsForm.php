<?php

namespace models;

use lib\Form;
use lib\Session;

class NewsForm extends Form
{
    public $category_id;
    public $title;
    public $content;
    public $is_analytic;
    public $status;
    public $version;

    /**
     * NewsForm constructor.
     *
     * @param string|null $title
     * @param string|null $content
     * @param string $is_analytic
     * @param string $status
     * @param string $version
     * @param string|null $category_id
     */
    public function __construct(
        $title = null,
        $content = null,
        $is_analytic = null,
        $status = '0',
        $version = '0',
        $category_id = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->is_analytic = $is_analytic;
        $this->status = $status;
        $this->version = $version;
        $this->category_id = $category_id;
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

//        $this->category_id = $this->asInt($this->category_id, array('min_range' => 1, 'max_range' => 4294967295));
//        if (!$this->category_id) {
//            $is_good = false;
//            $session->setFlash("Недопустимое значение категории.", 'warning');
//        }

        $this->title = $this->asTitle($this->title, array('regexp' => "/^[\w- ]{2,25}$/u"));
        if (!$this->title) {
            $is_good = false;
            $session->setFlash("Название должно быть больше 2 и меньше 25 символов.", 'warning');
        }

        $this->content = $this->asText($this->content);
        if (!$this->content) {
            $is_good = false;
            $session->setFlash("Ошибка кодировки.", 'warning');
        }

        $this->is_analytic = $this->asCheckbox($this->is_analytic);

        $this->status = $this->asRadio($this->status);

        $this->version = $this->asInt($this->version, array('min_range' => 1, 'max_range' => 65535));
        if (!$this->version) {
            $is_good = false;
            $session->setFlash("Недопустимое значение версии.", 'warning');
        }

        return $is_good;
    }
}