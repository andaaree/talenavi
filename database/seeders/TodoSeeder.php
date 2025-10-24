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
        $js = json_decode('
        [
            {
                "title": "Email Dev Lead (Sprint Planning)",
                "assignee": "Sam",
                "due_date": "2025-10-24",
                "time_tracked": 2893,
                "status": "pending",
                "priority": "medium"
            },
            {
                "title": "Call Client A (Feedback)",
                "assignee": "John",
                "due_date": "2025-10-24",
                "time_tracked": 503,
                "status": "in_progress",
                "priority": "high"
            },
            {
                "title": "Make Q4 Sales Report",
                "assignee": "Edo",
                "due_date": "2025-10-27",
                "time_tracked": 82,
                "status": "in_progress",
                "priority": "medium"
            },
            {
                "title": "Task Review (Bug #451)",
                "assignee": "Dante",
                "due_date": "2025-10-25",
                "time_tracked": 1063,
                "status": "completed",
                "priority": "medium"
            },
            {
                "title": "Email Client B (Invoice)",
                "assignee": "Sam",
                "due_date": "2025-10-24",
                "time_tracked": 4626,
                "status": "completed",
                "priority": "low"
            },
            {
                "title": "Review PR #112",
                "assignee": "John",
                "due_date": "2025-10-25",
                "time_tracked": 3653,
                "status": "open",
                "priority": "medium"
            },
            {
                "title": "Call Vendor (Supply ETA)",
                "assignee": "Edo",
                "due_date": "2025-10-24",
                "time_tracked": 7957,
                "status": "pending",
                "priority": "low"
            },
            {
                "title": "Make Weekly Report (Marketing)",
                "assignee": "Dante",
                "due_date": "2025-10-24",
                "time_tracked": 92,
                "status": "in_progress",
                "priority": "medium"
            },
            {
                "title": "Task Review (Feature #789)",
                "assignee": "Sam",
                "due_date": "2025-10-26",
                "time_tracked": 28,
                "status": "open",
                "priority": "medium"
            },
            {
                "title": "Email Dev Lead (API Docs)",
                "assignee": "John",
                "due_date": "2025-10-27",
                "time_tracked": 0,
                "status": "pending",
                "priority": "low"
            },
            {
                "title": "Finalize Client Presentation",
                "assignee": "Edo",
                "due_date": "2025-10-28",
                "time_tracked": 66,
                "status": "in_progress",
                "priority": "high"
            },
            {
                "title": "Call Client A (Demo Setup)",
                "assignee": "Dante",
                "due_date": "2025-10-25",
                "time_tracked": 577,
                "status": "pending",
                "priority": "high"
            },
            {
                "title": "Make Expense Report",
                "assignee": "Sam",
                "due_date": "2025-10-24",
                "time_tracked": 34,
                "status": "completed",
                "priority": "low"
            },
            {
                "title": "Task Review (UI Mockups)",
                "assignee": "John",
                "due_date": "2025-10-27",
                "time_tracked": 128,
                "status": "pending",
                "priority": "medium"
            },
            {
                "title": "Email Dev Lead (Bug #455)",
                "assignee": "Edo",
                "due_date": "2025-10-24",
                "time_tracked": 44,
                "status": "completed",
                "priority": "medium"
            },
            {
                "title": "Draft Project Proposal",
                "assignee": "Dante",
                "due_date": "2025-10-30",
                "time_tracked": 9860,
                "status": "in_progress",
                "priority": "medium"
            },
            {
                "title": "Call IT Support (Printer)",
                "assignee": "Sam",
                "due_date": "2025-10-24",
                "time_tracked": 8549,
                "status": "pending",
                "priority": "low"
            },
            {
                "title": "Review Timesheets",
                "assignee": "John",
                "due_date": "2025-10-24",
                "time_tracked": 397,
                "status": "in_progress",
                "priority": "low"
            },
            {
                "title": "Make Sprint 11 Plan",
                "assignee": "Edo",
                "due_date": "2025-10-29",
                "time_tracked": 163,
                "status": "open",
                "priority": "high"
            },
            {
                "title": "Email Client C (Intro)",
                "assignee": "Dante",
                "due_date": "2025-10-26",
                "time_tracked": 8148,
                "status": "pending",
                "priority": "medium"
            },
            {
                "title": "Task Review (Security Patch)",
                "assignee": "Sam",
                "due_date": "2025-10-25",
                "time_tracked": 5420,
                "status": "in_progress",
                "priority": "high"
            },
            {
                "title": "Call Dev Lead (Deployment)",
                "assignee": "John",
                "due_date": "2025-10-24",
                "time_tracked": 5240,
                "status": "completed",
                "priority": "high"
            },
            {
                "title": "Make Monthly Report (Finance)",
                "assignee": "Edo",
                "due_date": "2025-10-31",
                "time_tracked": 692,
                "status": "pending",
                "priority": "medium"
            },
            {
                "title": "Email Client A (Contract)",
                "assignee": "Dante",
                "due_date": "2025-10-27",
                "time_tracked": 587,
                "status": "completed",
                "priority": "medium"
            },
            {
                "title": "Update Staging Server",
                "assignee": "Sam",
                "due_date": "2025-10-25",
                "time_tracked": 18,
                "status": "pending",
                "priority": "low"
            },
            {
                "title": "Call Client B (Check-in)",
                "assignee": "John",
                "due_date": "2025-10-28",
                "time_tracked": 168,
                "status": "open",
                "priority": "low"
            },
            {
                "title": "Task Review (Bug #458)",
                "assignee": "Edo",
                "due_date": "2025-10-26",
                "time_tracked": 4767,
                "status": "in_progress",
                "priority": "medium"
            },
            {
                "title": "Make Team Meeting Agenda",
                "assignee": "Dante",
                "due_date": "2025-10-27",
                "time_tracked": 532,
                "status": "completed",
                "priority": "low"
            },
            {
                "title": "Email Dev Lead (Code Freeze)",
                "assignee": "Sam",
                "due_date": "2025-10-26",
                "time_tracked": 0,
                "status": "pending",
                "priority": "medium"
            },
            {
                "title": "Review Marketing Copy",
                "assignee": "John",
                "due_date": "2025-10-25",
                "time_tracked": 6217,
                "status": "in_progress",
                "priority": "low"
            }
        ]
        ',true);
        foreach ($js as $j) {
            Todo::create($j);
        }
    }
}
