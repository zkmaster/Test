<?php
/**
 * 文件
 */
$api->group(['namespace' => 'Common', 'prefix' => 'file'], function($api) {

    $api->get('down', [
        'as' => 'fileDown',
        'uses' => 'FileController@download',
        'type' => 0,
        'desc' => '下载文件'
    ]);
});
