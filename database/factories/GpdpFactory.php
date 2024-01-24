<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GpdpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date_start=$this->faker->dateTimeBetween($startDate = 'now', $endDate="+30 days");
        $date_remind=$date_start->modify("+60 days");
        $date_due=$date_start->modify("+90 days");
        return [
            'staff_num'=>$this->faker->numberBetween(100,999),
            'name_zh'=>$this->faker->name(),
            'date_start'=>$date_start,
            'date_remind'=>$date_remind,
            'date_due'=>$date_due,
            'email'=>$this->faker->email,
        ];
    }
}
