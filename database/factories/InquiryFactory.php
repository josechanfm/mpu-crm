<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'parent_id'=>1,
            'root_id'=>1,
            'department_id'=>1,
            'title'=>$this->faker->text(5),
            'email'=>$this->faker->email,
            'phone'=>$this->faker->phoneNumber,
            'content'=>$this->faker->text,
        ];
    }
}
