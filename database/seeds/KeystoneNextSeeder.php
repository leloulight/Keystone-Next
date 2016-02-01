<?php
    
    use Illuminate\Database\Seeder;
    use App\Next\Models\User;
    use App\Next\Models\Homework;

    class KeystoneNextSeeder extends Seeder {
        
        public function run() {
            DB::table('users')->delete();
            DB::table('homework')->delete();
            DB::table('users_homework')->delete();

            // $this->command->info('Users created!');

            // $ecoOne = Homework::create(array(
            //     'homework_id' => 1234,
            //     'title' => 'Do shit plz',
            //     'description' => 'A description',
            //     'subject' => 'Economics',
            //     'start' => '2015-05-20 04:10:11',
            //     'end' => '2015-05-21 04:10:11',
            //     'can_delete' => false,
            //     'can_submit' => true,
            // ));

            // $ecoTwo = Homework::create(array(
            //     'homework_id' => 4534,
            //     'title' => 'More crap',
            //     'description' => 'boo!',
            //     'subject' => 'Economics',
            //     'start' => '2015-05-20 04:10:11',
            //     'end' => '2015-05-21 04:10:11',
            //     'can_delete' => false,
            //     'can_submit' => true,
            // ));

            // $history = Homework::create(array(
            //     'homework_id' => 26443,
            //     'title' => 'Sleepy',
            //     'description' => 'laaa',
            //     'subject' => 'History',
            //     'start' => '2015-05-20 04:10:11',
            //     'end' => '2015-05-21 04:10:11',
            //     'can_delete' => false,
            //     'can_submit' => true,
            // ));
            // link our bears to picnics ---------------------
            // for our purposes we'll just add all bears to both picnics for our many to many relationship

            $this->command->info($userOne->user_id);
            $this->command->info($ecoOne->homework_id);

            $userOne->homework()->attach($ecoOne->homework_id);
            $userOne->homework()->attach($ecoTwo->homework_id);
            $userTwo->homework()->attach($ecoTwo->homework_id);
            $userTwo->homework()->attach($history->homework_id);
            $userThree->homework()->attach($history->homework_id);

            $this->command->info('They are terrorizing picnics!');

        }

    }
?>