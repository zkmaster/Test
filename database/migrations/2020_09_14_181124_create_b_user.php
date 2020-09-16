<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid', 36)->unique()->default('')->nullable(false)->comment('用户唯一ID');
            $table->string('mobile', 11)->default('')->nullable(false)->comment('手机号');
            $table->string('email', 30)->default('')->nullable(false)->comment('邮箱');
            $table->string('password', 300)->default('')->nullable(false)->comment('用户登录密码');
            $table->char('salt', 10)->default('')->nullable(false)->comment('加密盐');

            $table->string('nick_name', 30)->unique()->default('')->nullable(false)->comment('用户昵称');
            $table->string('motto', 100)->default('')->nullable(false)->comment('座右铭');
            $table->string('avatar', 255)->default('')->nullable(false)->comment('用户头像');
            $table->tinyInteger('gender')->default(0)->nullable(false)->comment('性别:0=保密,1=男,2=女');
            $table->integer('birthday')->default(0)->nullable(false)->comment('生日');
            $table->string('real_name', 30)->default('')->nullable(false)->comment('用户真实名');
            $table->string('user_number', 18)->default('')->nullable(false)->comment('身份证号');
            $table->string('country', 30)->default('')->nullable(false)->comment('国家');
            $table->string('province', 30)->default('')->nullable(false)->comment('省');
            $table->string('city', 30)->default('')->nullable(false)->comment('市');

            $table->tinyInteger('bind_wechat')->default(0)->nullable(false)->comment('是否绑定了微信:0=否,1=是');
            $table->string('wechat_unionid')->default('')->nullable(false)->comment('微信 用户唯一ID');
            $table->string('wechat_openid')->default('')->nullable(false)->comment('微信 OpenID');
            $table->tinyInteger('bind_qq')->default(0)->nullable(false)->comment('是否绑定了QQ:0=否,1=是');
            $table->string('qq_unionid')->default('')->nullable(false)->comment('QQ 用户唯一ID');
            $table->string('qq_openid')->default('')->nullable(false)->comment('QQ OpenID');

            $table->unsignedBigInteger('login_at')->default(0)->nullable(false)->comment('登录时间');
            $table->tinyInteger('login_type')->default(0)->nullable(false)->comment('登录类型:1=手机号验证码,2=账户密码,3=微信授权,4=QQ授权');
            $table->unsignedBigInteger('last_login_at')->default(0)->nullable(false)->comment('上次登录时间');
            $table->tinyInteger('last_login_type')->default(0)->nullable(false)->comment('上次登录类型:1=手机号验证码,2=账户密码,3=微信授权,4=QQ授权');

            $table->tinyInteger('status')->default(0)->nullable(false)->comment('用户状态:0=未激活,1=正常,2=禁用,3=申请注销中');
            $table->tinyInteger('level')->default(0)->nullable(false)->comment('用户等级');

            $table->unsignedBigInteger('created_at')->default(0)->nullable(false)->comment('创建时间');
            $table->unsignedBigInteger('updated_at')->default(0)->nullable(false)->comment('修改时间');
        });
        DB::statement("ALTER TABLE `b_user` comment '用户表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_user');
    }
}
