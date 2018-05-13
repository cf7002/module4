<?php

use lib\Config;
use lib\Session;

/** @var array $data */

/** @var Session $session */
$session = Session::getInstance();

$flash = getFlash();
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

    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>

<div class="container">
    <!-- HEARED -->
    <header class="header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-7">
                <a class="header-logo text-dark" href="/">Bla-Bla News</a>
            </div>

            <div class="col-5 d-flex justify-content-end align-items-center">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Поиск" aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text btn btn-secondary"><i class="fas fa-search"></i></span>
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
    <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-center">
            <a class="p-2 text-muted" href="/admin">Домой</a>
            <a class="p-2 text-muted" href="/admin/category">Категории</a>
            <a class="p-2 text-muted" href="/admin/tag">Тэги</a>
            <a class="p-2 text-muted" href="/admin/news">Новости</a>
            <a class="p-2 text-muted" href="/admin/advt">Реклама</a>
            <a class="p-2 text-muted" href="#">Поиск</a>
            <a class="p-2 text-muted" href="#">О нас</a>
        </nav>
    </div>

    <main role="main" class="main">
        <div class="row">
            <div class="col-md-12">
                <?php
                    if (!empty($flash)) {
                        foreach ($flash as $message) {
                            printf("<div class='alert %s' role='alert'>%s</div>", $message['type'], $message['message']);
                        }
                    }
                ?>

                <?= $data['content'] ?>
            </div>

        </div>
    </main>

    <footer class="footer">
        <?= Config::get('site_name') ?>&nbsp;&copy;&nbsp;<?= date('Y') ?>
    </footer>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script>window.jQuery || document.write('<script src="/js/jquery-slim.min.js"><\/script>')</script>-->
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/holder.min.js"></script>
<script>
    Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
    });
</script>
</body>
</html>
