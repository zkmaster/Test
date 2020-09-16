<?php
/**
 *  账户路由
 */
$api->group(['namespace' => 'Common'], function($api) {

    $api->post('emaReg', [
        'as' => 'emailRegister',
        'uses' => 'AccountController@emailRegister',
        'type' => 0,
        'desc' => '邮箱注册'
    ]);

    $api->post('actLog', [
        'as' => 'accountLogin',
        'uses' => 'AccountController@accountLogin',
        'type' => 0,
        'desc' => '账户密码登录'
    ]);

});
