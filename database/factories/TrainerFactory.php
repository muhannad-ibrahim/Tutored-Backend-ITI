<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        $male_imgs = ['male_prof1.jpeg','male_prof2.jpeg','male_prof3.jpeg','male_prof4.jpg','male_prof5.jpg','male_prof6.jpg'];
        $female_imgs = ['female_prof1.jpg','female_prof2.jpg','female_prof3.jpg','female_prof4.jpg','female_prof5.jpg','female_prof6.jpg'];

        if ($gender=='male'){
            $img = $male_imgs[array_rand($male_imgs)];
        }else{
            $img = $female_imgs[array_rand($female_imgs)];
        }

        return [
            'fname' => $this->faker->name($gender),
            'lname' => $this->faker->name(),
            'gender' => $gender,
            'img' => $img,
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
        ];
    }
}
