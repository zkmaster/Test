<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;

class lumenTest extends Command
{
    /**
     * 命令
     *
     * @var string
     */
    protected $signature = 'lumen:test';

    /**
     * 命令行的概述。
     * @var string
     */
    protected $description = '自定义测试命令';

    /**
     * 运行命令。
     *
     * @return mixed
     */
    public function handle()
    {
//        $string  = 'https://cubepaas.com'; # ok
//        $string  = 'http://cubepaas.com'; # ok
//        $string  = 'https://www.cubepaas.com'; # ok
//        $string  = 'https://wwwwwwwwww.cubepaas.com'; # ok
//        $string  = 'https://wwww.cubepaas.com/?name=100&age=100'; # ok
//        $string  = 'https://wwww.cubepaas.com/user/name/?age=100'; # ok
        $string  = 'https://cubepaas.com/user/name/w.ewe'; # ok
        preg_match_all('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?((\?(&?[\w-#]+=[\w-#]+)*)|([\w-#]\/?))\/?$/', $string, $match);
//        preg_match_all('/^((https|http):\/\/)?([\da-zA-Z\.-]+)\.([a-zA-Z\.]{2,6})([\/\w \.-]*)*\/?.*$/', $string, $match);
        dd($match);
    }
}
