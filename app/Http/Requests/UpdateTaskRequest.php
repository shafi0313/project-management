<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'project_id' => ['required', 'exists:projects,id'],
            'task_name' => ['required', 'string', 'min:1', 'max:255'],
            'task_description' => ['nullable'],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'status' => ['required', 'integer', 'min:1', 'max:127'],
        ];
    }
}
