<?php

namespace models;

use lib\Model;

class Advt extends Model
{
    protected $tbl_name = 'tbl_advertisements';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'title' => 'Название',
            'price' => 'Цена',
            'vendor' => 'Продавец',
            'href' => 'Веб-адрес',
            'rating' => 'Рейтинг',
            'count' => 'Счетчик',
            'status' => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
            'version' => 'Версия',
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
    public function createAdvt(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name}
          (title, price, vendor, href, rating, `count`, status, created_at, updated_at, version) values (?,?,?,?,?,?,?,?,?,?)";

        return $this->db->insert($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function updateAdvt(array $data)
    {
        $sql = "UPDATE {$this->tbl_name}
          SET title = ?, price = ?, vendor = ?, href = ?, rating = ?, status = ?, updated_at = ?, version = ? WHERE id = ?";

        return $this->db->update($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function deleteAdvt(array $data)
    {
        $sql = "DELETE FROM {$this->tbl_name} WHERE id = ?";

        return $this->db->delete($sql, $data);
    }

    /**
     * @param $id
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function findById($id)
    {
        $id = intval($id);

        if (!$id) {
            throw new \Exception('Некорректный ID');
        }
        $sql = "SELECT * FROM {$this->tbl_name} WHERE id = ?";

        return $this->db->select($sql, [$id]);
    }

    /**
     * @return array|bool
     */
    public function getMinMax()
    {
        $sql = "SELECT MIN(id) AS start, MAX(id) AS end FROM {$this->tbl_name}";

        return $this->db->select($sql);
    }

}