<?php

namespace models;

use lib\Model;

class News extends Model
{
    protected $tbl_name = 'tbl_news';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'category_id' => 'Категория',
            'title' => 'Название',
            'content' => 'Содержание',
            'is_analytic' => 'Аналитика',
            'view_counter' => 'Счетчик',
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
    public function createNews(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name}
          (category_id, title, content, is_analytic, status, created_at, updated_at, version) VALUES (?,?,?,?,?,?,?,?)";

        return $this->db->insert($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function updateNews(array $data)
    {
        $sql = "UPDATE {$this->tbl_name}
          SET category_id = ?, title = ?, content = ?, is_analytic = ?, status = ?, updated_at = ?, version = ?
          WHERE id = ?";

        return $this->db->update($sql, $data);
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function deleteNews(array $data)
    {
        $sql = "DELETE FROM {$this->tbl_name} WHERE id = ?";

        return $this->db->delete($sql, $data);
    }

    /**
     * @param $id
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function incCounter($id)
    {
        $id = intval($id);
        if (!$id) {
            throw new \Exception('Некорректный ID');
        }

        $sql = "UPDATE {$this->tbl_name}
          SET view_counter = view_counter + 1
          WHERE id = ?";

        return $this->db->update($sql, [$id]);
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
        $sql = "SELECT news.*, img.file_name FROM {$this->tbl_name} news 
            LEFT JOIN tbl_images img ON news.id = img.news_id WHERE news.id = ?";

        return $this->db->select($sql, [$id]);
    }

    /**
     * @return array|bool
     */
    public function getHotNews()
    {
        $sql = "SELECT news.id, news.title, img.file_name FROM {$this->tbl_name} news
            LEFT JOIN tbl_images img ON news.id = img.news_id WHERE status = ? 
            ORDER BY news.created_at DESC LIMIT 4";

        return $this->db->select($sql, [$this::STATUS_ACTIVE]);
    }

    /**
     * @param $category_id
     * @param string $direction
     * @param null $limit
     *
     * @return array|bool
     */
    public function getListNews($category_id, $direction = 'ASC', $limit = null)
    {
        $limit = is_null($limit) ? '' : 'LIMIT ' . $limit;

        $sql = "SELECT id, title FROM {$this->tbl_name} 
            WHERE status = ? AND category_id = {$category_id} 
            ORDER BY created_at {$direction} {$limit}";

        return $this->db->select($sql, [$this::STATUS_ACTIVE]);
    }

    /**
     * @param $category_id
     * @param string $direction
     * @param null $limit
     *
     * @return array|bool
     */
    public function getListAnalytics($category_id, $direction = 'ASC', $limit = null)
    {
        $limit = is_null($limit) ? '' : 'LIMIT ' . $limit;

        $sql = "SELECT id, title FROM {$this->tbl_name} 
            WHERE status = ? AND is_analytic = ? AND category_id = {$category_id} 
            ORDER BY created_at {$direction} {$limit}";

        return $this->db->select($sql, [$this::STATUS_ACTIVE, 1]);
    }

    /**
     * @param $category_id
     *
     * @return array|bool
     */
    public function countNewsByCategory($category_id = null)
    {
        $add_condition = empty($category_id) ? '' : "AND category_id = {$category_id}";

        $sql = "SELECT COUNT(*) AS cnt FROM {$this->tbl_name} 
            WHERE status = ? {$add_condition}";

        return $this->db->select($sql, [$this::STATUS_ACTIVE]);
    }
    /**
     * @param $category_id
     *
     * @return array|bool
     */
    public function countAnalyticByCategory($category_id = null)
    {
        $add_condition = empty($category_id) ? '' : "AND category_id = {$category_id}";

        $sql = "SELECT COUNT(*) AS cnt FROM {$this->tbl_name} 
            WHERE status = ? AND is_analytic = ? {$add_condition}";

        return $this->db->select($sql, [$this::STATUS_ACTIVE, 1]);
    }

    /**
     * @param $content
     *
     * @return string
     */
    public function getPartialContent($content)
    {
        $pattern = '/[А-ЯA-Z].{15,}?(\.|\!|\?)(?=\ |\r|\n|$)/';
        $arr = [];
        preg_match_all($pattern, $content, $arr, PREG_SET_ORDER);

        $str = '';
        for ($i = 0; $i < 5; $i++) {
            $str .= $arr[$i][0] . ' ';
        }

        $result = trim($str, " .\t\n\r\0\x0B") . '...' . '<a href="/user/login">Читать далее&gt;&gt;&gt;</a>';
        unset($arr, $str);

        return $result;
    }
}