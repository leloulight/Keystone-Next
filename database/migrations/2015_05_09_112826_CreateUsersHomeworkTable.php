<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersHomeworkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_homework', function(Blueprint $table)
		{
		    $table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->integer('homework_id')->unsigned();
		    $table->boolean('complete')->default(false);
		    $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_homework');
	}

}
