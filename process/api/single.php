<?php

use Core\Response;
use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$id         = $_GET['id'];
$tableName  = $_GET['table'];
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = $table->getModule();

// get data
$crudRepository = new CrudRepository($tableName);
$crudRepository->setModule($module);

// response data table
$data = $crudRepository->find([
    'id' => $id
]);

return Response::json($data, 'data '.$tableName.' retrieved');