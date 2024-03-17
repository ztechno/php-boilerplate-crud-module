<?php

use Core\Response;

// init table fields
$tableName  = $_GET['table'];
$table      = tableFields($tableName);
$fields     = $table->getFields();

return Response::json($fields, 'fields '.$tableName.' retrieved');