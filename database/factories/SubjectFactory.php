<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subjects>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $subjects = [
            'Maths',
            'Biology',
            'Chemistry',
            'Physics',
            'English',
            'History',
            'Geography',
            'Spanish',
            'French',
            'German',
            'Music',
            'Art',
            'Physical Education',
            'Technology',
            'Literature',
            'Economics',
        ];

        return [
            'name' => fake()->unique()->randomElement($subjects)
        ];
    }
}
