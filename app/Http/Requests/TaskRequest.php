<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'progress' => 'nullable|numeric|min:0|max:1',
            'status' => 'nullable|in:todo,progress,done,inProgress',
            'gradient_start' => 'nullable|integer|min:0',
            'gradient_end' => 'nullable|integer|min:0',
            'due_date' => 'nullable|date',
        ];
    }
}
