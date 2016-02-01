<?php

use App\Next\Next;

Next::hello();

use App\Next\Data\HomeworkSource;
use App\Next\Layout\Colours;
use App\Next\Data\Cache;
use App\Next\Models\Homework;
use App\Next\Models\User;

// var_dump(Cache::update_cache_date('HomeworkSource'));

DB::table('homework')->delete();
DB::table('users_homework')->delete();
var_dump(HomeworkSource::parsed_data());

// var_dump(Homework::all());

$user = User::find(60829);
 foreach ($user->homework as $tree)
        echo $tree->homework_id . ':' . $tree->pivot->complete.'<br>';

?>