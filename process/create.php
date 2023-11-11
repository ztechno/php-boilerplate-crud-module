<?php

use Core\Page;
use Core\Request;
use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$tableName  = $_GET['table'];
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = $table->getModule();
$title      = _ucwords(__("$module.label.$tableName"));
$error_msg  = get_flash_msg('error');
$old        = get_flash_msg('old');

if(Request::isMethod('POST'))
{

    $crudRepository = new CrudRepository($tableName);
    $crudRepository->setModule($module);
    $crudRepository->create($_POST[$tableName]);

    set_flash_msg(['success'=>"$title berhasil ditambahkan"]);

    header('location:'.crudRoute('crud/index',$tableName));
    die();
}

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
        'title' => __('crud.label.create')
    ]
]);

return view('crud/views/create', compact('fields', 'tableName', 'error_msg', 'old'));