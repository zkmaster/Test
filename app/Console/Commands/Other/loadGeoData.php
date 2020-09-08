<?php


namespace App\Console\Commands\Other;


use Illuminate\Console\Command;

class loadGeoData extends Command
{
    /**
     * 命令
     *
     * @var string
     */
    protected $signature = 'load.geo';

    /**
     * 命令行的概述。
     * @var string
     */
    protected $description = '加载地区信息数据';

    /**
     * 运行命令。
     *
     * @return mixed
     */
    public function handle()
    {
        $res = `pwd`;
        dd($res);
    }
}
