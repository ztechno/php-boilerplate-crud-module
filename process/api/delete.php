<?php

use Core\Response;
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

// response json success
return Response::json([], 'data deleted');