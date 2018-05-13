<?php

namespace controllers;

use lib\Config;
use lib\Controller;
use lib\Pagination;
use lib\Request;
use lib\Session;
use models\Comment;
use models\CommentForm;
use models\User;
use models\Vote;

class CommentController extends Controller
{
    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->model = new Comment($this->getDB());
    }

    /**
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        /** @var Session $session */
        $session = Session::getInstance();
        $user = $session->get('user');

        do {
            if (empty($user)) {
                $this->setFlash('Неизвестный пользователь.', 'warning');
                break;
            }

            $comment = new CommentForm(
                $request->post('news_id'),
                $request->post('parent_id'),
                $request->post('user_id'),
                $request->post('message')
            );

            // валидация пользовательских данных
            if (!$comment->isValid()) {
                break;
            }

            // добавление категории в БД
            $date = date('U');
            $sql_result = $this->model->createComment([
                $comment->news_id,
                $comment->parent_id,
                $comment->user_id,
                $comment->message,
                $comment->status,
                $date
            ]);

            if (!$sql_result) {
                $this->setFlash('Ошибка добавления комментария.', 'danger');
                break;
            }
            $this->setFlash('Ваш комментарий успешно добавлен.', 'success');

        } while (0);

        $this->redirect($request->server('HTTP_REFERER'));
    }

    /**
     * @param Request $request
     *
     * @return mixed|null
     *
     * @throws \Exception
     */
    public function voteAction(Request $request)
    {
        /** @var Session $session */
        $session = Session::getInstance();
        $user = $session->get('user');

        $msg = "Ошибка обработки.";

        do {
            if (empty($user)) {
                $this->setFlash('Неизвестный пользователь.', 'warning');
                break;
            }

            $comment = new Comment($this->getDB());
            $find_id = $comment->findById($request->post('comment_id'));
            if (empty($find_id)) {
                $this->setFlash('Неизвестный комментарий.', 'warning');
                break;
            }

            $direction = intval($request->post('direction'));
            if ($direction > 0) {
                $msg = "Вам понравился комментарий. Ваш голос засчитан.";
            } elseif ($direction < 0) {
                $msg = "Вам не понравился комментарий. Ваш голос засчитан.";
            } else {
                break;
            }
            $vote = new Vote($this->getDB());
            $detail = $vote->checkVote([$find_id[0]['id'], $user]);

            if (!empty($detail)) {
                $date = date('d-m-y H:i', $detail[0]['created_at']);
                $msg = "Вы уже голосовали за этот комментарий {$date}";
                break;
            }

            $voting = $vote->voting([$find_id[0]['id'], $user, $direction, date('U')]);

        } while (0);

        return $msg;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function userlistAction()
    {
        $params = $this->router->getParams();
        $user_id = intval($params[0]);

        do {
            if (empty($user_id)) {
                $this->setFlash('Неизвестный пользователь.', 'warning');
                break;
            }
            $user = new User($this->getDB());
            $user_detail = $user->findById([$user_id]);
            if (empty($user_detail)) {
                $this->setFlash('Неизвестный пользователь.', 'warning');
                break;
            }

            $comment = new Comment($this->getDB());
            // пагинация
            $count_comments = $comment->getCountCommentsByUser($user_detail[0]['id']);
            $num = Config::get('items_per_page');
            $page = empty($params[1]) ? 1 : intval($params[1]);
            $start = $page == 1 ? 0 : $page * $num - $num;
            $limit = "$start, $num";
            $pagination = new Pagination(array(
                'itemsCount' => $count_comments[0]['cnt'],
                'itemsPerPage' => $num,
                'currentPage' => $page
            ));

        } while(0);

        return $this->render('userlist', [
            'comments' => $comment->getAllCommentsByUser($user_detail[0]['id'], 'DESC', $limit),
            'pagination' => $pagination
        ]);
    }
}