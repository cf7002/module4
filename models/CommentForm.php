<?php

namespace models;

use lib\Form;
use lib\Session;

class CommentForm extends Form
{
    public $news_id;
    public $parent_id;
    public $user_id;
    public $message;
    public $status;
    public $version;

    /**
     * CommentForm constructor.
     *
     * @param string $news_id
     * @param string $parent_id
     * @param string $user_id
     * @param string $message
     * @param string $status
     * @param string $version
     */
    public function __construct(
        $news_id,
        $parent_id,
        $user_id,
        $message,
        $status = '1',
        $version = '1'
    ) {
        $this->news_id = $news_id;
        $this->parent_id = $parent_id;
        $this->user_id = $user_id;
        $this->message = $message;
        $this->status = $status;
        $this->version = $version;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $is_good = true;

        /** @var Session $session */
        $session = Session::getInstance();

        $this->message = $this->asText($this->message);
        if (!$this->message) {
            $is_good = false;
            $session->setFlash("Некорректное сообщение.", 'warning');
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