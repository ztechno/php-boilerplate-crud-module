<?php

use Core\Page;
use Core\Request;
use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$tableName  = $_GET['table'];
$id         = $_GET['id'];
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = $table->getModule();
$title      = _ucwords(__("$module.label.$tableName"));
$error_msg  = get_flash_msg('error');
$old        = get_flash_msg('old');

$crudRepository = new CrudRepository($tableName);
$crudRepository->setModule($module);

if(Request::isMethod('POST'))
{

    $crudRepository->update($_POST[$tableName], [
        'id' => $id
    ]);

    set_flash_msg(['success'=>"$title berhasil diupdate"]);

    header('location:'.crudRoute('crud/index',$tableName));
    die();
}

$data = $crudRepository->find([
    'id' => $id
]);

// page section
Page::setActive("$module.$tableName");
Page::setTitle($title);
Page::setModuleName($title);
Page::setBreadcrumbs([
    [
        'url' => routeTo('/'),
        'title' => __('crud.label.home')
    ],
    [
        'url' => routeTo('crud/index', ['table' => $tableName]),
        'title' => $title
    ],
    [
        'title' => __('crud.label.edit')
    ]
]);

return view('crud/views/edit', compact('fields', 'tableName', 'data', 'error_msg', 'old'));