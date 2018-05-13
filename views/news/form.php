<?php

/** @var array $data */

use lib\Config;

$uploads = Config::get('uploads');
?>

<form class="form-category col-md-8" method="post" enctype="multipart/form-data">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Новости</h1>
    </div>

    <div class="form-group">
        <select class="custom-select d-block w-100" id="inputCategoryId" name="category_id" required autofocus>
            <option value="">Укажите категорию</option>
            <?php foreach ($data['categories'] as $item): ?>
                <option value="<?= $item['id'] ?>" <?= $item['id'] == $data['news']['category_id'] ? 'selected' : null ?>><?= $item['title'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group card">
        <div class="pt-2 pl-3">
            <input type="checkbox" class="" id="inputIsAnalytic" name="is_analytic" value="1" <?= $data['news']['is_analytic'] ? 'checked' : null ?>>
            <label for="inputIsAnalytic">Аналитическая статья</label>
        </div>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputTitle" class="form-control" name="title" placeholder="Название"
               value="<?= $data['news']['title'] ?>" required>
        <label for="inputTitle">Название</label>
        <div class="invalid-feedback" style="width: 100%;">
            Error
        </div>
    </div>

    <div class="form-group">
        <textarea rows="12" class="form-control" id="inputContent" name="content"
              placeholder="Содержание" required><?= $data['news']['content'] ?></textarea>
    </div>

    <div class="form-group card">
        <div class="pt-2 pl-3">
            <input type="checkbox" class="" id="inputStatus" name="status" value="1" <?= $data['news']['status'] ? 'checked' : null ?>>
            <label for="inputStatus">Отображать эту новость</label>
        </div>
    </div>

    <?php if (!empty($data['news_images'])): ?>
        <div class="form-group card">
            <div class="card-header">Текущие изображения</div>
            <div class="card-body">
                <ul class="list-group list-unstyled">
                    <?php foreach ($data['news_images'] as $image): ?>
                        <li>
                            <img src="/uploads/<?= $image['file_name'] ?>" alt="image" width="250">
                            <a href="/admin/news/deleteImage/<?= $image['id'] . '/' . $image['file_name'] ?>"
                               class="btn btn-outline-danger">Delete</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <div class="custom-file">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?= $uploads['max_file_size'] ?>" />
            <input type="file" class="custom-file-input" id="inputFile" name="upload_files[]"
                   accept="image/jpeg, image/gif, image/png" multiple>
            <label class="custom-file-label" for="inputFile">Добавить изображение</label>
        </div>
    </div>

    <div class="form-group card">
        <div class="card-header">Тэги</div>
        <div class="card-body">
            <ul class="list-group list-unstyled">
            <?php foreach ($data['tags'] as $tag): ?>
                <li>
                    <input type="checkbox" name="tags[]" id="<?= $tag['id'] ?>"
                           value="<?= $tag['id'] ?>" <?= in_array($tag['id'], $data['news_tags']) ? 'checked' : null ?>/>
                    <label for="<?= $tag['id'] ?>"><?= $tag['title'] ?></label>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <input type="hidden" id="inputVersion" class="form-control" name="version"
           value="<?= $data['news']['version'] + 1 ?>">

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="1">Сохранить</button>
</form>