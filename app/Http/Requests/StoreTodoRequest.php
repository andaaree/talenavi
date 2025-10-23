<?php

namespace App\Http\Requests;

use App\TodoPriorityEnum;
use App\TodoStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'assignee' => 'nullable|string',
            //date validation with minimal date today
            'due_date' => 'required|after_or_equal:today',
            'time_tracked' =>  'integer|min:0',
            //enum validation with default as pending
            'status' => 'sometimes|in:pending,open,in_progress,completed',
            //enum validation with default as low
            'priority' => 'sometimes|in:low,medium,high',
        ];
    }

    public function prepareForValidation(){
        $this->mergeIfMissing([
            'time_tracked' => 0, // Set default if 'time_tracked' is not present
            'status' => TodoStatusEnum::Pending->value, // Set default if 'status' is not present
            'priority' => TodoPriorityEnum::Low->value, // Set default if 'priority' is not present
        ]);
    }
}
