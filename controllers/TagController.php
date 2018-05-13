<?php

namespace controllers;

use lib\Controller;
use lib\Request;
use models\Tag;
use models\TagForm;

class TagController extends Controller
{
    /**
     * TagController constructor.
     */
    public function __construct()
    {
        $this->model = new Tag($this->getDB());
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function admin_indexAction()
    {
        $list_tags = $this->model->getAllTable($this->model->getTableName());

        return $this->render('', [
            'labels' => $this->model->attributeNames(),
            'tags' => $list_tags,
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
            $tag = new TagForm(
                $request->post('title'),
                $request->post('status'),
                $request->post('version')
            );

            do {
                // валидация пользовательских данных
                if (!$tag->isValid()) {
                    break;
                }
                // проверка уникальности названия
                if (!$this->model->isUnique($this->model->getTableName(), 'title', $tag->title)) {
                    $this->setFlash("Тэг '{$tag->title}' уже существует.", 'warning');
                    break;
                }
                // добавление тэга в БД
                $date = date('U');
                $sql_result = $this->model->createTag([
                    $tag->title,
                    $tag->status,
                    $date,
                    $date,
                    $tag->version
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка добавления тэга.', 'danger');
                    break;
                }
                $this->setFlash('Тэг успешно добавлен.', 'success');

                $this->redirect('/admin/tag');

            } while (0);
        }
        $tag = new TagForm();

        return $this->render('form', [
            'tag' => (array) $tag,
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
            $this->setFlash('Неизвестный тэг.', 'warning');

            $this->redirect('/admin/tag');
        }

        $tag = $this->model->findById($id);
        if (empty($tag)) {
            $this->setFlash('Неизвестный тэг.', 'warning');

            $this->redirect('/admin/tag');
        }

        if ($request->post('submit')) {
            do {
                $form = new TagForm(
                    $request->post('title'),
                    $request->post('status'),
                    $request->post('version')
                );

                // валидация пользовательских данных
                if (!$form->isValid()) {
                    break;
                }
                // добавление категории в БД
                $sql_result = $this->model->updateTag([
                    $form->title,
                    $form->status,
                    date('U'),
                    $form->version,
                    $tag[0]['id']
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка добавления тэга.', 'danger');
                    break;
                }
                $this->setFlash('Тэг успешно обновлен.', 'success');

                $this->redirect('/admin/tag');

            } while (0);
        }

        return $this->render('form', [
            'tag' => $tag[0],
        ]);
    }

    /**
     * @throws \Exception
     */
    public function admin_deleteAction()
    {
        $params = $this->router->getParams();
        $tag = $this->model->findById($params[0]);

        if (empty($tag)) {
            $this->setFlash('Неизвестный тэг.', 'warning');

            $this->redirect('/admin/tag');
        }

        $sql_result = $this->model->deleteTag([
            $tag[0]['id']
        ]);

        if ($sql_result) {
            $this->setFlash('Тэг успешно удален.', 'success');
        } else {
            $this->setFlash('Ошибка удаления тэга.', 'danger');
        }

        $this->redirect('/admin/tag');
    }
}