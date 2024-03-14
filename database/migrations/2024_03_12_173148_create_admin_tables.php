<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account', 190)->unique();
            $table->string('password', 60);
            $table->string('entry_password', 60);
            $table->string('email', 20)->nullable();
            $table->string('salt', 6)->nullable();
            $table->string('full_name', 20)->nullable();
            $table->string('last_ip', 16)->nullable();
            $table->integer('last_time')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->string('avatar')->nullable();
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });

        Schema::create('manager_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manager_id');
            $table->integer('role_id');
            $table->index(['role_id', 'manager_id']);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });

        Schema::create('privilege', function (Blueprint $table) {
            $table->increments('id');
            $table->string('privilege_name', 50)->unique();
            $table->string('module_name', 50)->nullable();
            $table->string('controller_name', 20)->nullable();
            $table->string('action_name', 30)->nullable();
            $table->string('route_url', 100)->nullable();
            $table->string('route_name', 30)->nullable();
            $table->string('parameter', 50)->nullable();
            $table->string('privilege_icon', 30)->nullable();
            $table->string('target', 10)->nullable();
            $table->smallInteger('orders')->default(0);
            $table->tinyInteger('is_menu')->default(1);
            $table->smallInteger('parent_id')->default(0);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });

        Schema::create('role_privilege', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('privilege_id');
            $table->index(['role_id', 'privilege_id']);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });

        Schema::create('operate_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('ip')->nullable();
            $table->text('input')->nullable();
            $table->index('user_id');
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });

        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_name');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_cate')->default(0);
            $table->smallInteger('orders')->default(0);
            $table->string('remark')->nullable();
            $table->smallInteger('parent_id')->default(0);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });
        Schema::create('login_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('operating_system', 20)->nullable();
            $table->string('useragent', 255)->nullable();
            $table->string('ip', 50)->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->smallInteger('login_num')->default(0);
            $table->index('user_id');
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });

        Schema::create('branch', function (Blueprint $table) {
            $table->increments('id');
            $table->string('branch_name', 50)->nullable();
            $table->smallInteger('type')->default(2);
            $table->smallInteger('orders')->default(0);
            $table->smallInteger('parent_id')->default(0);
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });

        Schema::create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('value', 512)->nullable();
            $table->string('inc_type', 64)->nullable();
            $table->string('desc', 50)->nullable();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('queue', 255)->nullable();
            $table->longText('payload', 512)->nullable();
            $table->tinyInteger('attempts')->default(0);
            $table->integer('reserved_at')->nullable();
            $table->integer('available_at')->nullable();
            $table->integer('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager');
        Schema::dropIfExists('role');
        Schema::dropIfExists('privilege');
        Schema::dropIfExists('manager_role');
        Schema::dropIfExists('role_privilege');
        Schema::dropIfExists('operate_log');
        Schema::dropIfExists('login_log');
        Schema::dropIfExists('branch');
        Schema::dropIfExists('config');
        Schema::dropIfExists('jobs');
    }
};
