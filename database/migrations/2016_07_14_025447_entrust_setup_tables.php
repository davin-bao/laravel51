<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('staff_role', function (Blueprint $table) {
            $table->integer('staff_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->primary(['staff_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parent')->nullable()->comment('父菜单 action');
            $table->string('icon')->nullable()->comment('图标class');
            $table->string('uri')->nullable()->comment('uri 现无意义');
            $table->string('action')->nullable()->comment('action URL地址对应的接口地址');
            $table->string('display_name')->nullable()->comment('中文名称');
            $table->string('description')->nullable()->comment('描述');
            $table->tinyInteger('is_menu')->default(0)->comment('是否作为菜单显示:1 是, 0 否');
            $table->tinyInteger('allow')->default(0)->comment('是否所有角色都可以访问:1 是, 0 否');
            $table->tinyInteger('sort')->default(0)->comment('排序');

            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('role_staff');
        Schema::drop('roles');
    }
}
