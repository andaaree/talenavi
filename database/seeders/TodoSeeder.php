<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assigneeId1 = 'Sam';
        $assigneeId2 = 'John';
        Todo::factory(30)->create();
        Todo::factory()->create([
            'title' => 'Buy milk and eggs',
            'assignee' => $assigneeId1,
            'due_date' => '2025-11-10',
            'time_tracked' => 10,
            'status' => 'pending',
            'priority' => 'low',
        ]);
        Todo::factory()->create([
                    'title' => 'Finish project report',
                    'assignee' => $assigneeId2,
                    'due_date' => '2025-11-15',
                    'time_tracked' => 60,
                    'status' => 'in_progress',
                    'priority' => 'medium',
                ]);
        Todo::factory()->create([
                    'title' => 'Call the client',
                    'assignee' => 'Edo', // different assignee
                    'due_date' => '2025-11-20',
                    'time_tracked' => 120,
                    'status' => 'completed',
                    'priority' => 'high',
                ]);
        Todo::factory()->create([
                    'title' => 'Review project timeline',
                    'assignee' => $assigneeId2,
                    'due_date' => '2025-11-15', // Same as todo2
                    'time_tracked' => 30,
                    'status' => 'pending',
                    'priority' => 'medium',
                ]);
        Todo::factory()->create([
                    'title' => 'Freelance weekly report',
                    'assignee' => $assigneeId1,
                    'due_date' => '2025-11-15',
                    'time_tracked' => 180,
                    'status' => 'open',
                    'priority' => 'medium',
                ]);
    }
}
