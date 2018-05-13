<?php

namespace models;

use lib\Config;
use lib\DB;
use lib\Model;

class Tag extends Model
{
    protected $tbl_name = 'tbl_tags';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'title' => 'Название',
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
    public function createTag(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} 
          (title, status, created_at, updated_at, version) values (?,?,?,?,?)";

        return $this->db->insert($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function updateTag(array $data)
    {
        $sql = "UPDATE {$this->tbl_name} 
          SET title = ?, status = ?, updated_at = ?, version = ? WHERE id = ?";

        return $this->db->update($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function deleteTag(array $data)
    {
        $sql = "DELETE FROM {$this->tbl_name} WHERE id = ?";

        return $this->db->delete($sql, $data);
    }

    /**
     * @return array|bool
     */
    public function getListTags()
    {
        $sql = "SELECT id, title FROM {$this->tbl_name} WHERE status = ? ORDER BY title";

        return $this->db->select($sql, [$this::STATUS_ACTIVE]);
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
     * @param array $data
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function findByIdArray(array $data)
    {
        if (empty($data)) {
            throw new \Exception('Некорректный ID');
        }

        foreach ($data as $id) {
            $arr[] = intval($id);
        }
        sort($arr);
        $par = implode(',', $arr);

        $sql = "SELECT * FROM {$this->tbl_name} WHERE id IN ({$par})" ;

        return $this->db->select($sql);
    }

    /**
     * @param string $title
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function findByTitle($title)
    {
        if (empty($title)) {
            throw new \Exception('Некорректное имя тэга.');
        }
        $sql = "SELECT id, title FROM {$this->tbl_name} WHERE status = ? AND title LIKE ? LIMIT 1";

        return $this->db->select($sql, [$this::STATUS_ACTIVE, "%$title%"]);
    }

    /**
     * @return array|bool
     */
    public static function getArrayTag()
    {
        $params = Config::get('db');
        $sql = "SELECT title FROM tbl_tags WHERE status = ? ORDER BY title";

        $db = new DB($params);

        return $db->select($sql, [self::STATUS_ACTIVE]);
    }
}