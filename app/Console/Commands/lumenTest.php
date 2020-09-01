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
        $this->info(md5('dingo' . microtime()));
    }
}
