<?php

/** @var array $data */

use lib\Session;
use models\User;

/** @var Session $session */
$session = Session::getInstance();

$news = $data['news'][0];

$disabled = User::isUser() ? '' : 'disabled';
?>

<h1><?= $news['title'] ?></h1>
<div class="text-center">
    <img class="img-fluid" src="/uploads/<?= $news['file_name'] ?>" alt="Image">
</div>
<p class="text-justify"><?= $news['content'] ?></p>

<hr>
<?php foreach ($data['tag_list'] as $tag): ?>
    <a href="/search/tag/<?= $tag['id'] ?>" class="mx-1"><?=$tag['title'] ?></a>
<?php endforeach; ?>
<hr>

<div class="card border-info mb-3" style="max-width: 18rem;">
    <div class="card-body text-info">
        <h5 class="card-title">Эту новость</h5>
        <p class="card-text">читают сейчас: <span class="ml-1 badge badge-warning" id="read_now">3</span></p>
        <p class="card-text">прочитали всего: <span class="ml-1 badge badge-success"><?= $news['view_counter'] ?></span></p>
    </div>
</div>
<hr>

<div class="mb-3 text-center">Комментариев:&nbsp;<?= $data['total_comments'] ?></div>

<div class="card" id="newComment">
    <div class="card-header">
        <label for="inputMessage" class="font-weight-bold">Ваш комментарий</label>
    </div>
    <form id="userMessage" method="post" action="/comment/create">
        <div class="card-body p-1">
            <textarea name="message" id="inputMessage" rows="5" required></textarea>
            <input type="hidden" id="inputNews" name="news_id" value="<?= $news['id'] ?>">
            <input type="hidden" id="inputUser" name="user_id" value="<?= $session->get('user') ?>">
        </div>
        <div class="card-footer text-right">
            <input type="submit" class="btn btn-outline-success btn-sm" <?= $disabled ?> value="Добавить комментарий">
        </div>
    </form>
</div>

<a name="reply"></a>
<div class="card" id="replyComment" style="display: none">
    <div class="card-header">
        <label for="inputMessage" class="font-weight-bold">Ответ на комментарий</label>
    </div>
    <div class="card-body p-1">
        <form id="userMessage" method="post" action="/comment/reply">
            <textarea name="message" id="inputMessage" rows="5" required></textarea>
            <input type="hidden" id="inputComment" name="comment_id" value="">
        </form>
    </div>
    <div class="card-footer text-right">
        <input type="submit" class="btn btn-outline-success btn-sm" <?= $disabled ?> value="Ответить">
    </div>
</div>

<div class="mt-3">
    <?php
        $first = array_shift($data['hierarchy']);
        $comment = $data['list_comments'][$first[0]];
        unset($data['list_comments'][$first[0]]);
    ?>

    <div class="card my-2">
        <div class="card-header">
            <div class="col-sm-12 px-0 row" data-comment="<?= $comment['id'] ?>">
                <span class="font-weight-bold"><?= $comment['nick_name'] ?></span>
                <button class="btn btn-sm btn-outline-success vote ml-4" data-vote="1" <?= $disabled ?>><i class="fas fa-thumbs-up"></i></button>
                <button class="btn btn-sm btn-outline-success" disabled><?= $comment['vote_up'] ?></button>
                <button class="btn btn-sm btn-outline-danger vote ml-2" data-vote="-1" <?= $disabled ?>><i class="fas fa-thumbs-down"></i></button>
                <button class="btn btn-sm btn-outline-danger" disabled><?= $comment['vote_down'] ?></button>
                <small class="ml-4"><?= date('d-m-y H:i', $comment['created_at']) ?></small>
                <a href="#reply" class="btn btn-sm btn-outline-info ml-2 reply" <?= $disabled ?> title="Ответить"><i class="fas fa-reply"></i></a>
            </div>
            <div class="mt-3 row">
                <?= $comment['message'] ?>
            </div>
        </div>
        <div class="card-body">
        <?php foreach ($data['hierarchy'] as $items): ?>
            <?php $spacer = 0 ?>
            <div class="row">
            <?php foreach ($items as $item): ?>
                <?php if (!isset($data['list_comments'][$item])): ?>
                    <?php $spacer += 1 ?>
                <?php else: ?>
                <div class="col-sm-<?= $spacer ?>">&nbsp;</div>
                <div class="col-sm-<?= 12 - $spacer ?>" data-comment="<?= $data['list_comments'][$item]['id'] ?>">
                    <span class=" font-weight-bold"><?= $data['list_comments'][$item]['nick_name'] ?></span>
                    <button class="btn btn-sm btn-outline-success vote ml-2" data-vote="1" <?= $disabled ?>><i class="fas fa-thumbs-up vote"></i></button>
                    <button class="btn btn-sm btn-outline-success" disabled><?= $data['list_comments'][$item]['vote_up'] ?></button>
                    <button class="btn btn-sm btn-outline-danger vote ml-2" data-vote="-1" <?= $disabled ?>><i class="fas fa-thumbs-down vote"></i></button>
                    <button class="btn btn-sm btn-outline-danger" disabled><?= $data['list_comments'][$item]['vote_down'] ?></button>
                    <small class="ml-2"><?= date('d-m-y H:i', $data['list_comments'][$item]['created_at']) ?></small>
                    <a href="#reply" class="btn btn-sm btn-outline-info reply" <?= $disabled ?> title="Ответить"><i class="fas fa-reply"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-<?= $spacer ?>">&nbsp;</div>
                <div class="col-sm-<?= 12 - $spacer ?> my-2 border-bottom"><?= $data['list_comments'][$item]['message'] ?></div>
                <?php unset($data['list_comments'][$item]) ?>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }
    function read_now() {
        var num = getRandomInt(0, 6);
        var elem = document.getElementById('read_now');
        elem.textContent = num;
    }
    setInterval(read_now, 3000);
</script>