<?php

use lib\Config;
use lib\Session;
use models\Tag;
use models\User;

/** @var array $data */

/** @var Session $session */
$session = Session::getInstance();

$flash = getFlash();

$tags = Tag::getArrayTag();
$arr = [];
foreach ($tags as $tag) {
    $arr[] = '"' . $tag['title'] . '"';
}
$tags_str = implode(',', $arr);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title><?= Config::get('site_name') ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="/css/fontawesome-all.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/site.css" rel="stylesheet">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<!--<body onunload="return _unload();">-->
<body data-popover="0">

<input type="text" hidden id="isSubscribe" value="<?= $session->get('isSubscribe') ? 1 : null ?>">
<!-- Модальное окно на email-рассылку -->
<div class="modal fade" id="offerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form-signin" action="/user/subscribe" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Хотите получать новостную рассылку?</h5>
                </div>
                <div class="modal-body">
                    <h6>Предлагаем подписаться.</h6>
                    <div class="form-label-group">
                        <input type="email" id="subscribeEmail" name="email" class="form-control" maxlength="100" placeholder="Электронный адрес" required autofocus>
                        <label for="subscribeEmail">Электронный адрес</label>
                    </div>

                    <div class="form-label-group">
                        <input type="text" id="subscribeFirstName" name="first_name" class="form-control" maxlength="25" placeholder="Имя" required>
                        <label for="subscribeFirstName">Имя</label>
                    </div>

                    <div class="form-label-group">
                        <input type="text" id="subscribeLastName" name="last_name" class="form-control" maxlength="25" placeholder="Фамилия" required>
                        <label for="subscribeLastName">Фамилия</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отказаться</button>
                    <button type="submit" class="btn btn-primary" id="formSubscribe" name="submit" value="1">Подписаться</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Модальное окно на email-рассылку -->

<div class="container">
    <!-- HEARED -->
    <header class="header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-7">
                <a class="header-logo text-dark" href="/">Bla-Bla News</a>
            </div>

            <div class="col-5 d-flex justify-content-end align-items-center">
                <form class="form-inline" action="/search/title/" method="post">
                    <div class="input-group input-group-sm ui-widget">
                        <input type="text" class="form-control" name="search" id="tags_search" placeholder="Поиск" aria-label="Search">
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text btn btn-secondary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <?php if ($session->get('user')): ?>
                    <a class="btn btn-sm btn-outline-secondary ml-2" href="/user/logout">Выйти</a>
                <?php else: ?>
                    <a class="btn btn-sm btn-outline-secondary ml-2" href="/user/login">Войти</a>
                    <a class="btn btn-sm btn-secondary ml-2" href="/user/register">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <!-- MAIN MENU -->
<!--    <div class="nav-scroller py-1 mb-2">-->
    <nav class="nav d-flex justify-content-center">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link text-muted active" href="/">Главная</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Категории</a>
                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                <?php foreach ($data['menu'] as $menu_item): ?>
                    <?php if ($menu_item['sub_category']): ?>
                        <li class="dropdown-submenu">
                            <a class="dropdown-item" tabindex="-1" href="#"><?= $menu_item['title'] ?></a>
                            <ul class="dropdown-menu">
                            <?php foreach ($data['menu'] as $submenu_item): ?>
                                <?php if (!$submenu_item['sub_category']): ?>
                                    <li><a class="dropdown-item" tabindex="-1" href="/category/analytic/<?= $submenu_item['id'] ?>"><?= $submenu_item['title'] ?></a></li>
                                <?php endif; ?>
                            <?php endforeach;?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="/category/view/<?= $menu_item['id'] ?>"><?= $menu_item['title'] ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link text-muted" href="/search">Поиск</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-muted" href="/about">О нас</a>
            </li>
        </ul>
        <?php if (User::isAdmin()): ?>
            <a class="p-2 text-muted" href="/admin">Админ-зона</a>
        <?php endif; ?>
    </nav>


    <main role="main" class="main">
        <div class="row">
            <!--  LEFT SIDE  -->
            <aside class="col-md-3">
                <?php if (file_exists(VIEW_DIR . 'layouts/left_side.php')) {
                    include(VIEW_DIR . 'layouts/left_side.php');
                } else {
                    echo '';
                } ?>
            </aside>

            <!-- CENTER -->
            <div class="col-md-6">
                <?php if (!empty($flash)) {
                    foreach ($flash as $message) {
                        printf("<div class='alert %s' role='alert'>%s</div>", $message['type'], $message['message']);
                    }
                } ?>

                <?= $data['content'] ?>
            </div>

            <!-- RIGHT SIDE -->
            <aside class="col-md-3">
                <?php if (file_exists(VIEW_DIR . 'layouts/right_side.php')) {
                    include(VIEW_DIR . 'layouts/right_side.php');
                } else {
                    echo '';
                } ?>
            </aside>
        </div>
    </main>

    <footer class="footer">
        <?= Config::get('site_name') ?>&nbsp;&copy;&nbsp;<?= date('Y') ?>
    </footer>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script>window.jQuery || document.write('<script src="/js/vendor/jquery-slim.min.js"><\/script>')</script>-->
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/holder.min.js"></script>
<script src="/js/site.js"></script>

<script>
    (function ($) {
        var availableTags = [<?= $tags_str ?>];

        $('#tags_search').autocomplete({
            source: availableTags
        });
    })(jQuery);

    // Holder.addTheme('thumb', {
    //     bg: '#55595c',
    //     fg: '#eceeef',
    //     text: 'Thumbnail'
    // });
</script>
</body>
</html>
