<?php

namespace models;

use lib\Model;

class Vote extends Model
{
    protected $tbl_name = 'tbl_votes';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'comment_id' => 'Комментарий',
            'user_id' => 'Пользователь',
            'vote' => 'Голос',
            'created_at' => 'Создано',
        ];
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tbl_name;
    }

    /**
     * @param array $data
     *
     * @return array|bool
     *
     */
    public function checkVote(array $data)
    {
        $sql = "SELECT * FROM {$this->tbl_name} 
            WHERE comment_id = ? AND user_id = ?";

        return $this->db->select($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function voting(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} (comment_id, user_id, vote, created_at) VALUES (?,?,?,?)";

        return $this->db->update($sql, $data);
    }
}