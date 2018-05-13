<?php

/** @var array $data */

$k = (int) $data['category']['priority'];

// вариант 1
//for ($i = 0; $i < 256; $i++) {
//    $t[$i] = $i == $k ? 'selected' : null;
//}

// ИМХО, это будет быстрее чем вариант 1
$i = 0;
while ($i < $k) {
    $t[$i] = null;
    $i++;
}
$t[$k] = 'selected';
while ($i < 255) {
    $i++;
    $t[$i] = null;
}
?>

<form class="form-category col-md-6" method="post">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Категория</h1>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputTitle" class="form-control" name="title" placeholder="Название"
               value="<?= $data['category']['title'] ?>" required autofocus>
        <label for="inputTitle">Название</label>
        <div class="invalid-feedback" style="width: 100%;">
            Errorrrrrrrrr
        </div>
    </div>

    <div class="form-group">
        <select class="custom-select d-block w-100" id="inputPriority" name="priority" required>
            <option value="">Укажите приоритет</option>
            <?php foreach ($t as $key => $value): ?>
                <option value="<?= $key ?>" <?= $value ?>><?= $key ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group card">
        <div class="pt-2 pl-3">
            <input type="checkbox" class="" id="inputIsModerated" name="is_moderated" value="1" <?= $data['category']['is_moderated'] ? 'checked' : null ?>>
            <label for="inputIsModerated">Модерировать комментарии</label>
        </div>
    </div>

    <div class="form-group card">
        <div class="pt-2 pl-3">
            <input type="checkbox" class="" id="inputSubCategory" name="sub_category" value="1" <?= $data['category']['sub_category'] ? 'checked' : null ?>>
            <label for="inputSubCategory" title="Использовать как подкатегорию для других категорий">Подкатегория</label>
        </div>
    </div>

    <div class="form-group card">
        <div class="pt-2 pl-3">
            <input type="checkbox" class="" id="inputStatus" name="status" value="1" <?= $data['category']['status'] ? 'checked' : null ?>>
            <label for="inputStatus">Использовать эту категорию</label>
        </div>
    </div>

    <input type="hidden" id="inputVersion" class="form-control" name="version"
           value="<?= $data['category']['version'] + 1 ?>">

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="1">Сохранить</button>
</form>