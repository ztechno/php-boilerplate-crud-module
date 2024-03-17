<?php

use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$tableName  = $_GET['table'];
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = $table->getModule();

// get data
$crudRepository = new CrudRepository($tableName);
$crudRepository->setModule($module);

// response data table
header('Content-Type: application/json; charset=utf-8');
return $crudRepository->dataTableApi($fields);