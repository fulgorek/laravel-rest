<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // make sure we seed a clean db
        DB::table('users')->truncate();

        // our first user
        DB::table('users')->insert([
            'name'       => 'admin',
            'email'      => 'admin@site.com',
            'password'   => Hash::make('password'),
            'created_at' => Carbon::now()
        ]);
    }
}
