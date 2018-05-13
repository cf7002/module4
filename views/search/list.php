<?php

/** @var array $data */

?>

<div class="mb-5">
    <h3 class="pb-3 mb-4 font-italic border-bottom">
        Новости с тэгом <small><?= $data['tag'][0]['title'] ?></small>
    </h3>
    <ul>
    <?php foreach ($data['news_list'] as $news): ?>
        <li><a href="/news/view/<?= $news['id'] ?>"><?= $news['title'] ?></a></li>
    <?php endforeach; ?>
    </ul>
</div>

<nav class="page-pagination">
    <?php foreach ($data['pagination']->buttons as $button): ?>
        <?php if ($button->isActive): ?>
            <a href="/search/tag/<?= $data['tag'][0]['id'] ?>/<?= $button->page ?>" class="btn btn-outline-primary"><?= $button->text ?></a>
        <?php else : ?>
            <a class="btn btn-outline-secondary disabled" href="#"><?= $button->text ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
