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

$crudRepository = new CrudRepository($tableName);
$crudRepository->setModule($module);
$crudRepository->delete([
    'id' => $id
]);

$title      = _ucwords(__("$module.label.$tableName"));

set_flash_msg(['success'=>"$title berhasil dihapus"]);

header('location:'.crudRoute('crud/index',$tableName));
die();
