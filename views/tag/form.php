<?php

/** @var array $data */

?>

<form class="form-tag col-md-6" method="post">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Тэг</h1>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputTitle" class="form-control" name="title" placeholder="Название"
               value="<?= $data['tag']['title'] ?>" required autofocus>
        <label for="inputTitle">Название</label>
        <div class="invalid-feedback" style="width: 100%;">
            Error
        </div>
    </div>

    <div class="form-group card">
        <div class="pt-2 pl-3">
            <input type="checkbox" class="" id="inputStatus" name="status" value="1" <?= $data['tag']['status'] ? 'checked' : null ?>>
            <label for="inputStatus">Использовать этот тэг</label>
        </div>
    </div>

    <input type="hidden" id="inputVersion" class="form-control" name="version"
           value="<?= $data['tag']['version'] + 1 ?>">

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="1">Сохранить</button>
</form>