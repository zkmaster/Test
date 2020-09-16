<?php
/**
 *  用户路由
 */
$api->group(['namespace' => 'Common'], function($api) {

    $api->get('useInf', [
        'as' => 'userInfo',
        'uses' => 'UserController@getInfo',
        'type' => 1,
        'desc' => '用户信息'
    ]);

});
