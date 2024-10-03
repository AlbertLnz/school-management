<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        ClassRoom::factory(4)->create();
        $students = Student::factory(10)->create();
        $subjects = Subject::factory(16)->create();

        foreach ($students as $student) {

            foreach ($subjects as $subject) {

                $studentSubjectId = DB::table('student_subject')->insertGetId([
                    'academic_year' => '2023-2024',
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $grades = Grade::factory(10)->create();

                foreach ($grades as $grade) {
                    DB::table('student_subject_grade')->insert([
                        'student_subject_id' => $studentSubjectId,
                        'grades_id' => $grade->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
