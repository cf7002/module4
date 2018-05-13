<?php

namespace controllers;

use lib\Config;
use lib\Controller;
use lib\Pagination;
use lib\Request;
use models\Category;
use models\Tag;
use models\TagRelation;

class SearchController extends Controller
{
    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $categories = new Category($this->getDB());
        $tags = new Tag($this->getDB());

        if ($request->post('submit')) {

            var_dump($request);
            die;
        }

        return $this->render('', [
            'categories' => $categories->getListCategories('title'),
            'tags' => $tags->getListTags(),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function tagAction()
    {
        $params = $this->router->getParams();
        $id = intval($params[0]);

        if (empty($id)) {
            throw new \Exception('Несуществующий тэг.');
        }

        $tag = new Tag($this->getDB());

        $tag_relation = new TagRelation($this->getDB());
        // пагинация
        $count_news = $tag_relation->countTagsById($id);
        $num = Config::get('items_per_page');
        $page = empty($params[1]) ? 1 : intval($params[1]);
        $start = $page == 1 ? 0 : $page * $num - $num;
        $limit = "$start, $num";
        $pagination = new Pagination(array(
            'itemsCount' => $count_news[0]['cnt'],
            'itemsPerPage' => $num,
            'currentPage' => $page
        ));

        return $this->render('list', [
            'tag' => $tag->findById($id),
            'news_list' => $tag_relation->getNewsListByTag($id, 'DESC', $limit),
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function titleAction(Request $request)
    {
        $needle = trim(strip_tags($request->post('search')));

        $tag = new Tag($this->getDB());
        $tag_id = $tag->findByTitle($needle);

        if (empty($tag_id)) {
            return $this->render('not_found');
        }

        $tag_relation = new TagRelation($this->getDB());
        // пагинация
        $count_news = $tag_relation->countTagsById($tag_id[0]['id']);
        $num = Config::get('items_per_page');
        $page = empty($params[1]) ? 1 : intval($params[1]);
        $start = $page == 1 ? 0 : $page * $num - $num;
        $limit = "$start, $num";
        $pagination = new Pagination(array(
            'itemsCount' => $count_news[0]['cnt'],
            'itemsPerPage' => $num,
            'currentPage' => $page
        ));

        return $this->render('list', [
            'tag' => $tag->findById($tag_id[0]['id']),
            'news_list' => $tag_relation->getNewsListByTag($tag_id[0]['id'], 'DESC', $limit),
            'pagination' => $pagination
        ]);
    }
}
