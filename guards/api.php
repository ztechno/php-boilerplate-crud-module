<?php

use Core\JwtAuth;
use Core\Response;

if(!JwtAuth::validateBearerToken() || empty(jwtAuth()))
{
    echo Response::json([], 'Unauthorized', 401);
    die();
}