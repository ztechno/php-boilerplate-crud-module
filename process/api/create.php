<?php

use Core\Request;
use Core\Response;
use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$tableName  = $_GET['table'];
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = $table->getModule();

if(Request::isMethod('POST'))
{
    $crudRepository = new CrudRepository($tableName);
    $crudRepository->setModule($module);
    $crudRepository->create($_POST[$tableName]);

    return Response::json($data, 'data created');
}

// response method not allowed
return Response::json([], 'this method is not allowed', 401);