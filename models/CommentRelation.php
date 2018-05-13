<?php

namespace models;

use lib\Model;

class CommentRelation extends Model
{
    protected $tbl_name = 'tbl_comment_relation';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'comment_id' => 'Комментарий',
            'parent_id' => 'Предок',
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



}