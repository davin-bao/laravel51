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
            $table->tinyInteger('status')->default(1);
            $table->string('name', 25);
            $table->string('mobile', 25);
            $table->string('confirm_token', 100)->nullable();
            $table->string('avatar',255)->nullable();
            $table->rememberToken();
            $table->datetime('confirmed_at')->nullable();
            $table->softDeletes();
            $table->datetime('last_seen')->nullable();
            $table->nullableTimestamps();
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
