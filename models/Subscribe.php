<?php

namespace models;

use lib\Model;

class Subscribe extends Model
{
    protected $tbl_name = 'tbl_subscribers';

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tbl_name;
    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'ID',
            'email' => 'Электронный адрес',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
        ];
    }

    /**
     * @param array $data
     *
     * @return bool|string
     */
    public function createSubscriber(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} 
          (email, first_name, last_name)
          values (?,?,?)";

        return $this->db->insert($sql, $data);
    }
}