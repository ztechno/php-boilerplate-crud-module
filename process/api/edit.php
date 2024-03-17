<?php

use Core\Request;
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

if(Request::isMethod('POST'))
{
    $crudRepository->update($_POST[$tableName], [
        'id' => $id
    ]);

    // response data in json 
    $data = $crudRepository->find([
        'id' => $id
    ]);
    
    return Response::json($data, 'data updated');
}

// response method not allowed
return Response::json([], 'this method is not allowed', 401);