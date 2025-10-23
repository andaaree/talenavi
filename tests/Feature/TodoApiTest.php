<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase; // Resets the database after each test

    /**
     * Test: POST /todo
     * Can create a new todo
     */

     public function test_can_create_a_todo() : void{
        //prepare the data for the new todo
        $data = [
            'title' => 'My First Todo',
            'due_date' => now()->addDays(5)->format('d-m-y'),
            'assignee' => 'John Doe',
            'priority' => 'medium',
        ];
        //make post request with data
        $response = $this->postJson('/todo',$data);

        //assert
        $response->assertCreated(); // check 201 http status
        $response->assertJsonFragment(['title' => 'My First Todo']); // check response contain new title
        $this->assertDatabaseHas('todos',); // check db updated
     }

     /**
     * Test: POST /todo (Validation)
     * Test that creating a todo fails without required fields.
     */
    public function test_create_todo_fails_without_required_fields(): void
    {
        //send empty data
        $data = [];

        //make a POST request
        $response = $this->postJson('/todo', $data);

        //assert
        $response->assertStatus(422); //check for 422 Unprocessable Entity status
        $response->assertJsonValidationErrors(['title', 'due_date']); // Check for specific validation errors
    }
}
