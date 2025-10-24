<?php

namespace App\Models;

use App\TodoPriorityEnum;
use App\TodoStatusEnum;
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
}
