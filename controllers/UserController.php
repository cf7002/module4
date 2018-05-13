<?php

namespace controllers;

use lib\Controller;
use lib\Request;
use lib\Session;
use models\LoginForm;
use models\RegisterForm;
use models\Subscribe;
use models\SubscribeForm;
use models\User;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model = new User($this->getDB());
    }

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        /** @var Session $session */
        $session = Session::getInstance();

        if ($request->post('submit')) {
            $user = new RegisterForm(
                $request->post('email'),
                $request->post('password'),
                $request->post('first_name'),
                $request->post('last_name'),
                $request->post('nick_name'),
                $request->post('version')
            );

            do {
                // валидация пользовательских данных
                if (!$user->isValid()) {
                    break;
                }
                // проверка уникальности регистрируемого адреса
                if (!$this->model->isUnique($this->model->getTableName(), 'email', $user->email)) {
                    $this->setFlash("Пользователь с адресом '{$user->email}' уже зарегистрирован.", 'warning');
                    break;
                }
                // проверка уникальности регистрируемого псевдонима
                if (!$this->model->isUnique($this->model->getTableName(), 'nick_name', $user->nick_name)) {
                    $this->setFlash("Пользователь с псевдонимом '{$user->nick_name}' уже зарегистрирован.", 'warning');
                    break;
                }
                // добавление пользователя в БД
                $date = date('U');
                $sql_result = $this->model->createUser([
                    $user->email,
                    password_hash($user->password, PASSWORD_DEFAULT),
                    $user->first_name,
                    $user->last_name,
                    $user->nick_name,
                    User::ROLE_USER,
                    User::STATUS_ACTIVE,
                    $date,
                    $date,
                    $user->version
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка добавления пользователя.', 'danger');
                    break;
                }
                $this->setFlash('Пользователь успешно добавлен.', 'success');

                $this->redirect($session->get('ref', true));

            } while (0);
        }
        $session->store('ref', $request->server('HTTP_REFERER'));

        return $this->render('', []);
    }

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws \Exception
     */
    public function loginAction(Request $request)
    {
        /** @var Session $session */
        $session = Session::getInstance();

        if ($request->post('submit')) {
            $user = new LoginForm(
                $request->post('email'),
                $request->post('password')
            );

            do {
                // валидация пользовательских данных
                if (!$user->isValid()) {
                    break;
                }
                // поиск пользователя в БД и его аутентификация
                $sql_result = $this->model->findByEmail([$user->email]);
                if (!$sql_result || !password_verify($user->password, $sql_result['password'])) {
                    $this->setFlash("Ошибка аутентификации.", 'warning');
                    break;
                }
                // создание сессионной переменной-флага с ролью пользователя
                $session->set('user', (int) $sql_result['id']);
                $session->set('role', (int) $sql_result['role']);
                $this->setFlash("Аутентификации прошла успешно.", 'success');

                $ref = is_null($session->get('ref')) ? '/' : $session->get('ref', true);
                $to = (int) $sql_result['role'] === User::ROLE_ADMIN ? '/admin' : $ref;
                $this->redirect($to);

            } while (0);
        }
        $session->set('ref', $request->server('HTTP_REFERER'));

        return $this->render('', []);
    }

    /**
     * Logout method
     *
     * @param Request $request
     */
    public function logoutAction(Request $request)
    {
        /** @var Session $session */
        $session = Session::getInstance();
        $session->get('user', true);
        $session->get('role', true);

        $this->redirect($request->server('HTTP_REFERER'));
    }

    /**
     * @param Request $request
     */
    public function subscribeAction(Request $request)
    {
        /** @var Session $session */
        $session = Session::getInstance();

        if ($request->post('submit')) {
            $user = new SubscribeForm(
                $request->post('email'),
                $request->post('first_name'),
                $request->post('last_name')
            );

            do {
                // валидация пользовательских данных
                if (!$user->isValid()) {
                    break;
                }
                $this->model = new Subscribe($this->getDB());
                // проверка уникальности регистрируемого адреса
                if (!$this->model->isUnique($this->model->getTableName(), 'email', $user->email)) {
                    $this->setFlash("Пользователь с адресом '{$user->email}' уже зарегистрирован.", 'warning');
                    break;
                }
                // добавление пользователя в БД
                $sql_result = $this->model->createSubscriber([
                    $user->email,
                    $user->first_name,
                    $user->last_name
                ]);

                if (!$sql_result) {
                    $this->setFlash('Ошибка добавления пользователя.', 'danger');
                    break;
                }
                $this->setFlash('Пользователь успешно добавлен.', 'success');

                $session->set('isSubscribe', 1);

                $this->redirect($request->server('HTTP_REFERER'));
            } while (0);
        }
    }
}