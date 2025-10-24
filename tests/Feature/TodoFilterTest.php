<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoFilterTest extends TestCase
{
    use RefreshDatabase;

    private $todo1;
    private $todo2;
    private $todo3;
    private $todo4;
    private $todo5;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // We use simple integer IDs for assignees, no User model needed.
        $assigneeId1 = 'Sam';
        $assigneeId2 = 'John';

        // Create a predictable set of Todos to filter against
        $this->todo1 = Todo::factory()->create([
            'title' => 'Buy milk and eggs',
            'assignee' => $assigneeId1,
            'due_date' => '2025-11-10',
            'time_tracked' => 10,
            'status' => 'pending',
            'priority' => 'low',
        ]);

        $this->todo2 = Todo::factory()->create([
            'title' => 'Finish project report',
            'assignee' => $assigneeId2,
            'due_date' => '2025-11-15',
            'time_tracked' => 60,
            'status' => 'in_progress',
            'priority' => 'medium',
        ]);

        $this->todo3 = Todo::factory()->create([
            'title' => 'Call the client',
            'assignee' => 'Edo', // different assignee
            'due_date' => '2025-11-20',
            'time_tracked' => 120,
            'status' => 'completed',
            'priority' => 'high',
        ]);

        $this->todo4 = Todo::factory()->create([
            'title' => 'Review project timeline',
            'assignee' => $assigneeId2,
            'due_date' => '2025-11-15', // Same as todo2
            'time_tracked' => 30,
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $this->todo5 = Todo::factory()->create([
            'title' => 'Freelance weekly report',
            'assignee' => $assigneeId1,
            'due_date' => '2025-11-15',
            'time_tracked' => 180,
            'status' => 'open',
            'priority' => 'medium',
        ]);
    }

    public function test_filters_by_title_like_search()
    {
        $filters = ['title' => 'project'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(2, $todos);
        $this->assertTrue($todos->contains($this->todo2));
        $this->assertTrue($todos->contains($this->todo4));
    }

    public function test_filters_by_a_single_assignee()
    {
        $filters = ['assignee' => 'John'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(2, $todos);
        $this->assertTrue($todos->contains($this->todo2));
        $this->assertTrue($todos->contains($this->todo4));
    }

    public function test_filters_by_multiple_assignees()
    {
        $filters = ['assignee' => 'John,Edo'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(3, $todos);
    }

    public function test_filters_by_due_date_range()
    {
        $filters = ['due_start' => '2025-11-12', 'due_end' => '2025-11-20'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(4, $todos);
        $this->assertTrue($todos->contains($this->todo2));
        $this->assertTrue($todos->contains($this->todo3));
        $this->assertTrue($todos->contains($this->todo4));
        $this->assertTrue($todos->contains($this->todo5));
    }

    public function test_filters_by_due_date_single_day_when_end_date_is_empty()
    {
        $filters = ['due_start' => '2025-11-15', 'due_end' => ''];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(3, $todos);
        $this->assertTrue($todos->contains($this->todo2));
        $this->assertTrue($todos->contains($this->todo4));
        $this->assertTrue($todos->contains($this->todo5));
    }

    public function test_filters_by_due_date_single_day_when_end_date_is_not_present()
    {
        $filters = ['due_start' => '2025-11-10'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(1, $todos);
        $this->assertTrue($todos->contains($this->todo1));
    }

    public function test_filters_by_time_tracked_range()
    {
        $filters = ['tracked_min' => 30, 'tracked_max' => 60];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(2, $todos);
        $this->assertTrue($todos->contains($this->todo2));
        $this->assertTrue($todos->contains($this->todo4));
    }

    public function test_filters_by_time_tracked_min_only()
    {
        $filters = ['tracked_min' => 60];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(3, $todos);
        $this->assertTrue($todos->contains($this->todo2));
        $this->assertTrue($todos->contains($this->todo3));
        $this->assertTrue($todos->contains($this->todo5));
    }

    public function test_filters_by_time_tracked_max_only()
    {
        $filters = ['tracked_max' => 30];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(2, $todos);
        $this->assertTrue($todos->contains($this->todo1));
        $this->assertTrue($todos->contains($this->todo4));
    }

    public function test_filters_by_a_single_status()
    {
        $filters = ['status' => 'in_progress'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(1, $todos);
        $this->assertTrue($todos->contains($this->todo2));
    }

    public function test_filters_by_multiple_statuses()
    {
        $filters = ['status' => 'pending,completed'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(3, $todos);
        $this->assertTrue($todos->contains($this->todo1));
        $this->assertTrue($todos->contains($this->todo3));
        $this->assertTrue($todos->contains($this->todo4));
    }

    public function test_filters_by_a_single_priority()
    {
        $filters = ['priority' => 'high'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(1, $todos);
        $this->assertTrue($todos->contains($this->todo3));
    }

    public function test_filters_by_multiple_priorities()
    {
        $filters = ['priority' => 'low,medium'];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(4, $todos);
        $this->assertTrue($todos->contains($this->todo1));
        $this->assertTrue($todos->contains($this->todo2));
        $this->assertTrue($todos->contains($this->todo4));
        $this->assertTrue($todos->contains($this->todo5));
    }

    public function test_can_combine_multiple_filters()
    {
        $filters = [
            'assignee' => 'John',
            'status' => 'pending',
        ];

        $todos = Todo::filter($filters)->get();

        $this->assertCount(1, $todos);
        $this->assertTrue($todos->contains($this->todo4));
    }

    public function test_returns_all_todos()
    {
        $filters = [];
        $todos = Todo::filter($filters)->get();

        $this->assertCount(5, $todos);
    }
}
