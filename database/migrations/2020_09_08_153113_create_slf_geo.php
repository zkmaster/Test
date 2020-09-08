<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            $table->unsignedInteger('created_at')->default(0)->nullable($value = false)->comment('创建时间');
            $table->unsignedInteger('updated_at')->default(0)->nullable($value = false)->comment('更新时间');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });
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
