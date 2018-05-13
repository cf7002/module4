<?php

namespace models;

use lib\Model;
use lib\Session;

class User extends Model
{
    const ROLE_USER = 1;
    const ROLE_EDITOR = 4;
    const ROLE_MODERATOR = 7;
    const ROLE_ADMIN = 9;

    protected $tbl_name = 'tbl_users';

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
            'password' => 'Пароль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'nick_name' => 'Псевдоним',
            'role' => 'Роль',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
            'version' => 'Версия',
            'status' => 'Статус',
        ];
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function findByEmail(array $data)
    {
        $sql = "SELECT * FROM {$this->tbl_name} WHERE email = ? LIMIT 1";

        $result = $this->db->select($sql, $data);

        return empty($result[0]) ? false : $result[0];
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function findById(array $data)
    {
        $sql = "SELECT * FROM {$this->tbl_name} WHERE id = ?";

        return $result = $this->db->select($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function createUser(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} 
          (email, password, first_name, last_name, nick_name, role, status, created_at, updated_at, version)
          values (?,?,?,?,?,?,?,?,?,?)";

        return $this->db->insert($sql, $data);
    }

    /**
     * @return bool
     */
    public static function isUser()
    {
        /** @var Session $session */
        $session = Session::getInstance();

        return (bool) $session->get('user');
    }

    /**
     * @return bool
     */
    public static function isAdmin()
    {
        /** @var Session $session */
        $session = Session::getInstance();

        $role = $session->get('role');
        if ($role && $role === User::ROLE_ADMIN) {
            return true;
        }

        return false;
    }
}