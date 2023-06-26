<?php
namespace Database\Seeders;

use App\Models\Trainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $trainer1= Trainer::factory()
        ->create();

        $trainer2= Trainer::factory()
        ->create();

        $trainer3= Trainer::factory()
        ->create();

        DB::table('courses')->insert([
            'name' => 'HTML 5 & CSS 3',
            'price' => 200,
            'duration' => 15,
            'img' => 'html5_css3.png',
            'desc'=>'HTML & CSS course',
            'preq' =>'None',
            'trainer_id' => $trainer1->id,
            'category_id' => 1,
        ]);

        DB::table('courses')->insert([
            'name' => 'CCNA',
            'price' => 2000,
            'duration' => 35,
            'img' => 'ccna.jpg',
            'desc'=>'CCNA course',
            'preq' =>'prerequisites',
            'trainer_id' =>$trainer2->id,
            'category_id' => 5,
        ]);

        DB::table('courses')->insert([
            'name' => 'JavaScript',
            'price' => 2000,
            'duration' => 35,
            'img' => 'js.png',
            'desc'=>'JavaScript course',
            'preq' =>'Basic knowledge of HTML & CSS',
            'trainer_id' =>$trainer3->id,
            'category_id' => 1,
        ]);

        DB::table('courses')->insert([
            'name' => 'Marketing',
            'price' => 1500,
            'duration' => 25,
            'img' => 'marketing.png',
            'desc'=>'Marketing course',
            'preq' =>'prerequisites',
            'trainer_id' => $trainer1->id,
            'category_id' => 3,
        ]);

        DB::table('courses')->insert([
            'name' => 'Nutrition',
            'price' => 200,
            'duration' => 7,
            'img' => 'nutrition.jpg',
            'desc'=>'Nutritions course',
            'preq' =>'None',
            'trainer_id' => $trainer2->id,
            'category_id' => 4,
        ]);

        DB::table('courses')->insert([
            'name' => 'Cyber Security',
            'price' => 2000,
            'duration' => 25,
            'img' => 'cyber_security.jpeg',
            'desc'=>'Cyber security course',
            'preq' =>'prerequisites',
            'trainer_id' => $trainer2->id,
            'category_id' => 5,
        ]);

        DB::table('courses')->insert([
            'name' => 'Fundamentals of Graphic Design',
            'price' => 200,
            'duration' => 20,
            'img' => 'graphic_design.jpg',
            'desc'=>'Graphic Design course',
            'preq' =>'prerequisites',
            'trainer_id' => $trainer1->id,
            'category_id' => 2,
        ]);

        DB::table('courses')->insert([
            'name' => 'Introduction to UI Design',
            'price' => 200,
            'duration' => 15,
            'img' => 'ui.png',
            'desc'=>'In this course, you will gain an understanding of the critical importance of user interface design. You will also learn industry-standard methods for how to approach the design of a user interface and key theories and frameworks that underlie the design of most interfaces you use today.

            Through a series of case studies on commercial systems - many of which you likely use on a regular basis - we will illustrate the benefits of good design. We will also demonstrate how the costs of bad design can often be severe (in user experience, money, and even human lives).

            You will then gain a high-level understanding of the user-interface design process. You will be introduced to common design scenarios - e.g. improving on existing designs and starting a new design from scratch - and the general design processes that tend to be used for each scenario.

            Finally, we will begin introducing the large body of existing knowledge on design by providing overviews of core user interface design theories and concepts. This key foundational information will help you avoid “reinventing the wheel” when you are designing your interfaces in this specialization.',
            'preq' =>'prerequisites',
            'trainer_id' => $trainer3->id,
            'category_id' => 2,
        ]);

        DB::table('courses')->insert([
            'name' => 'Google Cloud',
            'price' => 3000,
            'duration' => 10,
            'img' => 'google_cloud.png',
            'desc'=>'Cloud Computing course',
            'preq' =>'prerequisites',
            'trainer_id' => $trainer3->id,
            'category_id' => 5,
        ]);

        DB::table('courses')->insert([
            'name' => 'Science of Exercise',
            'price' => 200,
            'duration' => 5,
            'img' => 'excercise.jpeg',
            'desc'=>'health course',
            'preq' =>'prerequisites',
            'trainer_id' => $trainer1->id,
            'category_id' => 4,
        ]);

    }
}
