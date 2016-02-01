<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastoralCareTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pastoralcare', function(Blueprint $table)
		{
			$table->integer('pastoralcare_id')->unsigned()->unique()->index();
		    $table->string('type', 64);
		    $table->string('category', 128);
		    $table->text('description');
		    $table->text('author')->nullable();
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
		Schema::drop('pastoralcare');
	}

}
