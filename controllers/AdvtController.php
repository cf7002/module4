<?php

namespace controllers;

use lib\Controller;
use lib\Request;
use models\Advt;
use models\AdvtForm;

class AdvtController extends Controller
{
    /**
     * AdvtController constructor.
     */
    public function __construct()
    {
        $this->model = new Advt($this->getDB());
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function admin_indexAction()
    {
        $list_advt = $this->model->getAllTable($this->model->getTableName());

        return $this->render('', [
            'labels' => $this->model->attributeNames(),
            'advt' => $list_advt,
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
            $advt = new AdvtForm(
                $request->post('title'),
                $request->post('price'),
                $request->post('vendor'),
                $request->post('href'),
                $request->post('rating'),
                $request->post('status'),
                $request->post('version')
            );

            do {
                // валидация пользовательских данных
                if (!$advt->isValid()) {
                    break;
                }
                // добавление рекламы в БД
                $date = date('U');
                $sql_result = $this->model->createAdvt([
                    $advt->title,
                    $advt->price * 100,
                    $advt->vendor,
                    $advt->href,
                    $advt->rating,
                    0,
                    $advt->status,
                    $date,
                    $date,
                    $advt->version
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка добавления объявления.', 'danger');
                    break;
                }
                $this->setFlash('Объявление успешно добавлено.', 'success');

                $this->redirect('/admin/advt');

            } while (0);
        }
        $advt = new AdvtForm();

        return $this->render('form', [
            'advt' => (array) $advt,
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
            $this->setFlash('Неверный идентификатор рекламы.', 'warning');

            $this->redirect('/admin/advt');
        }

        $advt = $this->model->findById($id);
        if (empty($advt)) {
            $this->setFlash('Неверный идентификатор рекламы.', 'warning');

            $this->redirect('/admin/advt');
        }

        if ($request->post('submit')) {
            do {
                $form = new AdvtForm(
                    $request->post('title'),
                    $request->post('price'),
                    $request->post('vendor'),
                    $request->post('href'),
                    $request->post('rating'),
                    $request->post('status'),
                    $request->post('version')
                );

                // валидация пользовательских данных
                if (!$form->isValid()) {
                    break;
                }
                // добавление категории в БД
                $sql_result = $this->model->updateAdvt([
                    $form->title,
                    $form->price * 100,
                    $form->vendor,
                    $form->href,
                    $form->rating,
                    $form->status,
                    date('U'),
                    $form->version,
                    $advt[0]['id']
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка сохранения рекламы.', 'danger');
                    break;
                }
                $this->setFlash('Реклама успешно обновлена.', 'success');

                $this->redirect('/admin/advt');

            } while (0);
        }

        return $this->render('form', [
            'advt' => $advt[0],
        ]);
    }
}