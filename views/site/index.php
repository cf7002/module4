<?php

/** @var array $data */

$slide_classes = ['first-slide', 'second-slide', 'third-slide', 'fourth-slide'];

?>
<!-- CENTRAL -->

<!-- CAROUSEL -->
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php foreach ($data['hot_news'] as $key => $value): ?>
            <li data-target="#myCarousel" data-slide-to="<?= $key ?>" class="<?= $key === 0 ? 'active' : null ?>"></li>
        <?php endforeach; ?>
    </ol>
    <div class="carousel-inner">
        <?php foreach ($data['hot_news'] as $key => $value): ?>
            <div class="carousel-item <?= $key === 0 ? 'active' : null ?>">
                <img class="<?= $slide_classes[$key] ?>" src="/uploads/<?= $value['file_name'] ?>" alt="<?= $slide_classes[$key] ?>">
                <div class="container">
                    <div class="carousel-caption text-left">
                        <h4><?= $value['title'] ?></h4>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<?php foreach ($data['news_feed'] as $block_key => $block_value): ?>
    <div class="mb-5">
        <h3 class="pb-3 mb-4 font-italic border-bottom">
            <a href="/category/view/<?= $block_key ?>"><?= $block_value['title'] ?></a>
        </h3>
        <ul>
            <?php foreach ($block_value['news'] as $news_key => $news_value): ?>
                <li><a href="/news/view/<?= $news_key ?>"><?= $news_value ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>
