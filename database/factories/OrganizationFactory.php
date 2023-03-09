<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'abbr'=>$this->faker->text(5),
            'full_name' => $this->faker->name,
            'email'=>$this->faker->email,
            'phone'=>$this->faker->phoneNumber,
            'address' => $this->faker->address,
            'country' => $this->faker->country,
            'href'=>'https://www.thewsu.org',
            'title'=>$this->faker->sentence(4),
            'description'=>$this->faker->text,
            'content'=>$this->faker->text
        ];
    }
}
