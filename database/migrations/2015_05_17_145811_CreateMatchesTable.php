<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('matches', function(Blueprint $table)
		{
			$table->integer('match_id')->unsigned()->unique()->index();
		    $table->string('team_name', 128);
		    $table->string('opponent_name', 128);
		    $table->string('venue', 128);
		    $table->string('location', 128);
		    $table->string('result', 128);
		    $table->string('score_self', 64);
		    $table->string('score_opponent', 64);
		    $table->text('pre_comments');
		    $table->text('post_comments');
		    $table->dateTime('date');
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
		Schema::drop('matches');
	}

}
