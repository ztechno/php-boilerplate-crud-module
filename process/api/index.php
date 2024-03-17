<?php

use Core\Request;
use Core\Response;
use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$tableName  = $_GET['table'];
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = $table->getModule();

// get data
$crudRepository = new CrudRepository($tableName);
$crudRepository->setModule($module);

// response 
$order = Request::get('order', 'id');
$sort  = Request::get('sort', 'asc');
$limit = Request::get('limit', 20);
$page  = Request::get('page', 1);
$limit = (($page-1)*$limit).','.$limit;

return Response::json($crudRepository->get([], [$order=>$sort], $limit), 'data '.$tableName.' retrieved');