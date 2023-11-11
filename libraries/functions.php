<?php

function crudRoute($path, $tableName)
{
    $params = ['table' => $tableName];
    if(isset($_GET['filter']))
    {
        $params['filter'] = $_GET['filter'];
    }
    return routeTo($path, $params);
}

// echo startWith('crud/index', 'crud/');