<?php

namespace Tests\Feature;

use App\Models\Grade;
use Tests\TestCase;

class GradeTest extends TestCase
{
    public function test_it_can_create_a_grade()
    {
        $data = [
            'gradeNum' => 5.5
        ];

        $response = $this->post(route('api.grades.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('grades', $data);
    }

    public function test_it_can_get_all_grades()
    {
        $grade = app(Grade::class)->factory()->create();

        $response = $this->get(route('api.grades.index'));

        $response->assertStatus(200);
        $response->assertJsonFragment(['gradeNum' => $grade->gradeNum]);
    }

    public function test_it_can_delete_a_grade()
    {
        $grade = app(Grade::class)->factory()->create();

        $response = $this->delete(route('api.grades.destroy', $grade->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('grades', ['id' => $grade->id]);
    }

    public function test_it_can_show_a_grade()
    {
        $grade = app(Grade::class)->factory()->create();

        $response = $this->get(route('api.grades.show', $grade->id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['gradeNum' => $grade->gradeNum]);
    }
}
