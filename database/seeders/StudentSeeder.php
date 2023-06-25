<?php
namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'fname' => 'Muhannad',
            'lname' => 'Ibrahim',
            'gender' => 'male',
            'phone' => 1234567892,        
            'email' => 'muhannad@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        Student::factory()
                ->count(5)
                ->create();
    }
}
