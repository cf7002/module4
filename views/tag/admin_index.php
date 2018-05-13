<?php

use lib\Model;

/** @var array $data */

?>

<h1>Тэги</h1>
<div class="mb-3">
    <a href="/admin/tag/create" class="btn btn-success">Создать</a>
</div>

<table class="table table-striped table-responsive-md">
    <thead>
    <tr class="table-primary">
        <th scope="col"><?= $data['labels']['id'] ?></th>
        <th scope="col"><?= $data['labels']['title'] ?></th>
        <th scope="col"><?= $data['labels']['status'] ?></th>
        <th scope="col" colspan="2" class="text-center">Действие</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data['tags'] as $item):?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= $item['title'] ?></td>
            <td class="td-status">
                <i class="<?= $item['status'] == Model::STATUS_ACTIVE ? Model::ICON_ACTIVE : Model::ICON_NOT_ACTIVE ?>"></i>
            </td>
            <td class="td-action">
                <a href="/admin/tag/update/<?= $item['id'] ?>"><i class="far fa-edit"></i></a>
                <a href="/admin/tag/delete/<?= $item['id'] ?>"><i class="far fa-trash-alt"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>