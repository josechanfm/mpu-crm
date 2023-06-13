<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\email>
 */
class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'enquiry_id'=>$this->faker->numberBetween(1,10),
            'admin_user_id'=>'1',
            'sender'=>'no-replay@mpu.edu.mo',
            'receiver'=>$this->faker->email,
            'subject'=>$this->faker->text(5),
            'content'=>$this->faker->text(500),
        ];
    }
}
