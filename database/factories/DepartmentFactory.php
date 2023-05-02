<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $abbr=$this->faker->text(5);
        $name=$this->faker->name;
        return [
            'abbr_zh'=>$abbr,
            'name_zh' => $name,
        ];
    }
}
