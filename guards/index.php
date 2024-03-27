<?php

$auth = auth();
$publicRoutes = \Core\Request::getPublicRoutes();

if(empty($auth) && !in_array($route, $publicRoutes))
{
    header("location: ".routeTo('auth/login'));
    die();
}

if($auth && $route == 'auth/login')
{
    $AUTH_AFTER_LOGIN_SUCCESS = env('APP_PATH', '') .'/'. env('AUTH_AFTER_LOGIN_SUCCESS','');
    header("location: ". $AUTH_AFTER_LOGIN_SUCCESS);
    die();
}