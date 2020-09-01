<?php

/*
|--------------------------------------------------------------------------
| 系统路由
|--------------------------------------------------------------------------
|
| 加载版本路由文件
|
*/
$router->group(['namespace' => 'V1', 'prefix' => 'api'], function () use ($router) {
    if(file_exists(__DIR__.'/v1/version.php') ) include_once(__DIR__.'/v1/version.php');
});
