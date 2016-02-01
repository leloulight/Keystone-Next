<?php

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample() {
    	// DB::table('lessons')->delete();
    	// DB::table('lessons')->delete();
		DB::enableQueryLog();
		// App\Next\Models\User::find(60830)
		var_dump(App\Next\Data\SportsZoneSource::update());
		// var_dump(App\Next\Models\User::find(60829)->next_lesson_of('Economics', \Carbon\Carbon::now()->addDay()));
		$queries = DB::getQueryLog();
		$last_query = end($queries);
		// var_dump($last_query);
		// var_dump(App\Next\Models\User::find(60829)->lessons->first());
		// $response = $this->call('GET', '/');

		// $this->assertEquals(200, $response->getStatusCode());

	}

}


// MIN - 1 * (170) = NOW