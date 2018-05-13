<?php

/** @var array $data */

?>

<div class="mb-5">
    <h3 class="pb-3 mb-4 font-italic border-bottom">
        Комментарии пользователя <small></small>
    </h3>
    <ul>
        <?php foreach ($data['comments'] as $comment): ?>
            <li><a href="/news/view/<?= $comment['id'] ?>"><?= $comment['message'] ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>

<nav class="page-pagination">
    <?php foreach ($data['pagination']->buttons as $button): ?>
        <?php if ($button->isActive): ?>
            <a href="/comment/userlist/<?= $button->page ?>" class="btn btn-outline-primary"><?= $button->text ?></a>
        <?php else : ?>
            <a class="btn btn-outline-secondary disabled" href="#"><?= $button->text ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
