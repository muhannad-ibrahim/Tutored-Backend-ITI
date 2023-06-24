<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 'name',

        DB::table('users')->insert([

            'name' =>'admin1',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('12345678'),

        ]);
    }
}
