<?php

namespace controllers;

use lib\Controller;
use lib\Request;
use lib\UploadedFile;
use models\Category;
use models\Comment;
use models\Image;
use models\News;
use models\NewsForm;
use models\Tag;
use models\TagRelation;
use models\User;

class NewsController extends Controller
{
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->model = new News($this->getDB());
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function viewAction()
    {
        $params = $this->router->getParams();
        $id = intval($params[0]);

        if (empty($id)) {
            throw new \Exception('Несуществующая новость.');
        }

        $news = new News($this->getDB());
        $news_detail = $news->findById($id);
        $news->incCounter($id);

        $tags = new TagRelation($this->getDB());

        if (!User::isUser()) {
            $news_detail[0]['content'] = $news->getPartialContent($news_detail[0]['content']);
        }

        $comments = new Comment($this->getDB());
        $list_comments = $comments->getListComments($id);
        $list_comments = $comments->rebuildListComments($list_comments);
        $total_comments = key($list_comments);
        $hierarchy = $comments->getHierarchy($list_comments[$total_comments]);

        return $this->render('', [
            'news' => $news_detail,
            'tag_list' => $tags->getTagListByNews($id),
            'total_comments' => $total_comments,
            'list_comments' => $list_comments[$total_comments],
            'hierarchy' => $hierarchy
        ]);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function admin_indexAction()
    {
        $list_news = $this->model->getAllTable($this->model->getTableName());

        return $this->render('', [
            'labels' => $this->model->attributeNames(),
            'news' => $list_news,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws \Exception
     */
    public function admin_createAction(Request $request)
    {
        $categories = new Category($this->getDB());
        $category_list = $categories->getListCategories('title');

        $tags = new Tag($this->getDB());
        $tag_list = $tags->getListTags();

        if ($request->post('submit')) {
            $news = new NewsForm(
                $request->post('title'),
                $request->post('content'),
                $request->post('is_analytic'),
                $request->post('status'),
                $request->post('version')
            );

            do {
                // валидация пользовательских данных
                if (!$news->isValid()) {
                    break;
                }

                if (empty($categories->findById($request->post('category_id')))) {
                    $this->setFlash('Ошибка категории.', 'danger');
                    break;
                }
                $news->category_id = $request->post('category_id');

                // добавление новости в БД
                $date = date('U');
                $sql_result = $this->model->createNews([
                    $news->category_id,
                    $news->title,
                    $news->content,
                    $news->is_analytic,
                    $news->status,
                    $date,
                    $date,
                    $news->version
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка добавления новости.', 'danger');
                    break;
                }
                $this->setFlash('Новость успешно добавлена.', 'success');
                // сохраняем тэги
                $find_tags = $tags->findByIdArray($request->post('tags'));
                foreach ($find_tags as $tag) {
                    $data4tag[] = [$sql_result, $tag['id'], $date];
                }
                if (!empty($data4tag)) {
                    $tag_relation = new TagRelation($this->getDB());
                    $tag_relation->createTagRelation($data4tag);
                }
                // сохраняем изображения
                if (!empty($request->getFiles())) {
                    /** @var UploadedFile $file */
                    foreach ($request->getFiles() as $file) {
                        $data4image[] = [$sql_result, $file->getName(), $date];
                    }
                    if (!empty($data4image)) {
                        $image = new Image($this->getDB());
                        $image->createImage($data4image);
                    }
                }
                $this->redirect('/admin/news');

            } while (0);
        }

        $news = new NewsForm();

        return $this->render('form', [
            'news' => (array) $news,
            'categories' => $category_list,
            'tags' => $tag_list,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws \Exception
     */
    public function admin_updateAction(Request $request)
    {
        $params = $this->router->getParams();
        $id = intval($params[0]);
        if (empty($id)) {
            $this->setFlash('Неизвестная новость.', 'warning');

            $this->redirect('/admin/news');
        }

        $news = $this->model->findById($id);

        if (empty($news)) {
            $this->setFlash('Неизвестная новость.', 'warning');

            $this->redirect('/admin/news');
        }
        $news_id = $news[0]['id'];

        $categories = new Category($this->getDB());
        $category_list = $categories->getListCategories('title');

        $tags = new Tag($this->getDB());
        $tag_list = $tags->getListTags();

        $tag_relation = new TagRelation($this->getDB());
        $arr = $tag_relation->findByNews($news_id);
        $news_tags = [];
        foreach ($arr as $item) {
            $news_tags[$item['id']] = $item['tag_id'];
        }
        unset($arr);

        $image = new Image($this->getDB());
        $news_images = $image->findByNews($news_id);

        if ($request->post('submit')) {
            $form = new NewsForm(
                $request->post('title'),
                $request->post('content'),
                $request->post('is_analytic'),
                $request->post('status'),
                $request->post('version')
            );

            do {
                // валидация пользовательских данных
                if (!$form->isValid()) {
                    break;
                }

                if (empty($categories->findById($request->post('category_id')))) {
                    $this->setFlash('Ошибка категории.', 'danger');
                    break;
                }
                $form->category_id = $request->post('category_id');

                $date = date('U');
                $sql_result = $this->model->updateNews([
                    $form->category_id,
                    $form->title,
                    $form->content,
                    $form->is_analytic,
                    $form->status,
                    $date,
                    $form->version,
                    $news_id
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка обновления новости.', 'danger');
                    break;
                }
                $this->setFlash('Новость успешно обновлена.', 'success');

                // сохраняем тэги
                $incoming = $request->post('tags');
                sort($incoming);

                $tags_add = array_diff($incoming, $news_tags);
                if (!empty($tags_add)) {
                    $find_tags = $tags->findByIdArray($tags_add);
                    // добавляем в базу
                    foreach ($find_tags as $tag) {
                        $data4tag[] = [$news_id, $tag['id'], $date];
                    }
                    $tag_relation->createTagRelation($data4tag);
                }

                $tags_del = array_diff($news_tags, $incoming);
                if (!empty($tags_del)) {
                    // удаляем из базы
                    foreach ($tags_del as $key => $value) {
                        $tag_relation->deleteTagRelation([$key]);
                    }
                }

                // сохраняем изображения
                if (!empty($request->getFiles())) {
                    /** @var UploadedFile $file */
                    foreach ($request->getFiles() as $file) {
                        $data4image[] = [$news_id, $file->getName(), $date];
                    }
                    if (!empty($data4image)) {
                        $image = new Image($this->getDB());
                        $image->createImage($data4image);
                    }
                }

                $this->redirect('/admin/news');

            } while (0);
        }

        return $this->render('form', [
            'news' => $news[0],
            'categories' => $category_list,
            'tags' => $tag_list,
            'news_tags' => $news_tags,
            'news_images' => $news_images
        ]);
    }

    /**
     * @throws \Exception
     */
    public function admin_deleteAction()
    {
        $params = $this->router->getParams();
        $category = $this->model->findById($params[0]);

        if (empty($category)) {
            $this->setFlash('Неизвестная категории.', 'warning');

            $this->redirect('/admin/category');
        }

        $sql_result = $this->model->deleteCategory([
            $category[0]['id']
        ]);

        if ($sql_result) {
            $this->setFlash('Категория успешно удалена.', 'success');
        } else {
            $this->setFlash('Ошибка удаления категории.', 'danger');
        }

        $this->redirect('/admin/category');
    }

    /**
     * @param Request $request
     */
    public function admin_deleteImageAction(Request $request)
    {
        $params = $this->router->getParams();

        $image = new Image($this->getDB());
        $image->deleteImage($params);

        $this->redirect($request->server('HTTP_REFERER'));
    }
}