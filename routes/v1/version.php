<?php
# 加载详细路由文件
$router->group([
    'prefix' => 'v1',
],function($api) {

    if(file_exists(__DIR__.'/common/account.php') ) include_once(__DIR__.'/common/account.php');

});
