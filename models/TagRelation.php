<?php

namespace models;

use lib\Model;

class TagRelation extends Model
{
    protected $tbl_name = 'tbl_tag_relations';

    /**
     * @return array
     */
    public function attributeNames()
    {
        return [
            'id' => 'Ключ',
            'news_id' => 'Новость',
            'tag_id' => 'Тэг',
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
    public function createTagRelation(array $data)
    {
        $sql = "INSERT INTO {$this->tbl_name} 
          (news_id, tag_id, created_at) values (?,?,?)";

        $result = $this->db->saveBatch($sql, $data);

        return $result;
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function deleteTagRelation(array $data)
    {
        $sql = "DELETE FROM {$this->tbl_name} WHERE id = ?";

        $result = $this->db->delete($sql, $data);

        return $result;
    }

    /**
     * @param $field_name
     * @param $id
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    private function findByField($field_name, $id)
    {
        $id = intval($id);
        if (!$id) {
            throw new \Exception('Некорректный ID');
        }

        $sql = "SELECT id, news_id, tag_id FROM {$this->tbl_name} 
            WHERE {$field_name} = ? ORDER BY created_at DESC";

        return $this->db->select($sql, [$id]);
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
        return $this->findByField('news_id', $news_id);
    }

    /**
     * @param $tag_id
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function findByTag($tag_id)
    {
        return $this->findByField('tag_id', $tag_id);
    }

    /**
     * @return array|bool
     */
    public function getRatingTags()
    {
        $sql = "SELECT tag_id, t.title, COUNT(r.id) AS cnt FROM tbl_tag_relations r 
            LEFT JOIN tbl_tags t ON r.tag_id = t.id
            GROUP BY tag_id ORDER BY t.title";

        return $this->db->select($sql);
    }

    /**
     * @return array|bool
     */
    public function getHotTags()
    {
        $rating = $this->getRatingTags();

        if (!empty($rating)) {
            $min = null;
            $max = null;
            foreach ($rating as $tag) {
                if (!isset($min)) {
                    $min = $tag['cnt'];
                    continue;
                }
                if (!isset($max)) {
                    $max = $tag['cnt'];
                    continue;
                }

                if ($tag['cnt'] < $min) {
                    $min = $tag['cnt'];
                    continue;
                }

                if ($tag['cnt'] > $max) {
                    $max = $tag['cnt'];
                    continue;
                }
            }

            $k = (int)(($max-$min)/3);
            foreach ($rating as $key => $tag) {
                $rating[$key]['index'] = 3 + round(($max - $tag['cnt'])/$k);
            }
        }

        return $rating;
    }

    /**
     * @param $tag_id
     * @param string $direction
     * @param $limit
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function getNewsListByTag($tag_id, $direction = 'ASC', $limit = null)
    {
        $id = intval($tag_id);
        if (!$id) {
            throw new \Exception('Некорректный ID');
        }

        $limit = is_null($limit) ? '' : 'LIMIT ' . $limit;

        $status = self::STATUS_ACTIVE;
        $sql = "SELECT news.id, news.title FROM {$this->tbl_name} r 
            LEFT JOIN tbl_news news ON r.news_id = news.id WHERE tag_id = ? AND news.status = {$status}
            ORDER BY r.created_at {$direction} {$limit}";

        return $this->db->select($sql, [$id]);
   }

    /**
     * @param $news_id
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function getTagListByNews($news_id)
    {
        $id = intval($news_id);
        if (!$id) {
            throw new \Exception('Некорректный ID');
        }

        $sql = "SELECT tag.id, tag.title FROM {$this->tbl_name} r 
            LEFT JOIN tbl_tags tag ON tag_id = tag.id WHERE news_id = ?
            ORDER BY tag.title";

        return $this->db->select($sql, [$id]);
    }


    /**
     * @param $tag_id
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function countTagsById($tag_id)
    {
        $id = intval($tag_id);
        if (!$id) {
            throw new \Exception('Некорректный ID');
        }

        $sql = "SELECT COUNT(*) AS cnt FROM {$this->tbl_name} 
            WHERE tag_id = ?";

        return $this->db->select($sql, [$id]);
    }


}