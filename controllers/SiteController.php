<?php

namespace controllers;

use lib\Config;
use lib\Controller;
use models\Category;
use models\News;

class SiteController extends Controller
{
    /**
     * @return string
     *
     * @throws \Exception
     */
    public function indexAction()
    {
        $categories = new Category($this->getDB());
        $category_list = $categories->getListCategories('priority', 'DESC');

        $news = new News($this->getDB());
        $hot_news = $news->getHotNews();
        //формируем ленту новостей
        $result = [];
        foreach ($category_list as $category) {
            $result[$category['id']]['title'] = $category['title'];
            $news_list = $news->getListNews($category['id'], 'DESC', Config::get('items_per_page'));
            foreach ($news_list as $item) {
                $result[$category['id']]['news'][$item['id']] = $item['title'];
            }
        }

        return $this->render('',[
            'hot_news' => $hot_news,
            'news_feed' => $result,
        ]);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function admin_indexAction()
    {

        return $this->render('');
    }
}