<?php

use App\Team;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::chunk(30, function ($users) {
            foreach ($users as $user) {
                try {
                    $faker = app(Faker::class);
                    $user_team_count = $faker->numberBetween(0, 2);
                    for ($i = 0; $i < $user_team_count; ++$i) {
                        $team = new Team([
                            'team_id' => time() . mt_rand(100000, 999999),
                        ]);
                        $team->save();
                        $team->team_id = $team->id;
                        $team->save();

                        $team->users()->save($user, [
                            'position' => 'leader',
                            'status' => 'verified',
                        ]);
                        $user_member_count = $faker->numberBetween(0, 2);
                        for ($j = 0; $j < $user_member_count; ++$j) {
                            $user_member = User::inRandomOrder()->first();
                            $team->users()->save($user_member, [
                                'position' => 'member',
                                'status' => 'waiting',
                            ]);
                        }
                    }
                } catch (PDOException $exception) {
                    echo $exception->getMessage(), PHP_EOL;
                }
            }
        });
    }
}
