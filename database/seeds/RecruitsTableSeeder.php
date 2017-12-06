<?php

use Illuminate\Database\Seeder;

class RecruitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Recruit::class, 30)->make()->each(function ($recruit) {
            $team = \App\Team::inRandomOrder()->first();
            $team->recruits()->save($recruit);
        });
    }
}
