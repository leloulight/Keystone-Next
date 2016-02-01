<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('people', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->unique()->index();
			$table->string('name', 50);
			$table->string('security_identifier', 47);
			$table->string('email', 40);
			$table->integer('user_type', 1);
			$table->string('house', 18)->unsigned()->nullable();
			$table->string('job_title', 80);
			$table->integer('year_level', 2)->unsigned()->nullable();
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
		Schema::drop('people');
	}

}
