<?php

namespace Tests\Feature;

use App\Models\Student;
use Tests\TestCase;

class StudentTest extends TestCase
{
    public function test_it_can_create_a_student()
    {
        $data = [
            'name' => 'Jorge Sanchez',
            'surname' => 'Sanchez',
            'age' => 14,
            'classroom_id' => 1
        ];

        $response = $this->post(route('api.students.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('students', $data);
    }

    public function test_it_can_get_all_students()
    {
        $student = Student::factory()->create();

        $response = $this->get(route('api.students.index'));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $student->name]);
    }

    public function test_it_can_update_a_student()
    {
        $student = Student::factory()->create();

        $data = [
            'name' => 'Jorge Martínez',
            'surname' => 'Martínez',
            'age' => 16,
        ];

        $response = $this->put(route('api.students.update', $student->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('students', $data);
    }

    public function test_it_can_delete_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->delete(route('api.students.destroy', $student->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
