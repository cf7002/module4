<?php

namespace controllers;

use lib\Config;
use lib\Controller;
use lib\Pagination;
use lib\Request;
use models\Category;
use models\CategoryForm;
use models\News;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->model = new Category($this->getDB());
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function viewAction()
    {
        $params = $this->router->getParams();
        $id = intval($params[0]);

        if (empty($id)) {
            throw new \Exception('Неверная категория.');
        }

        $category = new Category($this->getDB());
        $detail = $category->findById($id);

        $news = new News($this->getDB());
        // пагинация
        $count_news = $news->countNewsByCategory($detail[0]['id']);
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
            'category' => $detail[0],
            'news' => $news->getListNews($detail[0]['id'], 'DESC', $limit),
            'pagination' => $pagination
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function analyticAction()
    {
        $params = $this->router->getParams();
        $id = intval($params[0]);

        if (empty($id)) {
            throw new \Exception('Неверная категория.');
        }

        $category = new Category($this->getDB());
        $detail = $category->findById($id);

        $news = new News($this->getDB());
        // пагинация
        $count_news = $news->countAnalyticByCategory($detail[0]['id']);
        $num = Config::get('items_per_page');
        $page = empty($params[1]) ? 1 : intval($params[1]);
        $start = $page == 1 ? 0 : $page * $num - $num;
        $limit = "$start, $num";
        $pagination = new Pagination(array(
            'itemsCount' => $count_news[0]['cnt'],
            'itemsPerPage' => $num,
            'currentPage' => $page
        ));

        return $this->render('list_a', [
            'category' => $detail[0],
            'news' => $news->getListAnalytics($detail[0]['id'], 'DESC', $limit),
            'pagination' => $pagination
        ]);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function admin_indexAction()
    {
        $list_categories = $this->model->getAllTable($this->model->getTableName());

        return $this->render('', [
            'labels' => $this->model->attributeNames(),
            'categories' => $list_categories,
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
        if ($request->post('submit')) {
            $category = new CategoryForm(
                $request->post('title'),
                $request->post('priority'),
                $request->post('is_moderated'),
                $request->post('sub_category'),
                $request->post('status'),
                $request->post('version')
            );

            do {
                // валидация пользовательских данных
                if (!$category->isValid()) {
                    break;
                }
                // проверка уникальности названия
                if (!$this->model->isUnique($this->model->getTableName(), 'title', $category->title)) {
                    $this->setFlash("Категория '{$category->title}' уже существует.", 'warning');
                    break;
                }
                // добавление категории в БД
                $date = date('U');
                $sql_result = $this->model->createCategory([
                    $category->title,
                    $category->priority,
                    $category->is_moderated,
                    $category->sub_category,
                    $category->status,
                    $date,
                    $date,
                    $category->version
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка добавления категории.', 'danger');
                    break;
                }
                $this->setFlash('Категория успешно добавлена.', 'success');

                $this->redirect('/admin/category');

            } while (0);
        }

        $category = new CategoryForm();

        return $this->render('form', [
            'category' => (array) $category,
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
            $this->setFlash('Неизвестная категории.', 'warning');

            $this->redirect('/admin/category');
        }

        $category = $this->model->findById($id);
        if (empty($category)) {
            $this->setFlash('Неизвестная категории.', 'warning');

            $this->redirect('/admin/category');
        }

        if ($request->post('submit')) {
            do {
                $form = new CategoryForm(
                    $request->post('title'),
                    $request->post('priority'),
                    $request->post('is_moderated'),
                    $request->post('sub_category'),
                    $request->post('status'),
                    $request->post('version')
                );

                // валидация пользовательских данных
                if (!$form->isValid()) {
                    break;
                }
                // добавление категории в БД
                $sql_result = $this->model->updateCategory([
                    $form->title,
                    $form->priority,
                    $form->is_moderated,
                    $form->sub_category,
                    $form->status,
                    date('U'),
                    $form->version,
                    $category[0]['id']
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка сохранения категории.', 'danger');
                    break;
                }
                $this->setFlash('Категория успешно обновлена.', 'success');

                $this->redirect('/admin/category');

            } while (0);
        }

        return $this->render('form', [
            'category' => $category[0],
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
}