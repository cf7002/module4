<?php

/** @var array $data */

var_dump($data);
?>

<form class="form-search" method="post">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Поиск по параметрам</h1>
    </div>

    <div class="form-group card">
        <div class="card-header">Категории</div>
        <div class="card-body">
            <ul class="list-group list-unstyled">
                <?php foreach ($data['categories'] as $category): ?>
                    <li>
                        <input type="checkbox" name="categories[]" id="<?= $category['id'] ?>" value="<?= $category['id'] ?>"/>
                        <label for="<?= $category['id'] ?>"><?= $category['title'] ?></label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="form-group card">
        <div class="card-header">Тэги</div>
        <div class="card-body">
            <ul class="list-group list-unstyled">
                <?php foreach ($data['tags'] as $tag): ?>
                    <li>
                        <input type="checkbox" name="tags[]" id="<?= $tag['id'] ?>" value="<?= $tag['id'] ?>"/>
                        <label for="<?= $tag['id'] ?>"><?= $tag['title'] ?></label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputBegin" class="form-control" name="begin" placeholder="Начало периода">
        <label for="inputBegin">Начало периода</label>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputEnd" class="form-control" name="end" placeholder="Окончание периода">
        <label for="inputEnd">Окончание периода</label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="1">Искать</button>
</form>
