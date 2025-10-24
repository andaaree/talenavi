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

    protected $fillable = [
        'title',
        'assignee',
        'time_tracked',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
      'due_date'=> 'datetime:d-m-Y',
      'status' => TodoStatusEnum::class,
      'priority' => TodoPriorityEnum::class
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

    public static function getSummary(string $type){
        return match ($type) {
            'assignee' => self::getAssigneeSummary(),
            'time_tracked' => self::getTimeTrackedSummary(),
            'status' => self::getStatusSummary(),
            'priority' => self::getPrioritySummary(),
            'due_date' => self::getDueDateSummary(),
            'keyword' => self::getKeywordSummary(),
            default => ['status' => 'error', 'messages' => 'Invalid summary type provided.','code' => 400],
        };
    }

    public static function getStatusSummary() : array{
        $status = self::query()
        ->select('status')
        ->selectRaw('count(*) as total')
        ->groupBy('status')
        ->pluck('total','status');

        return ['status_summary' => $status];
    }

    public static function getPrioritySummary() : array{
        $prior = self::query()
            ->select('priority')
            ->selectRaw('count(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority');
        return ['priority_summary' => $prior];
    }

    public static function getTimeTrackedSummary() : array{
        $time = self::query()
                ->selectRaw('YEAR(due_date) as year, MONTH(due_date) as month, SUM(time_tracked) as total_time')
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
        return ['time_tracked_summary' => $time];
    }

    public static function getAssigneeSummary() : array {
        $assignee = self::query()
            ->select('assignee')
            ->selectRaw('COUNT(*) as total_todos')
            ->selectRaw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending_todos")
            ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as total_timetracked_completed_todos")
            ->groupBy('assignee')
            ->get()
            ->keyBy('assignee')
            ->map(function ($item) {
                return collect($item->toArray())->except('assignee');
            });
            ;

        return ['assignee_summary' => $assignee];
    }

    public static function getDueDateSummary() : array {
        $dues = self::query()
            ->selectRaw('YEAR(due_date) as year, MONTH(due_date) as month, SUM(time_tracked) as total_time')
            ->selectRaw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending_todos")
            ->selectRaw("SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as total_open_todos")
            ->selectRaw("SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as total_in_progress_todos")
            ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as total_timetracked_completed_todos")
            ->groupBy('year')->groupBy('month')
            ->orderBy('year') ->orderBy('month')
            ->get();

        return ['due_date_summary' => $dues];
    }

}
