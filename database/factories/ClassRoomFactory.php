<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected static $courseIndex = 0;

    public function definition(): array
    {
        $courses = ['1r ESO', '2n ESO', '3r ESO', '4t ESO'];

        $course = $courses[self::$courseIndex % count($courses)];
        self::$courseIndex++;

        return [
            'course' => $course,
        ];
    }
}
