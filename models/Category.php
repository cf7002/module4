<?php

namespace models;

use lib\Model;

class Category extends Model
{
    protected $tbl_name = 'tbl_categories';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'title' => 'Название',
            'priority' => 'Очередность',
            'is_moderated' => 'Модерирование',
            'sub_category' => 'Подкатегория',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
            'version' => 'Версия',
            'status' => 'Статус',
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
    public function createCategory(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} 
          (title, priority, is_moderated, sub_category, status, created_at, updated_at, version)
          values (?,?,?,?,?,?,?,?)";

        return $this->db->insert($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function updateCategory(array $data)
    {
        $sql = "UPDATE {$this->tbl_name} 
          SET title = ?, priority = ?, is_moderated = ?, sub_category = ?, status = ?, updated_at = ?, version = ?
          WHERE id = ?";

        return $this->db->update($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function deleteCategory(array $data)
    {
        $sql = "DELETE FROM {$this->tbl_name} WHERE id = ?";

        return $this->db->delete($sql, $data);
    }

    /**
     * @param string $id
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
     * @param string $order_field
     * @param string $direction
     * @param int|null $limit
     * @return array|bool
     */
    public function getListCategories($order_field, $direction = 'ASC', $limit = null)
    {
        $limit = is_null($limit) ? '' : 'LIMIT ' . $limit;
        $sql = "SELECT id, title, sub_category FROM {$this->tbl_name} 
            WHERE status = ? ORDER BY {$order_field} {$direction} {$limit}";

        return $this->db->select($sql, [$this::STATUS_ACTIVE]);
    }

}