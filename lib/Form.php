<?php

namespace lib;

class Form
{
    /**
     * @param string $attr
     * @param int $filter
     * @param array|int $options
     *
     * @return bool|mixed
     */
    protected function attrChecking($attr, $filter, $options = null)
    {
        $attr = trim($attr);

        return strlen($attr) === 0 ? false : filter_var($attr, $filter, $options);
    }

    /**
     * @param string $attr
     *
     * @return bool|mixed
     */
    protected function asEmail($attr)
    {
        $attr = $this->attrChecking($attr, FILTER_SANITIZE_EMAIL);

        return $attr ? $this->attrChecking($attr, FILTER_VALIDATE_EMAIL) : false;
    }

    /**
     * @param string $attr
     * @param array $options
     *
     * @return bool|mixed
     */
    protected function asLength($attr, array $options = [])
    {
        return $this->attrChecking($attr, FILTER_VALIDATE_REGEXP, array('options' => $options));
    }

    /**
     * @param string $attr
     * @param array $options
     *
     * @return bool|mixed
     */
    protected function asTitle($attr, array $options = [])
    {
        $attr = $this->attrChecking($attr, FILTER_VALIDATE_REGEXP, array('options' => $options));
        $attr = $attr ? $this->attrChecking($attr, FILTER_SANITIZE_STRING) : false;

        return $attr ? $this->attrChecking($attr, FILTER_SANITIZE_SPECIAL_CHARS) : false;
    }

    /**
     * @param $attr
     * @param array $options
     *
     * @return bool|mixed
     */
    protected function asInt($attr, array $options = [])
    {
        return $this->attrChecking($attr, FILTER_VALIDATE_INT, array('options' => $options));
    }

    /**
     * @param string $attr
     *
     * @return bool|mixed
     */
    protected function asText($attr)
    {
        $attr = trim($attr);
        if (strlen($attr) < 2) $attr = false;

        return $attr ? $this->attrChecking($attr, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : false;
    }

    /**
     * @param string $attr
     *
     * @return int|null
     */
    protected function asCheckbox($attr)
    {
        return is_null($attr) ? null : 1;
    }

    /**
     * @param string $attr
     *
     * @return int
     */
    protected function asRadio($attr)
    {
        return empty($attr) ? 0 : 1;
    }

    /**
     * @param $attr
     *
     * @return bool|mixed
     */
    protected function asUrl($attr)
    {
        $attr = $this->attrChecking($attr, FILTER_SANITIZE_URL);

        return $attr ? $this->attrChecking($attr, FILTER_VALIDATE_URL) : false;
    }

    /**
     * @param $attr
     *
     * @return bool|mixed
     */
    protected function asFloat($attr)
    {
        return $attr ? $this->attrChecking($attr, FILTER_VALIDATE_FLOAT) : false;
    }
}
