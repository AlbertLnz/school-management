<?php

namespace Tests\Feature;

use App\Models\Subject;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    public function test_it_can_create_a_subject()
    {
        $data = [
            'name' => 'Matemàtica',
        ];

        $response = $this->post(route('api.subjects.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('subjects', $data);
    }

    public function test_it_can_get_all_subjects()
    {
        $subject = Subject::factory()->create();

        $response = $this->get(route('api.subjects.index'));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $subject->name]);
    }

    public function test_it_can_update_a_subject()
    {
        $subject = Subject::factory()->create();

        $data = [
            'name' => 'Matemàtica',
        ];

        $response = $this->put(route('api.subjects.update', $subject->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('subjects', $data);
    }

    public function test_it_can_delete_a_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->delete(route('api.subjects.destroy', $subject->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }

    public function test_it_can_show_a_subject()
    {
        $subject = Subject::factory()->create();

        $response = $this->get(route('api.subjects.show', $subject->id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $subject->name]);
    }
}
