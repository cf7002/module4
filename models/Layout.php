<?php

namespace models;

use lib\Model;

class Layout extends Model
{
    /**
     * @return array|bool
     */
    public function fillMenu()
    {
        $categories = new Category($this->db);
        $list = $categories->getListCategories('priority', 'DESC');

        return $list;
    }

    /**
     * @return array|bool
     */
    public function hotTags()
    {
        $tag_relations = new TagRelation($this->db);
        $hot_tags = $tag_relations->getHotTags();

        return $hot_tags;
    }

    /**
     * @param $min
     * @param $max
     *
     * @return int
     */
    private function generator($min, $max)
    {
        return rand($min, $max);
    }

    /**
     * @throws \Exception
     */
    public function blockAd()
    {
        $advt = new Advt($this->db);
        $advt_detail = $advt->getMinMax();

        $arr_check = [];
        $arr_ads = [];
        do {
            do {
                do {
                    $num = $this->generator($advt_detail[0]['start'], $advt_detail[0]['end']);
                } while (key_exists($num, $arr_check));

                $ad = $advt->findById($num);

            } while (empty($ad));

            $arr_ads[] = $ad[0];
            $arr_check[$num] = $num;
        } while (count($arr_check) < 8);

        return $arr_ads;
    }

    /**
     * @return array|bool
     */
    public function topUsers()
    {
        $comments = new Comment($this->db);

        return $comments->getCountComments4Users();
    }
}