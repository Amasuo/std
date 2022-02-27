<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10),
            'capacity' => $this->faker->numberBetween(
                intval(config('consts.course.capacity_min')),
                intval(config('consts.course.capacity_max'))
            )
        ];
    }
}
