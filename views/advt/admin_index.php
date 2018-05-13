<?php

use lib\Model;

/** @var array $data */

?>

<h1>Реклама</h1>
<div class="mb-3">
    <a href="/admin/advt/create" class="btn btn-success">Создать</a>
</div>

<table class="table table-striped table-responsive-md">
    <thead>
    <tr class="table-primary">
        <th scope="col"><?= $data['labels']['id'] ?></th>
        <th scope="col"><?= $data['labels']['title'] ?></th>
        <th scope="col"><?= $data['labels']['price'] ?></th>
        <th scope="col"><?= $data['labels']['vendor'] ?></th>
        <th scope="col"><?= $data['labels']['href'] ?></th>
        <th scope="col"><?= $data['labels']['rating'] ?></th>
        <th scope="col"><?= $data['labels']['status'] ?></th>
        <th scope="col" colspan="2" class="text-center">Действие</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data['advt'] as $item):?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= $item['title'] ?></td>
            <td class="text-nowrap text-right">
                <?= number_format($item['price']/100, 2, '.', ' ') ?>
            </td>
            <td><?= $item['vendor'] ?></td>
            <td><?= $item['href'] ?></td>
            <td><?= $item['rating'] ?></td>
            <td class="td-status">
                <i class="<?= $item['status'] == Model::STATUS_ACTIVE ? Model::ICON_ACTIVE : Model::ICON_NOT_ACTIVE ?>"></i>
            </td>
            <td class="td-action">
                <a href="/admin/advt/update/<?= $item['id'] ?>"><i class="far fa-edit"></i></a>
                <a href="/admin/advt/delete/<?= $item['id'] ?>"><i class="far fa-trash-alt"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>