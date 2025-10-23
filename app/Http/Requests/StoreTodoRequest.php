<?php

namespace App\Http\Requests;

use App\TodoPriorityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'assignee' => 'nullable|string',
            //date validation with minimal date today
            'due_date' => 'required|after_or_equal:today',
            //numeric validation with default 0
            'time_tracked' => [
                Rule::default(0),
                'integer',
                'min:0'
            ],
            //numeric validation with default 0
            'status' => [
                Rule::default(0),
                'integer',
                'min:0'
            ],
            //enum validation with default as low
            'priority' => [
                'required',
                Rule::default(TodoPriorityEnum::Low),
                Rule::enum(TodoPriorityEnum::class),
            ],
        ];
    }
}
