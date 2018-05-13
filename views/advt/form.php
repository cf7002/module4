<?php

/** @var array $data */

$k = (int) $data['advt']['rating'];
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

<form class="form-advt col-md-6" method="post">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Реклама</h1>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputTitle" class="form-control" name="title" placeholder="Название"
               value="<?= $data['advt']['title'] ?>" required autofocus>
        <label for="inputTitle">Название</label>
    </div>

    <div class="form-label-group">
        <input type="number" min="0" step="0.01" id="inputPrice" class="form-control" name="price" placeholder="Цена"
               value="<?= $data['advt']['price']/100 ?>" required>
        <label for="inputPrice">Цена</label>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputVendor" class="form-control" name="vendor" placeholder="Производитель"
               value="<?= $data['advt']['vendor'] ?>" required>
        <label for="inputVendor">Производитель</label>
    </div>

    <div class="form-label-group">
        <input type="url" id="inputHref" class="form-control" name="href" placeholder="Веб-адрес"
               value="<?= $data['advt']['href'] ?>" required>
        <label for="inputHref">Веб-адрес</label>
    </div>

    <div class="form-group">
        <select class="custom-select d-block w-100" id="inputRating" name="rating" required>
            <option value="">Укажите рейтинг</option>
            <?php foreach ($t as $key => $value): ?>
                <option value="<?= $key ?>" <?= $value ?>><?= $key ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group card">
        <div class="pt-2 pl-3">
            <input type="checkbox" class="" id="inputStatus" name="status" value="1" <?= $data['advt']['status'] ? 'checked' : null ?>>
            <label for="inputStatus">Использовать эту рекламу</label>
        </div>
    </div>

    <input type="hidden" id="inputVersion" class="form-control" name="version"
           value="<?= $data['advt']['version'] + 1 ?>">

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="1">Сохранить</button>
</form>