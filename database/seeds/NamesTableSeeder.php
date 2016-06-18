<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('names')->truncate();

        $faker = Faker\Factory::create();
        $limit = 100;
        $user_id = DB::table('users')->first()->id;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('names')->insert([
                'user_id'    => $user_id,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'created_at' => $faker->dateTimeBetween($startDate = '-1 week', $endDate = 'now', $timezone = date_default_timezone_get()),
                // 'updated_at' => Carbon::now()
            ]);
        }
    }
}
