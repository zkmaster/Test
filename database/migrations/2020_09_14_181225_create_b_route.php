<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBRoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_route', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pid')->default(0)->nullable(false)->comment('父级ID');
            $table->string('name', 30)->default('')->nullable(false)->comment('名称');
            $table->string('path', 100)->unique()->default('')->nullable(false)->comment('路径');
            $table->string('alias', 100)->unique()->default('')->nullable(false)->comment('别名');
            $table->tinyInteger('type',false, true)->default(0)->nullable(false)->comment('类型:0=免登陆,1=登录免授权,2=登录且授权');
            $table->tinyInteger('status',false, true)->default(0)->nullable(false)->comment('类型:0=禁用,1=启用,2=暂未开放');
            $table->unsignedBigInteger('created_at')->default(0)->nullable(false)->comment('创建时间');
            $table->unsignedBigInteger('updated_at')->default(0)->nullable(false)->comment('修改时间');
        });
        DB::statement("ALTER TABLE `b_route` comment '路由节点表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_route');
    }
}
