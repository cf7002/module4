<?php

namespace lib;

class Model
{
    const STATUS_ACTIVE = '1';
    const STATUS_NOT_ACTIVE = '0';

    const ICON_ACTIVE = 'text-success fas fa-check';
    const ICON_NOT_ACTIVE = 'text-danger fas fa-times';
    const ICON_PASSIVE = 'text-warning fas fa-minus';

    /** @var DB */
    protected $db;

    /**
     * Model constructor.
     *
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * @param $tbl_name
     * @param $field
     * @param $value
     *
     * @return bool
     */
    public function isUnique($tbl_name, $field, $value)
    {
        $sql = "SELECT COUNT({$field}) AS cnt FROM {$tbl_name} WHERE {$field} = ?";

        $result = $this->db->select($sql, [$value]);

        return empty($result[0]['cnt']) ? true : false;
    }

    /**
     * @param string $tbl_name
     * @param bool $only_active
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function getAllTable($tbl_name, $only_active = false)
    {
        if (!$tbl_name) {
            throw new \Exception("Пустое имя таблицы!!!");
        }

        $cond = $only_active ? 'WHERE status = ' . Model::STATUS_ACTIVE : '';
        $sql = "SELECT * FROM {$tbl_name} {$cond}";

        return $this->db->select($sql);
    }

//    /**
//     * @param mixed $db
//     */
//    public function setDb(DB $db)
//    {
//        $this->db = $db;
//    }
}