<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeoplesMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('peoples_matches', function(Blueprint $table)
		{
		    $table->increments('id');
		    $table->integer('person_id')->unsigned();
		    $table->integer('match_id')->unsigned();
		    $table->boolean('is_staff')->default(false);
		    $table->boolean('is_team')->default(false);
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
		Schema::drop('peoples_matches');
	}


}
