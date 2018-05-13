<?php

namespace models;

use lib\Form;
use lib\Session;

class AdvtForm extends Form
{
    public $title;
    public $price;
    public $vendor;
    public $href;
    public $rating;
    public $status;
    public $version;

    /**
     * AdvtForm constructor.
     *
     * @param null $title
     * @param null $price
     * @param null $vendor
     * @param null $href
     * @param null $rating
     * @param string $status
     * @param string $version
     */
    public function __construct(
        $title = null,
        $price = null,
        $vendor = null,
        $href = null,
        $rating = null,
        $status = '0',
        $version = '0'
    ) {
        $this->title = $title;
        $this->price = $price;
        $this->vendor = $vendor;
        $this->href= $href;
        $this->rating = $rating;
        $this->status = $status;
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

        $this->title = $this->asTitle($this->title, array('regexp' => "/^[\w- ]{2,25}$/u"));
        if (!$this->title) {
            $is_good = false;
            $session->setFlash("Название должно быть больше 2 и меньше 25 символов.", 'warning');
        }

        $this->price = $this->asFloat($this->price);
        if (!$this->price) {
            $is_good = false;
            $session->setFlash("Не корректная сумма.", 'warning');
        }

        $this->vendor = $this->asTitle($this->vendor, array('regexp' => "/^[\w- ]{2,125}$/u"));
        if (!$this->vendor) {
            $is_good = false;
            $session->setFlash("Производитель должно быть больше 2 и меньше 125 символов.", 'warning');
        }

        $this->href = $this->asUrl($this->href);
        if (!$this->href) {
            $is_good = false;
            $session->setFlash("Не корректный веб-адрес.", 'warning');
        }

        $this->rating = $this->asInt($this->rating, array('min_range' => 0, 'max_range' => 255));
        if ($this->rating === false) {
            $is_good = false;
            $session->setFlash("Недопустимое значение рейтинга.", 'warning');
        }

        $this->status = $this->asRadio($this->status);

        $this->version = $this->asInt($this->version, array('min_range' => 1, 'max_range' => 65535));
        if (!$this->version) {
            $is_good = false;
            $session->setFlash("Недопустимое значение версии.", 'warning');
        }

        return $is_good;
    }}