<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('homework', function(Blueprint $table)
		{
			$table->integer('homework_id')->unsigned()->unique()->index();
		    $table->string('title', 128);
		    $table->text('description')->nullable();
		    $table->text('subject')->nullable();
		    $table->dateTime('start');
		    $table->dateTime('end');
		    $table->boolean('can_delete');
		    $table->boolean('can_submit');
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
		Schema::drop('homework');
	}

}
