<?php

namespace models;

use lib\Model;

class Image extends Model
{
    protected $tbl_name = 'tbl_images';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'news_id' => 'Новость',
            'file_name' => 'Имя файла',
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
     */
    public function createImage(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} 
          (news_id, file_name, created_at) values (?,?,?)";

        return $this->db->saveBatch($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function deleteImage(array $data)
    {
        $sql = "DELETE FROM {$this->tbl_name} WHERE id = ?";

        $result = $this->db->delete($sql, [$data[0]]);
        if ($result) {
            unlink(UPLOAD_DIR . $data[1]);
        }

        return $result;
    }

    /**
     * @param string $news_id
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function findByNews($news_id)
    {
        $id = intval($news_id);
        if (!$id) {
            throw new \Exception('Некорректный ID');
        }

        $sql = "SELECT id, file_name FROM {$this->tbl_name} 
            WHERE news_id = ?";

        return $this->db->select($sql, [$id]);
    }
}