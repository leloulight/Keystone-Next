<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lessons', function(Blueprint $table)
		{
			$table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->string('subject');
		    $table->text('location');
		    $table->dateTime('start');
		    $table->dateTime('end');
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
		Schema::drop('lessons');
	}

}
