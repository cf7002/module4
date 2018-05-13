<?php

namespace models;

use lib\Model;

class Comment extends Model
{
    protected $tbl_name = 'tbl_comments';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'news_id' => 'Новость',
            'parent_id' => 'Предок',
            'user_id' => 'Пользователь',
            'message' => 'Комментарий',
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
    public function createComment(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} 
          (news_id, parent_id, user_id, message, status, created_at)
          values (?,?,?,?,?,?)";

        return $this->db->insert($sql, $data);
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
        $sql = "SELECT * FROM {$this->tbl_name} 
            WHERE id = ?";

        return $this->db->select($sql, [$id]);
    }

    /**
     * @param $news_id
     *
     * @return array|bool
     */
    public function getListComments($news_id)
    {
        $sql = "SELECT c.*, u.nick_name 
            FROM {$this->tbl_name} c LEFT JOIN tbl_users u ON c.user_id = u.id 
            WHERE c.news_id = ? AND c.status = ?";

        return $this->db->select($sql, [$news_id, Model::STATUS_ACTIVE]);
    }

    /**
     * @return array|bool
     */
    public function getCountComments4Users()
    {
        $sql = "SELECT COUNT(c.id) AS cnt, c.user_id, u.nick_name
            FROM {$this->tbl_name} c LEFT JOIN tbl_users u ON c.user_id = u.id 
            WHERE c.status = ? GROUP BY c.user_id ORDER BY cnt DESC LIMIT 5";

        return $this->db->select($sql, [Model::STATUS_ACTIVE]);
    }

    /**
     * @param $user_id
     *
     * @param string $direction
     * @param null $limit
     * @return array|bool
     *
     * @throws \Exception
     */
    public function getAllCommentsByUser($user_id, $direction = 'ASC', $limit = null)
    {
        $limit = is_null($limit) ? '' : 'LIMIT ' . $limit;

        $sql = "SELECT id, news_id, message  FROM {$this->tbl_name} 
            WHERE user_id = ? AND status = ? ORDER BY created_at {$direction} {$limit}";

        return $this->db->select($sql, [$user_id, Model::STATUS_ACTIVE]);
    }

    /**
     * @param $user_id
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function getCountCommentsByUser($user_id)
    {
        $sql = "SELECT COUNT(*) AS cnt 
            FROM {$this->tbl_name} WHERE user_id = ? AND status = ?";

        return $this->db->select($sql, [$user_id, Model::STATUS_ACTIVE]);
    }

    public function getHotThemes()
    {
        
    }
    
    
    /**
     * @return array|bool
     */
    public function getCountVotesUp()
    {
        $sql = "SELECT comment_id, count(id) AS cnt 
            FROM `tbl_votes` 
            WHERE vote = 1 GROUP BY comment_id ORDER BY comment_id";

        $arr = $this->db->select($sql);
        $result = [];
        foreach ($arr as $item) {
            $result[$item['comment_id']] = $item['cnt'];
        }
        return $result;
    }

    /**
     * @return array|bool
     */
    public function getCountVotesDown()
    {
        $sql = "SELECT comment_id, count(id) AS cnt 
            FROM `tbl_votes` 
            WHERE vote = -1 GROUP BY comment_id ORDER BY comment_id";

        $arr = $this->db->select($sql);
        $result = [];
        foreach ($arr as $item) {
            $result[$item['comment_id']] = $item['cnt'];
        }
        return $result;
    }

    /**
     * @param array $comments
     *
     * @return array
     */
    public function rebuildListComments(array $comments)
    {
        $result = [];

        $vote_up = $this->getCountVotesUp();
        $vote_down = $this->getCountVotesDown();

        $total = 0;
        foreach ($comments as $comment) {
            $result[$comment['id']] = $comment;
            $result[$comment['id']]['vote_up'] = isset($vote_up[$comment['id']]) ? $vote_up[$comment['id']] : 0;
            $result[$comment['id']]['vote_down'] = isset($vote_down[$comment['id']]) ? $vote_down[$comment['id']] : 0;
            $total += $result[$comment['id']]['vote_up'];
            $total += $result[$comment['id']]['vote_down'];
        }

        return [$total => $result];
    }

    /**
     * @param array $arr
     *
     * @return array
     */
    public function getHierarchy(array $arr)
    {
        $relation = [];
        foreach ($arr as $item) {
            $relation[$item['id']] = $item['parent_id'];
        }
        krsort($relation);

        $result = [];
        foreach ($relation as $key => $value) {
            $t = $this->recur($relation, $key, '');
            $result[] = explode(';', trim($t, ';'));
        }
        krsort($result);

        return $result;
    }

    /**
     * @param $arr
     * @param $key
     * @param $res
     *
     * @return string
     */
    private function recur(&$arr, $key, $res)
    {
        if (isset($arr[$key])) {
            if ($key != $arr[$key]) {
                $res = $this->recur($arr, $arr[$key], $res);
            }
            return $res . $key . ';';
        }

        return null;
    }
}