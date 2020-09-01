<?php
/**
 *  账户路由
 */
$api->group(['namespace' => 'Common'], function($api) {

    $api->post('loginIn', [
        'as' => 'loginIn',
        'uses' => 'AccountController@loginIn',
        'type' => 0,
    ]);

});
