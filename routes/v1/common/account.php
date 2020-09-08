<?php
/**
 *  账户路由
 */
$api->group(['namespace' => 'Common'], function($api) {

    $api->post('loginIn', [
        'as' => 'loginIn',
        'uses' => 'AccountController@accountLogin',
        'type' => 0,
        'desc' => '账户密码登录'
    ]);

});
