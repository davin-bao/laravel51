<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',50)->unique();
            $table->string('email',50)->unique();
            $table->string('password', 60);
            $table->string('timezone', 25);
            $table->tinyInteger('status')->default(1);
            $table->string('name', 25);
            $table->string('mobile', 25);
            $table->string('confirm_token', 100)->nullable();
            $table->string('avatar',255)->nullable();
            $table->rememberToken();
            $table->unsignedInteger('confirmed_at')->nullable();
            $table->unsignedInteger('deleted_at')->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('staff');
    }
}
