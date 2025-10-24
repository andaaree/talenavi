<?php

namespace App\Models;

use App\TodoPriorityEnum;
use App\TodoStatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
      'due_date'=> 'datetime:d-m-Y',
    ];

    protected $attributes = [
        'time_tracked' => 0,
        'status' => TodoStatusEnum::Pending->value,
        'priority' => TodoPriorityEnum::Low->value,
    ];

    /**
     * Apply dynamic filters to the query.
     *
     * @param Builder $query
     * @param array   $filters  (Typically request()->all())
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        // 1. Title
        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        // 2. Assignee
        if (isset($filters['assignee'])) {
            $assignees = explode(',', $filters['assignee']);
            $query->whereIn('assignee', $assignees);
        }

        // 3. Due Date
        if (isset($filters['due_start'])) {
            $startDate = $filters['due_start'];
            $endDate = $filters['due_end'] ?? null;
            if (empty($endDate)) {
                // If no end date, search for the specific start date
                $query->whereDate('due_date', $startDate);
            } else {
                // Otherwise, search the range
                $query->whereBetween('due_date', [$startDate, $endDate]);
            }
        }

        if (isset($filters['due_end'])) {
            $startDate = $filters['due_start'] ?? null;
            $endDate = $filters['due_end'];
            if (empty($startDate)) {
                // If no start date, search for the specific end date
                $query->whereDate('due_date', $endDate);
            }
            else {
                // Otherwise, search the range
                $query->whereBetween('due_date', [$startDate, $endDate]);
            }
        }

        // 4. Time Tracked
        if (isset($filters['tracked_min'])) {
            $query->where('time_tracked', '>=', $filters['tracked_min']);
        }
        if (isset($filters['tracked_max'])) {
            $query->where('time_tracked', '<=', $filters['tracked_max']);
        }

        // 5. Status
        if (isset($filters['status'])) {
            $statuses = explode(',', $filters['status']);
            $query->whereIn('status', $statuses);
        }

        // 6. Priority
        if (isset($filters['priority'])) {
            $priorities = explode(',', $filters['priority']);
            $query->whereIn('priority', $priorities);
        }

        // Sort
        if(isset($filters['sortBy'])){
            $sort = explode('-',$filters['sortBy']);
            if (empty($sort[1])) {
                $query->orderBy($sort[0]);
            }else{
                $query->orderByDesc($sort[0]);
            }
        }

        return $query;
    }
}
