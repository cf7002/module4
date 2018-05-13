<?php

/** @var array $data */

//var_dump($data);
?>

<div class="card-deck mb-3 text-center">
    <!-- TOP-5 COMMENTATORS-->
    <div class="card mb-4 box-shadow">
        <div class="card-header px-0">
            <h4 class="my-0">Комментаторы</h4>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                <?php foreach ($data['top_users'] as $user): ?>
                    <li class="list-group-item">
                        <a href="/comment/userlist/<?= $user['user_id'] ?>"><?= $user['nick_name'] ?><sup><span class="badge badge-primary"><?= $user['cnt'] ?></span></sup></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<!-- TAGS BLOCK -->
<div class="card-deck mb-3 text-center">
    <div class="card mb-4 box-shadow">
        <div class="card-header px-0">
            <h4 class="my-0">Популярные теги</h4>
        </div>
        <div class="card-body text-justify">
        <?php foreach ($data['hot_tags'] as $tag):?>
            <a href="/search/tag/<?= $tag['tag_id'] ?>" class="h<?= $tag['index'] ?>"><small><?=$tag['title'] ?></small></a>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<?php foreach ($data['ads_right'] as $ad): ?>
    <!-- Advertising Block -->
    <div class="p-3 mb-3 bg-light rounded ads" data-toggle="popover" data-coupon="<?= md5($ad['title']) ?>">
        <h4 class="font-italic"><?= $ad['title'] ?></h4>
        <p class="mb-0 text-justify">
            <?= $ad['vendor'] ?>
        </p>
        <?php
        $arr_f = preg_split('/(\d*)(\d{2})/', $ad['price'], 0,PREG_SPLIT_DELIM_CAPTURE);
        $arr_d = preg_split('/(\d*)(\d{2})/', $ad['price'] * 0.9, 0,PREG_SPLIT_DELIM_CAPTURE);
        ?>
        <p class="price">
            <span class="full_price"><?= empty($arr_f[1]) ? 0 : $arr_f[1] ?><sup><?= $arr_f[2] ?></sup></span>
            <span class="discount"><?= empty($arr_d[1]) ? 0 : $arr_d[1] ?><sup><?= $arr_d[2] ?></sup></span>
        </p>

        <div class="text-center">Подробнее<br><a class="vendor" href="<?= $ad['href'] ?>"><?= $ad['href'] ?></a></div>
    </div>
<?php endforeach; ?>
