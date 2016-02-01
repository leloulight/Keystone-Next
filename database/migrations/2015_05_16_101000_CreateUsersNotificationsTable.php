<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_notifications', function(Blueprint $table)
		{
		    $table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->integer('notification_id')->unsigned();
		    $table->boolean('is_read')->default(true);
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
		Schema::drop('users_notifications');
	}


}
