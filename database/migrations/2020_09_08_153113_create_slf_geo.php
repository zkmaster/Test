<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSlfGeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slf_geo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('criteria_id')->default(0)->nullable(false)->comment('标准ID')->unique();
            $table->string('name_en', 100)->default('')->nullable(false)->comment('名称');
            $table->string('name', 100)->default('')->nullable(false)->comment('名称');
            $table->string('canonical_name_en', 300)->default('')->nullable(false)->comment('规范名称');
            $table->string('canonical_name', 300)->default('')->nullable(false)->comment('规范名称');
            $table->unsignedInteger('parent_id')->default(0)->nullable(false)->comment('父级ID');
            $table->string('country_code', 10)->default('')->nullable(false)->comment('国家代码');
            $table->string('target_type_en', 90)->default('')->nullable(false)->comment('目标类型');
            $table->string('target_type', 90)->default('')->nullable(false)->comment('目标类型');
            $table->string('status_en', 20)->default('')->nullable(false)->comment('状态');
            $table->unsignedTinyInteger('status')->default(0)->nullable(false)->comment('状态:0=禁用,1=启用');
//            $table->unsignedInteger('created_at')->default(0)->nullable(false)->comment('创建时间');
//            $table->unsignedInteger('updated_at')->default(0)->nullable(false)->comment('更新时间');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });
        DB::statement("ALTER TABLE `slf_geo` comment '地理位置'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slf_geo');
    }
}
